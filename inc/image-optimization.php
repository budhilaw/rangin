<?php
/**
 * Image Optimization and CDN Integration
 * - Optional AVIF/WebP conversion (on upload and bulk)
 * - Optional Cloudflare CDN URL rewriting (format/quality auto)
 *
 * @package PersonalWebsite
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Helpers to read options
 */
function image_opt_enabled() {
    return (bool) get_option('enable_image_optimization', false);
}

function image_opt_target_format() {
    $fmt = get_option('image_opt_target_format', 'webp');
    return ($fmt === 'avif') ? 'avif' : 'webp';
}

function image_opt_convert_original_enabled() {
    return (bool) get_option('image_opt_convert_original', false);
}

function image_opt_max_megapixels() {
    $mp = absint(get_option('image_opt_max_mp', 12));
    if ($mp < 1) { $mp = 12; }
    return $mp;
}

function image_opt_convert_quality() {
    $q = absint(get_option('image_opt_convert_quality', 85));
    if ($q < 1) $q = 1; if ($q > 100) $q = 100;
    return $q;
}

function image_opt_force_modern_only() {
    return (bool) get_option('image_opt_force_modern_only', false);
}

function image_opt_should_convert_path($path) {
    $info = @getimagesize($path);
    if (!$info) { return true; }
    $w = isset($info[0]) ? intval($info[0]) : 0;
    $h = isset($info[1]) ? intval($info[1]) : 0;
    if ($w <= 0 || $h <= 0) { return true; }
    $mp = ($w * $h) / 1000000.0;
    return ($mp <= image_opt_max_megapixels());
}

function cloudflare_cdn_enabled() {
    return (bool) get_option('enable_cloudflare_cdn', false);
}

function cloudflare_cdn_domain() {
    return trim((string) get_option('cloudflare_cdn_domain', ''));
}

function cloudflare_quality() {
    $q = absint(get_option('cloudflare_image_quality', 85));
    return min(100, max(1, $q));
}

/**
 * Convert a file to WebP/AVIF using Imagick when available
 */
function image_opt_convert_file($file_path, $quality = 85) {
    if (!class_exists('Imagick') || !file_exists($file_path)) {
        // Fallback to GD-only path (for AVIF via GD if available)
        return image_opt_convert_file_gd_only($file_path, $quality);
    }
    $generated = array();
    $target = image_opt_target_format();
    try {
        $img = new Imagick($file_path);
        $img->setImageCompressionQuality($quality);

        if ($target === 'webp') {
            // Create WebP
            $webp_path = preg_replace('/\.[^.]+$/', '.webp', $file_path);
            $webp = clone $img;
            $webp->setImageFormat('webp');
            if ($webp->writeImage($webp_path)) {
                $generated['webp'] = $webp_path;
            }
            $webp->clear();
            $webp->destroy();
        }

        // Create AVIF if supported
        if ($target === 'avif') {
            $avif_path = image_opt_write_avif($img, $file_path, $quality);
            if ($avif_path) { $generated['avif'] = $avif_path; }
        }

        $img->clear();
        $img->destroy();
    } catch (Exception $e) {
        // Silent fail to avoid breaking uploads
    }

    // If AVIF requested but not produced by Imagick, try GD fallback
    if ($target === 'avif' && empty($generated['avif'])) {
        $avif_from_gd = image_opt_convert_avif_gd($file_path, $quality);
        if ($avif_from_gd) { $generated['avif'] = $avif_from_gd; }
    }
    return $generated;
}

/**
 * On upload, create modern formats when enabled
 */
function image_opt_on_upload($metadata, $attachment_id) {
    if (!image_opt_enabled()) { return $metadata; }

    $file = get_attached_file($attachment_id);
    if (!$file || !file_exists($file)) { return $metadata; }

    // Convert original (optional)
    if (image_opt_convert_original_enabled() && image_opt_should_convert_path($file)) {
        image_opt_convert_file($file, image_opt_convert_quality());
    }

    // Convert generated sizes
    if (!empty($metadata['sizes']) && is_array($metadata['sizes'])) {
        $base_dir = dirname($file);
        foreach ($metadata['sizes'] as $size) {
            if (!empty($size['file'])) {
                $sz_path = trailingslashit($base_dir) . $size['file'];
                if (file_exists($sz_path) && image_opt_should_convert_path($sz_path)) {
                    image_opt_convert_file($sz_path, image_opt_convert_quality());
                }
            }
        }
    }
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'image_opt_on_upload', 10, 2);

