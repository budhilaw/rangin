<?php
/**
 * Enqueue Scripts and Styles
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles
 */
function personal_website_scripts() {
    // Font Awesome: load only style sets actually needed (solid + brands).
    // We avoid loading the 14KB base CSS and inline a tiny core in fa-font-display.css.
    wp_enqueue_style('fa-solid', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/solid.min.css', array(), '6.5.1');
    wp_enqueue_style('fa-brands', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/brands.min.css', array(), '6.5.1');
    
    // Main stylesheet (compiled TailwindCSS)
    $style_path_min = THEME_DIR . '/assets/css/style.min.css';
    $style_url_min  = THEME_URL . '/assets/css/style.min.css';
    $style_url      = THEME_URL . '/assets/css/style.css';
    $main_css_url   = file_exists($style_path_min) ? $style_url_min : $style_url;
    wp_enqueue_style('personal-website-style', $main_css_url, array('fa-solid','fa-brands'), THEME_VERSION);
    
    // Inline tiny CSS to avoid extra render-blocking requests
    $fa_fd_path = THEME_DIR . '/assets/css/fa-font-display.css';
    $fa_core_path = THEME_DIR . '/assets/css/fa-core-lite.css';
    $ua_head_path = THEME_DIR . '/assets/css/ua-headings.css';
    $inline_css = '';
    if (file_exists($fa_fd_path)) {
        $inline_css .= file_get_contents($fa_fd_path) . "\n";
    }
    if (file_exists($ua_head_path)) {
        $inline_css .= file_get_contents($ua_head_path) . "\n";
    }
    if (file_exists($fa_core_path)) {
        $inline_css .= file_get_contents($fa_core_path) . "\n";
    }
    if ($inline_css !== '') {
        wp_add_inline_style('personal-website-style', $inline_css);
    }
    
    // Custom JavaScript (use minified version if available)
    $main_js_path_min = THEME_DIR . '/assets/js/main.min.js';
    $main_js_url_min  = THEME_URL . '/assets/js/main.min.js';
    $main_js_url      = THEME_URL . '/assets/js/main.js';
    $main_js_url_final = file_exists($main_js_path_min) ? $main_js_url_min : $main_js_url;
    
    // Add file modification time for cache busting to ensure updated JS is loaded
    $js_version = THEME_VERSION;
    if (file_exists($main_js_path_min)) {
        $js_version = THEME_VERSION . '.' . filemtime($main_js_path_min);
    } elseif (file_exists(THEME_DIR . '/assets/js/main.js')) {
        $js_version = THEME_VERSION . '.' . filemtime(THEME_DIR . '/assets/js/main.js');
    }
    
    wp_enqueue_script('personal-website-main', $main_js_url_final, array('jquery'), $js_version, true);
    
    // Localize script for AJAX
    wp_localize_script('personal-website-main', 'personalWebsite', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('personal_website_nonce'),
        'homeUrl' => home_url()
    ));
    
    // Load comment reply script if needed with defer to avoid blocking
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
        // Ensure comment-reply script is deferred to avoid blocking
        add_filter('script_loader_tag', function($tag, $handle) {
            if ($handle === 'comment-reply') {
                if (strpos($tag, 'defer') === false) {
                    $tag = str_replace('<script ', '<script defer ', $tag);
                }
            }
            return $tag;
        }, 10, 2);
    }
}
add_action('wp_enqueue_scripts', 'personal_website_scripts');

/**
 * Add async/defer attributes to scripts for better performance
 */
function personal_website_script_attributes($tag, $handle, $src) {
    // Defer main.js (keeps dependency order with jQuery)
    if ($handle === 'personal-website-main') {
        if (strpos($tag, 'defer') === false) {
            $tag = str_replace('<script ', '<script defer ', $tag);
        }
        return $tag;
    }
    // Defer jQuery core and migrate to avoid render blocking
    if ($handle === 'jquery-core' || $handle === 'jquery-migrate') {
        // Ensure we don't add both async+defer. Use defer for safe ordering.
        if (strpos($tag, 'defer') === false) {
            $tag = str_replace('<script ', '<script defer ', $tag);
        }
        return $tag;
    }
    // Also defer WordPress comment-reply script to prevent reflows
    if ($handle === 'comment-reply') {
        if (strpos($tag, 'defer') === false) {
            $tag = str_replace('<script ', '<script defer ', $tag);
        }
        return $tag;
    }
    return $tag;
}
add_filter('script_loader_tag', 'personal_website_script_attributes', 10, 3);

