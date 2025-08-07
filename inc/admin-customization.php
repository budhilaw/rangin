<?php
/**
 * Admin Area Customization
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customize login page
 */
function personal_website_login_stylesheet() {
    wp_enqueue_style('custom-login', THEME_URL . '/assets/css/login.css', array(), THEME_VERSION);
}
add_action('login_enqueue_scripts', 'personal_website_login_stylesheet');

/**
 * Change login logo URL
 */
function personal_website_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'personal_website_login_logo_url');

/**
 * Change login logo title
 */
function personal_website_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'personal_website_login_logo_url_title');

/**
 * Hide admin bar for all users on frontend
 */
function personal_website_hide_admin_bar() {
    // Hide admin bar on frontend for all users (including administrators)
    if (!is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'personal_website_hide_admin_bar');

/**
 * Remove admin bar completely from frontend
 */
function personal_website_remove_admin_bar_completely() {
    // Remove the admin bar from the frontend entirely
    add_filter('show_admin_bar', '__return_false');
}
add_action('init', 'personal_website_remove_admin_bar_completely');

/**
 * Add custom admin styles
 */
function personal_website_admin_styles() {
    echo '<style>
        .wp-admin .update-nag,
        .wp-admin .notice,
        .wp-admin .error {
            display: none !important;
        }
        
        #adminmenu .wp-menu-image img {
            opacity: 0.6;
        }
        
        #adminmenu .current .wp-menu-image img,
        #adminmenu .wp-has-current-submenu .wp-menu-image img,
        #adminmenu a:hover .wp-menu-image img {
            opacity: 1;
        }
    </style>';
}
add_action('admin_head', 'personal_website_admin_styles');

/**
 * Remove admin bar styles and scripts from frontend
 */
function personal_website_remove_admin_bar_assets() {
    // Remove admin bar CSS from frontend
    wp_dequeue_style('admin-bar');
    // Remove admin bar JavaScript from frontend
    wp_dequeue_script('admin-bar');
}
add_action('wp_enqueue_scripts', 'personal_website_remove_admin_bar_assets', 999);

/**
 * Remove admin bar margin from body
 */
function personal_website_remove_admin_bar_margin() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'personal_website_remove_admin_bar_margin');