/**
 * Bulk conversion utility (simple, synchronous, limited to avoid timeouts)
 */
function image_opt_bulk_convert($limit = 50) {
    if (!image_opt_enabled()) { return 0; }
    $args = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => $limit,
        'post_mime_type' => 'image',
        'fields'         => 'ids',
    );
    $ids = get_posts($args);
    $count = 0;
    foreach ($ids as $id) {
        $file = get_attached_file($id);
        if ($file && file_exists($file)) {
            $converted_any = false;
            $out = image_opt_convert_file($file, image_opt_convert_quality());
            if (!empty($out)) { $converted_any = true; }

            $meta = wp_get_attachment_metadata($id);
            if (!empty($meta['sizes']) && is_array($meta['sizes'])) {
                $base_dir = dirname($file);
                foreach ($meta['sizes'] as $size) {
                    if (!empty($size['file'])) {
                        $sz_path = trailingslashit($base_dir) . $size['file'];
                        if (file_exists($sz_path)) {
                            $out2 = image_opt_convert_file($sz_path, 85);
                            if (!empty($out2)) { $converted_any = true; }
                        }
                    }
                }
            }

            if ($converted_any) { $count++; }
        }
    }
    return $count;
}

/**
 * Bulk conversion with logs for admin UI
 */
function image_opt_bulk_convert_with_log($limit = 50) {
    // Limit default to a smaller batch to avoid timeouts with AVIF
    if (!$limit || $limit > 10) { $limit = 10; }
    $result = array('count' => 0, 'logs' => array());
    if (!image_opt_enabled()) {
        $result['logs'][] = __('Image optimization is disabled. Enable it to run conversion.', 'personal-website');
        return $result;
    }
    $args = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => $limit,
        'post_mime_type' => 'image',
        'fields'         => 'ids',
    );
    $ids = get_posts($args);
    foreach ($ids as $id) {
        $file = get_attached_file($id);
        $title = get_the_title($id);
        if (!$file || !file_exists($file)) {
            $result['logs'][] = sprintf('⛔ %s — file missing', esc_html($title));
            continue;
        }
        $base_dir = dirname($file);
        $created_webp = 0; $created_avif = 0; $size_count = 0;
        $out = image_opt_convert_file($file, 85);
        if (!empty($out['webp'])) { $created_webp++; }
        if (!empty($out['avif'])) { $created_avif++; }
        $meta = wp_get_attachment_metadata($id);
        if (!empty($meta['sizes']) && is_array($meta['sizes'])) {
            foreach ($meta['sizes'] as $size) {
                if (!empty($size['file'])) {
                    $sz_path = trailingslashit($base_dir) . $size['file'];
                    if (file_exists($sz_path)) {
                        $o2 = image_opt_convert_file($sz_path, image_opt_convert_quality());
                        if (!empty($o2)) { $size_count++; }
                        if (!empty($o2['webp'])) { $created_webp++; }
                        if (!empty($o2['avif'])) { $created_avif++; }
                    }
                }
            }
        }
        if ($created_webp || $created_avif) {
            $result['count']++;
        }
        $base = basename($file);
        $result['logs'][] = sprintf('✅ %s — webp:%d, avif:%d (sizes converted: %d)', esc_html($base), $created_webp, $created_avif, $size_count);
    }
    return $result;
}
/**
 * Cloudflare CDN rewriting
 * Uses Image Resizing endpoint: /cdn-cgi/image/format=auto,quality=Q/...path
 */
function image_opt_cf_rewrite_url($url) {
    if (!cloudflare_cdn_enabled()) { return $url; }
    $parsed = wp_parse_url($url);
    if (empty($parsed['path'])) { return $url; }

    $host = cloudflare_cdn_domain();
    $scheme = is_ssl() ? 'https' : 'http';
    if (empty($host)) {
        // Use original host if none provided
        $home = wp_parse_url(home_url());
        $host = $home['host'] ?? ($parsed['host'] ?? '');
        if (empty($host)) { return $url; }
        $scheme = $home['scheme'] ?? $scheme;
    }

    $quality = cloudflare_quality();
    // Preserve original path and query (Image Resizing expects the path after the directive)
    $path_with_query = $parsed['path'] . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
    $cf = sprintf('%s://%s/cdn-cgi/image/format=auto,quality=%d%s', $scheme, $host, $quality, $path_with_query);
    return $cf;
}

function image_opt_filter_wp_get_attachment_image_src($image, $attachment_id, $size, $icon) {
    if (!is_array($image)) { return $image; }
    $image[0] = image_opt_cf_rewrite_url($image[0]);
    return $image;
}
add_filter('wp_get_attachment_image_src', 'image_opt_filter_wp_get_attachment_image_src', 10, 4);