/**
 * Move jQuery to footer on the front-end to avoid blocking render in <head>.
 */
function personal_website_move_jquery_to_footer($scripts) {
    if (is_admin()) { return; }
    $scripts->add_data('jquery', 'group', 1);
    $scripts->add_data('jquery-core', 'group', 1);
    $scripts->add_data('jquery-migrate', 'group', 1);
}
add_action('wp_default_scripts', 'personal_website_move_jquery_to_footer');

/**
 * Drop jquery-migrate on the frontend to reduce unused JS payload (~7KB gzipped).
 * The theme does not rely on deprecated APIs provided by migrate.
 */
function personal_website_remove_jquery_migrate($scripts) {
    if (is_admin()) { return; }
    if (!empty($scripts->registered['jquery']) && !empty($scripts->registered['jquery-core'])) {
        // Re-register jquery to depend only on jquery-core
        $scripts->remove('jquery');
        $scripts->add('jquery', false, array('jquery-core'), '3.7.1');
    }
    // Ensure migrate does not get printed by other dependencies
    $scripts->remove('jquery-migrate');
}
add_action('wp_default_scripts', 'personal_website_remove_jquery_migrate', 11);

/**
 * Convert selected stylesheets to preload + onload to reduce render blocking.
 * Adds a <noscript> fallback in personal_website_preload_resources.
 */
function personal_website_style_attributes($html, $handle, $href) {
    $handles = array(
        'personal-website-style',
        'fa-solid','fa-brands','font-awesome',
        'wp-block-library',
    );
    if (in_array($handle, $handles, true)) {
        // Safer non-blocking pattern compatible across browsers
        // rel=stylesheet, but load with media=print and switch to all onload
        $html = str_replace("rel='stylesheet'", "rel='stylesheet' media='print' onload=\"this.media='all'\"", $html);
    }
    return $html;
}
add_filter('style_loader_tag', 'personal_website_style_attributes', 10, 3);

/**
 * Add preload for critical resources
 */
function personal_website_preload_resources() {
    // Noscript fallback for main stylesheet (in case preload isn't supported)
    $style_path_min = THEME_DIR . '/assets/css/style.min.css';
    $style_url_min  = THEME_URL . '/assets/css/style.min.css';
    $style_url      = THEME_URL . '/assets/css/style.css';
    $main_css_url   = file_exists($style_path_min) ? $style_url_min : $style_url;
    echo '<noscript><link rel="stylesheet" href="' . esc_url($main_css_url) . '"></noscript>' . "\n";
    
    // Preconnect to external origins used early (fonts & Turnstile)
    echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://challenges.cloudflare.com" crossorigin>' . "\n";
    // Preload Font Awesome fonts to break CSS→font chain (match version)
    echo '<link rel="preload" as="font" type="font/woff2" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/webfonts/fa-solid-900.woff2" crossorigin>' . "\n";
    echo '<link rel="preload" as="font" type="font/woff2" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/webfonts/fa-brands-400.woff2" crossorigin>' . "\n";
    
    // Preload fonts (uncomment if using custom fonts)
    // echo '<link rel="preload" href="' . THEME_URL . '/assets/fonts/font.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action('wp_head', 'personal_website_preload_resources', 1);

// Add resource hints for DNS-prefetch & preconnect (WordPress-managed)
function personal_website_resource_hints($hints, $relation_type) {
    $origins = array(
        'https://cdnjs.cloudflare.com',
        'https://challenges.cloudflare.com',
    );
    if ($relation_type === 'preconnect' || $relation_type === 'dns-prefetch') {
        foreach ($origins as $o) {
            if (!in_array($o, $hints, true)) {
                $hints[] = $o;
            }
        }
    }
    return $hints;
}
add_filter('wp_resource_hints', 'personal_website_resource_hints', 10, 2);

/**
 * Trim Gutenberg block styles on the frontend to reduce unused CSS.
 * If a specific page relies on block styles, you can comment this out
 * or add conditional checks around it.
 */
function personal_website_trim_block_styles() {
    if (is_admin()) { return; }
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'personal_website_trim_block_styles', 100);
