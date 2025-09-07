<?php
/**
 * Asset Optimization: CSS/JS minification with upload cache
 * Toggle via Theme Options → Performance
 */

if (!defined('ABSPATH')) { exit; }

function asset_opt_enabled() {
    return (bool) get_option('enable_asset_minification', false);
}

function asset_opt_cache_dir() {
    $uploads = wp_get_upload_dir();
    $dir = trailingslashit($uploads['basedir']) . 'rangin-cache/min';
    if (!is_dir($dir)) {
        wp_mkdir_p($dir);
    }
    return $dir;
}

function asset_opt_cache_url_base() {
    $uploads = wp_get_upload_dir();
    return trailingslashit($uploads['baseurl']) . 'rangin-cache/min';
}

function asset_opt_is_local_theme_asset($src) {
    $theme_url = THEME_URL;
    return strpos($src, $theme_url) === 0;
}

function asset_opt_minify_css($css) {
    // Prefer MatthiasMullie\Minify if available
    if (asset_opt_mm_available()) {
        try {
            $minifier = new \MatthiasMullie\Minify\CSS();
            $minifier->add((string)$css);
            $min = $minifier->minify();
            if (is_string($min) && $min !== '') { return $min; }
        } catch (\Throwable $e) { /* fallback below */ }
    }
    // Basic CSS minifier: remove comments (except /*! ... */), collapse whitespace, trim around tokens
    $css = (string) $css;
    // Remove /* ... */ comments but preserve /*! ... */
    $css = preg_replace('/\/\*(?!\!)[\s\S]*?\*\//', '', $css) ?? $css;
    // Collapse whitespace
    $css = preg_replace('/\s+/', ' ', $css) ?? $css;
    // Remove spaces around tokens
    $css = preg_replace('/\s*([{};:,>])\s*/', '$1', $css) ?? $css;
    $css = str_replace(';}', '}', $css);
    return trim($css);
}

function asset_opt_minify_js($js) {
    // Prefer MatthiasMullie\Minify if available
    if (asset_opt_mm_available()) {
        try {
            $minifier = new \MatthiasMullie\Minify\JS();
            $minifier->add((string)$js);
            $min = $minifier->minify();
            if (is_string($min) && $min !== '') { return $min; }
        } catch (\Throwable $e) { /* fallback below */ }
    }
    // Safe JS packer: keep line structure, trim trailing spaces, coalesce blank lines.
    // We DO NOT strip comments or merge lines to avoid breaking EOL comment semantics.
    $js = (string) $js;
    $lines = preg_split("/\r?\n/", $js);
    $out = [];
    $blank = 0;
    foreach ($lines as $line) {
        $line = rtrim($line, " \t\r\n");
        if ($line === '') { $blank++; } else { $blank = 0; }
        if ($blank > 1) { continue; }
        $out[] = $line;
    }
    $min = implode("\n", $out);
    return $min;
}

function asset_opt_should_minify_js() {
    return (bool) get_option('enable_asset_minify_js', false);
}

function asset_opt_js_balanced($code) {
    // Quick balance check for braces/parens/brackets; if unbalanced, skip using minified
    $pairs = [ ['{','}'], ['(',')'], ['[',']'] ];
    foreach ($pairs as $p) {
        if (substr_count($code, $p[0]) !== substr_count($code, $p[1])) return false;
    }
    return true;
}

// Attempt to bootstrap Composer autoloaders if present
function asset_opt_bootstrap_composer() {
    static $boot = false;
    if ($boot) return; $boot = true;
    $candidates = array(
        ABSPATH . 'vendor/autoload.php',
        THEME_DIR . '/vendor/autoload.php',
        dirname(THEME_DIR) . '/vendor/autoload.php',
    );
    foreach ($candidates as $autoload) {
        if (file_exists($autoload)) { require_once $autoload; break; }
    }
}

