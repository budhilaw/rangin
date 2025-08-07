<?php
/**
 * Theme Setup and Configuration
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function personal_website_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'personal-website'),
        'footer' => esc_html__('Footer Menu', 'personal-website')
    ));
    
    // Add image sizes
    add_image_size('portfolio-thumb', 400, 300, true);
    add_image_size('blog-thumb', 350, 200, true);
    add_image_size('hero-image', 800, 600, true);
}
add_action('after_setup_theme', 'personal_website_setup');

/**
 * Custom excerpt length
 */
function personal_website_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'personal_website_excerpt_length');

/**
 * Custom excerpt more
 */
function personal_website_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'personal_website_excerpt_more');