function image_opt_filter_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    if (!is_array($sources)) { return $sources; }
    foreach ($sources as &$src) {
        if (!empty($src['url'])) {
            $src['url'] = image_opt_cf_rewrite_url($src['url']);
        }
    }
    return $sources;
}
add_filter('wp_calculate_image_srcset', 'image_opt_filter_srcset', 10, 5);

/**
 * Wrap attachment image HTML with <picture> offering AVIF/WEBP if available
 */
function image_opt_wrap_picture($html, $attachment_id, $size, $icon, $attr) {
    if (!image_opt_enabled()) { return $html; }
    if (stripos($html, '<picture') !== false) { return $html; }

    // Find the src URL in the generated <img>
    if (!preg_match('/\ssrc=\"([^\"]+)\"/i', $html, $m)) {
        return $html;
    }
    $src = $m[1];

    // Map URL to path
    $uploads = wp_get_upload_dir();
    if (empty($uploads['baseurl']) || empty($uploads['basedir'])) {
        return $html;
    }
    if (strpos($src, $uploads['baseurl']) !== 0) {
        return $html; // external image
    }
    $rel = ltrim(substr($src, strlen($uploads['baseurl'])), '/');
    $path = trailingslashit($uploads['basedir']) . $rel;

    $target = image_opt_target_format();
    // Build modern variants if they exist (single chosen format)
    $avif_url = $webp_url = '';
    $avif_path = preg_replace('/\.[^.]+$/', '.avif', $path);
    $webp_path = preg_replace('/\.[^.]+$/', '.webp', $path);
    if ($target === 'avif' && $avif_path && file_exists($avif_path)) {
        $avif_url = preg_replace('/\.[^.]+$/', '.avif', $src);
    }
    if ($target === 'webp' && $webp_path && file_exists($webp_path)) {
        $webp_url = preg_replace('/\.[^.]+$/', '.webp', $src);
    }

    if (!$avif_url && !$webp_url) {
        return $html;
    }

    // Optionally pass through Cloudflare rewriting for modern URLs
    if ($avif_url) { $avif_url = image_opt_cf_rewrite_url($avif_url); }
    if ($webp_url) { $webp_url = image_opt_cf_rewrite_url($webp_url); }
    $fallback_url = image_opt_cf_rewrite_url($src);

    // If forcing modern only and variant exists, return single <img> with modern src
    if (image_opt_force_modern_only()) {
        if ($target === 'avif' && $avif_url) {
            $html_fallback = preg_replace('/\ssrc=\"([^\"]+)\"/i', ' src="' . esc_url($avif_url) . '"', $html, 1);
            return $html_fallback;
        }
        if ($target === 'webp' && $webp_url) {
            $html_fallback = preg_replace('/\ssrc=\"([^\"]+)\"/i', ' src="' . esc_url($webp_url) . '"', $html, 1);
            return $html_fallback;
        }
    }

    // Reuse original <img> as fallback, but ensure src stays original for non-supporting browsers
    $picture = '<picture>';
    if ($target === 'avif' && $avif_url) {
        $picture .= '<source type="image/avif" srcset="' . esc_url($avif_url) . '">';
    }
    if ($target === 'webp' && $webp_url) {
        $picture .= '<source type="image/webp" srcset="' . esc_url($webp_url) . '">';
    }
    // Ensure fallback img uses possibly CDN-rewritten URL
    $html_fallback = preg_replace('/\ssrc=\"([^\"]+)\"/i', ' src="' . esc_url($fallback_url) . '"', $html, 1);
    $picture .= $html_fallback . '</picture>';
    return $picture;
}
add_filter('wp_get_attachment_image', 'image_opt_wrap_picture', 10, 5);

/**
 * -------- GD Fallback Utilities (AVIF) --------
 */
function image_opt_gd_avif_supported() {
    return function_exists('imageavif');
}

function image_opt_create_from_path_gd($path) {
    $info = @getimagesize($path);
    if (!$info) { return null; }
    $mime = $info['mime'] ?? '';
    switch ($mime) {
        case 'image/jpeg': return imagecreatefromjpeg($path);
        case 'image/png':  return imagecreatefrompng($path);
        case 'image/gif':  return imagecreatefromgif($path);
        case 'image/webp': return function_exists('imagecreatefromwebp') ? imagecreatefromwebp($path) : null;
        default: return null;
    }
}

