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
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');
    
    // Main stylesheet (will contain compiled TailwindCSS)
    wp_enqueue_style('personal-website-style', get_stylesheet_uri(), array('font-awesome'), THEME_VERSION);
    
    // Custom JavaScript
    wp_enqueue_script('personal-website-main', THEME_URL . '/assets/js/main.js', array('jquery'), THEME_VERSION, true);
    
    // Localize script for AJAX
    wp_localize_script('personal-website-main', 'personalWebsite', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('personal_website_nonce'),
        'homeUrl' => home_url()
    ));
    
    // Load comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'personal_website_scripts');

/**
 * Add async/defer attributes to scripts for better performance
 */
function personal_website_script_attributes($tag, $handle, $src) {
    // Add async to main.js for better performance
    if ($handle === 'personal-website-main') {
        return str_replace('<script ', '<script async ', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'personal_website_script_attributes', 10, 3);

/**
 * Add preload for critical resources
 */
function personal_website_preload_resources() {
    // Preload main stylesheet
    echo '<link rel="preload" href="' . get_stylesheet_uri() . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    
    // Preload main JavaScript
    echo '<link rel="preload" href="' . THEME_URL . '/assets/js/main.js" as="script">' . "\n";
    
    // Preload Font Awesome
    echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>' . "\n";
    
    // Preload fonts (uncomment if using custom fonts)
    // echo '<link rel="preload" href="' . THEME_URL . '/assets/fonts/font.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action('wp_head', 'personal_website_preload_resources', 1);