function asset_opt_mm_available() {
    asset_opt_bootstrap_composer();
    return class_exists('MatthiasMullie\\Minify\\CSS') && class_exists('MatthiasMullie\\Minify\\JS');
}

function asset_opt_minify_and_cache($src, $type) {
    if (!asset_opt_enabled() || !asset_opt_is_local_theme_asset($src)) {
        return $src;
    }
    // Skip already minified
    if (strpos($src, '.min.') !== false) { return $src; }
    // JS: Prefer prebuilt .min.js if enabled, otherwise auto-generate a safe .min.js in cache
    if ($type === 'js') {
        if (!asset_opt_should_minify_js()) { return $src; }
        $no_query = strtok($src, '?');
        $min_candidate_url = preg_replace('/\.js$/', '.min.js', $no_query);
        $min_candidate_path = str_replace(THEME_URL, THEME_DIR, $min_candidate_url);
        if ($min_candidate_url && file_exists($min_candidate_path)) {
            // Append original query string if any
            $query = parse_url($src, PHP_URL_QUERY);
            return $min_candidate_url . ($query ? ('?' . $query) : '');
        }
        // No prebuilt min file — generate cached .min.js using a safe packer
        $path = str_replace(THEME_URL, THEME_DIR, $no_query);
        if (!file_exists($path)) { return $src; }
        $hash = md5('js|' . $no_query . '|' . @filemtime($path) . '|' . @filesize($path));
        $out_path = trailingslashit(asset_opt_cache_dir()) . $hash . '.min.js';
        $out_url  = trailingslashit(asset_opt_cache_url_base()) . $hash . '.min.js';
        if (!file_exists($out_path)) {
            $raw = file_get_contents($path);
            if ($raw === false) { return $src; }
            $min = asset_opt_minify_js($raw);
            if (!empty($min) && asset_opt_js_balanced($min)) {
                if (substr($min, -1) !== "\n") { $min .= "\n"; }
                @file_put_contents($out_path, $min, LOCK_EX);
            } else {
                // fallback write original to ensure consistent URL if needed
                @file_put_contents($out_path, $raw, LOCK_EX);
            }
        }
        if (file_exists($out_path)) {
            $query = parse_url($src, PHP_URL_QUERY);
            return $out_url . ($query ? ('?' . $query) : '');
        }
        return $src;
    }

    // Map URL to path (strip query)
    $no_query = strtok($src, '?');
    $path = str_replace(THEME_URL, THEME_DIR, $no_query);
    if (!file_exists($path)) { return $src; }

    $hash = md5($no_query . '|' . @filemtime($path) . '|' . @filesize($path));
    $ext = $type === 'css' ? '.css' : '.js';
    $out_path = trailingslashit(asset_opt_cache_dir()) . $hash . $ext;
    $out_url  = trailingslashit(asset_opt_cache_url_base()) . $hash . $ext;

    if (!file_exists($out_path)) {
        $raw = file_get_contents($path);
        if ($raw === false) { return $src; }
        $min = ($type === 'css') ? asset_opt_minify_css($raw) : asset_opt_minify_js($raw);
        // Avoid writing empty files
        if (!empty($min)) {
            if ($type === 'js' && !asset_opt_js_balanced($min)) {
                // Fallback to original if balance check fails
                $min = $raw;
            }
            // Append newline to avoid accidental EOF token issues
            if (substr($min, -1) !== "\n") { $min .= "\n"; }
            @file_put_contents($out_path, $min, LOCK_EX);
        }
    }

    if (file_exists($out_path)) {
        return $out_url;
    }
    return $src;
}

function asset_opt_filter_style_src($src) {
    return asset_opt_minify_and_cache($src, 'css');
}

function asset_opt_filter_script_src($src) {
    return asset_opt_minify_and_cache($src, 'js');
}

add_filter('style_loader_src', 'asset_opt_filter_style_src', 20);
add_filter('script_loader_src', 'asset_opt_filter_script_src', 20);
