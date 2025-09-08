<?php
/**
 * Performance Optimization Functions
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove unnecessary WordPress features for better performance
 */
function personal_website_clean_head() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
}
add_action('init', 'personal_website_clean_head');

/**
 * Disable embeds for better performance
 */
function personal_website_disable_embeds() {
    remove_action('rest_api_init', 'wp_oembed_register_route');
    add_filter('embed_oembed_discover', '__return_false');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
}
add_action('init', 'personal_website_disable_embeds', 9999);

/**
 * Add lazy loading to images for better performance
 */
function personal_website_add_lazy_loading($content) {
    if (is_admin()) {
        return $content;
    }
    
    // Add loading="lazy" to images that don't already have it
    $content = preg_replace('/<img((?![^>]*loading=["\'][^"\']*["\'])[^>]*)>/i', '<img$1 loading="lazy">', $content);
    
    return $content;
}
add_filter('the_content', 'personal_website_add_lazy_loading');

/**
 * Tweak default WP image attributes to better defer offscreen images.
 * - Force loading="lazy" and decoding="async" for small/list thumbnails.
 * - Remove browser-assigned fetchpriority for non-LCP sizes to avoid eagerly
 *   loading offscreen images.
 */
function personal_website_image_attrs($attr, $attachment, $size) {
    // Sizes considered non-critical (lists, cards, sidebars)
    $non_critical_sizes = array('thumbnail', 'blog-thumb', 'portfolio-thumb', 'sidebar-thumb', 'blog-list');
    $is_non_critical = is_string($size) && in_array($size, $non_critical_sizes, true);

    if ($is_non_critical || is_home() || is_archive() || is_front_page()) {
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
        // Let the browser decide priority; do not force high for list items
        if (isset($attr['fetchpriority'])) {
            unset($attr['fetchpriority']);
        }
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'personal_website_image_attrs', 20, 3);

/**
 * Optimize WordPress queries
 */
function personal_website_optimize_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_home()) {
            $query->set('posts_per_page', 6);
        }
    }
}
add_action('pre_get_posts', 'personal_website_optimize_queries');

/**
 * Add cache busting for static assets
 */
function personal_website_cache_busting($src) {
    // Only handle theme assets
    if (strpos($src, THEME_URL) === false) {
        return $src;
    }

    // Strip query string before mapping to filesystem
    $no_query = strtok($src, '?');
    $path = str_replace(THEME_URL, THEME_DIR, $no_query);

    // Guard against missing files and warnings
    if (file_exists($path)) {
        $version = @filemtime($path);
        if (!empty($version)) {
            // Ensure we don't duplicate the param
            $src = remove_query_arg('v', $src);
            return add_query_arg('v', $version, $src);
        }
    }
    // Fallback: leave original URL untouched
    return $src;
}
add_filter('style_loader_src', 'personal_website_cache_busting');
add_filter('script_loader_src', 'personal_website_cache_busting');