function image_opt_convert_avif_gd($file_path, $quality = 85) {
    if (!image_opt_gd_avif_supported()) { return ''; }
    $im = image_opt_create_from_path_gd($file_path);
    if (!$im) { return ''; }
    // For PNG with alpha, ensure alpha is preserved
    imagealphablending($im, false);
    imagesavealpha($im, true);
    $avif_path = preg_replace('/\.[^.]+$/', '.avif', $file_path);
    // quality range for imageavif: 0 (best) to 100 (worst) in some builds, others 0-100 where higher better.
    // Use provided quality; implementations vary but it's acceptable.
    if (@imageavif($im, $avif_path, $quality)) {
        // Validate non-zero size
        if (!file_exists($avif_path) || filesize($avif_path) === 0) {
            @unlink($avif_path);
            imagedestroy($im);
            return '';
        }
        imagedestroy($im);
        return $avif_path;
    }
    imagedestroy($im);
    return '';
}

function image_opt_convert_file_gd_only($file_path, $quality = 85) {
    $generated = array();
    if (image_opt_target_format() === 'avif') {
        $avif = image_opt_convert_avif_gd($file_path, $quality);
        if ($avif) { $generated['avif'] = $avif; }
    }
    // We intentionally do not attempt WebP in GD-only path as most servers already succeed via Imagick.
    // If needed, we can add a GD WebP fallback in future.
    return $generated;
}

/**
 * Render a <picture> for an arbitrary uploads URL (theme options, etc.)
 * Falls back to plain <img> when optimization disabled or variants missing
 */
function image_opt_picture_for_url($url, $attrs = array()) {
    if (empty($url)) { return ''; }
    $attr_html = '';
    foreach ($attrs as $k => $v) {
        if ($v === null || $v === '') continue;
        $attr_html .= ' ' . esc_attr($k) . '="' . esc_attr($v) . '"';
    }

    // If optimization disabled, return plain img
    if (!image_opt_enabled()) {
        return '<img src="' . esc_url($url) . '"' . $attr_html . ' />';
    }

    $uploads = wp_get_upload_dir();
    if (empty($uploads['baseurl']) || empty($uploads['basedir'])) {
        return '<img src="' . esc_url($url) . '"' . $attr_html . ' />';
    }
    if (strpos($url, $uploads['baseurl']) !== 0) {
        // External URL — leave as-is
        return '<img src="' . esc_url($url) . '"' . $attr_html . ' />';
    }

    $rel = ltrim(substr($url, strlen($uploads['baseurl'])), '/');
    $path = trailingslashit($uploads['basedir']) . $rel;

    $target = image_opt_target_format();
    $avif_url = $webp_url = '';
    $avif_path = preg_replace('/\.[^.]+$/', '.avif', $path);
    $webp_path = preg_replace('/\.[^.]+$/', '.webp', $path);
    if ($target === 'avif' && $avif_path && file_exists($avif_path)) {
        $avif_url = preg_replace('/\.[^.]+$/', '.avif', $url);
    }
    if ($target === 'webp' && $webp_path && file_exists($webp_path)) {
        $webp_url = preg_replace('/\.[^.]+$/', '.webp', $url);
    }

    // If no variant exists, return plain img
    if ($target === 'avif' && !$avif_url) {
        return '<img src="' . esc_url($url) . '"' . $attr_html . ' />';
    }
    if ($target === 'webp' && !$webp_url) {
        return '<img src="' . esc_url($url) . '"' . $attr_html . ' />';
    }

    // Rewriting via Cloudflare when enabled
    $fallback_url = image_opt_cf_rewrite_url($url);
    if ($avif_url) { $avif_url = image_opt_cf_rewrite_url($avif_url); }
    if ($webp_url) { $webp_url = image_opt_cf_rewrite_url($webp_url); }

    // If forcing modern only and variant exists, output a single <img>
    if (image_opt_force_modern_only()) {
        if ($target === 'avif' && $avif_url) {
            return '<img src="' . esc_url($avif_url) . '"' . $attr_html . ' />';
        }
        if ($target === 'webp' && $webp_url) {
            return '<img src="' . esc_url($webp_url) . '"' . $attr_html . ' />';
        }
    }

    $html  = '<picture>';
    if ($target === 'avif' && $avif_url) {
        $html .= '<source type="image/avif" srcset="' . esc_url($avif_url) . '">';
    }
    if ($target === 'webp' && $webp_url) {
        $html .= '<source type="image/webp" srcset="' . esc_url($webp_url) . '">';
    }
    $html .= '<img src="' . esc_url($fallback_url) . '"' . $attr_html . ' />';
    $html .= '</picture>';
    return $html;
}

/**
 * ---- Admin AJAX: Progressive bulk conversion ----
 */
function image_opt_ajax_bulk_step() {
    check_ajax_referer('image_opt_nonce', 'nonce');
    if (!current_user_can('upload_files')) {
        wp_send_json_error(array('message' => 'forbidden'));
    }
    if (!image_opt_enabled()) {
        wp_send_json_error(array('message' => 'disabled'));
    }
    $format = image_opt_target_format();
    $limit = 1; // keep each step tiny to avoid 502s on heavy AVIF encodes

    // Fetch a small batch of attachments that are not marked done
    $args = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 50, // get a window and filter in PHP
        'post_mime_type' => 'image',
        'fields'         => 'ids',
        'orderby'        => 'ID',
        'order'          => 'DESC',
    );
    $ids = get_posts($args);

    $todo = array();
    foreach ($ids as $id) {
        $done = get_post_meta($id, '_image_opt_' . $format . '_done', true);
        $skip = get_post_meta($id, '_image_opt_' . $format . '_skip', true);
        if ($done !== '1' && $skip !== '1') { $todo[] = $id; }
        if (count($todo) >= $limit) break;
    }

    $processed = 0; $logs = array();
    foreach ($todo as $id) {
        $t0 = microtime(true);
        @set_time_limit(10);
        $file = get_attached_file($id);
        if (!$file || !file_exists($file)) { $logs[] = '⛔ missing file #' . $id; continue; }
        $base_dir = dirname($file);
        $count_fmt = 0; $sizes_done = 0;
        if (image_opt_convert_original_enabled() && image_opt_should_convert_path($file)) {
            $out = image_opt_convert_file($file, image_opt_convert_quality());
            if (!empty($out[$format])) { $count_fmt++; }
        }
        $meta = wp_get_attachment_metadata($id);
        if (!empty($meta['sizes'])) {
            foreach ($meta['sizes'] as $sz) {
                if (empty($sz['file'])) continue;
                $p = trailingslashit($base_dir) . $sz['file'];
                if (!file_exists($p) || !image_opt_should_convert_path($p)) continue;
                // Guard: keep total time per item tight
                if ((microtime(true) - $t0) > 7) { break; }
                $o2 = image_opt_convert_file($p, image_opt_convert_quality());
                if (!empty($o2[$format])) { $count_fmt++; $sizes_done++; }
            }
        }
        if ($count_fmt > 0) {
            update_post_meta($id, '_image_opt_' . $format . '_done', '1');
            $processed++;
        } else {
            // If nothing produced, mark as skipped to prevent re-hammering a problematic file
            update_post_meta($id, '_image_opt_' . $format . '_skip', '1');
        }
        $logs[] = '✅ #' . $id . ' — ' . $format . ':' . $count_fmt . ' (sizes converted: ' . $sizes_done . ')';
    }

    // Rough remaining count
    $remaining = 0;
    foreach ($ids as $id) {
        $done = get_post_meta($id, '_image_opt_' . $format . '_done', true);
        $skip = get_post_meta($id, '_image_opt_' . $format . '_skip', true);
        if ($done !== '1' && $skip !== '1') { $remaining++; }
    }

    wp_send_json_success(array('processed' => $processed, 'remaining' => $remaining, 'logs' => $logs));
}
add_action('wp_ajax_image_opt_bulk_step', 'image_opt_ajax_bulk_step');

/**
 * Write AVIF using Imagick instance; fall back to '' on failure
 */
function image_opt_write_avif(Imagick $img, $file_path, $quality) {
    try {
        $avif_path = preg_replace('/\.[^.]+$/', '.avif', $file_path);
        $avif = clone $img;
        $avif->setImageFormat('avif');
        // Tuning options to avoid timeouts/heavy CPU where supported
        if (method_exists($avif, 'setOption')) {
            @ $avif->setOption('avif:effort', '4');
            @ $avif->setOption('heic:speed', '4');
        }
        $avif->setImageCompressionQuality($quality);
        if ($avif->writeImage($avif_path)) {
            $avif->clear();
            $avif->destroy();
            // Validate file size
            if (!file_exists($avif_path) || filesize($avif_path) === 0) {
                @unlink($avif_path);
                return '';
            }
            return $avif_path;
        }
        $avif->clear();
        $avif->destroy();
    } catch (Exception $e) {
        // ignore
    }
    return '';
}
