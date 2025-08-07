<?php
/**
 * Widget Areas and Custom Widgets
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register widget areas
 */
function personal_website_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Blog Sidebar', 'personal-website'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in blog sidebar.', 'personal-website'),
        'before_widget' => '<section id="%1$s" class="widget mb-8 p-6 bg-white rounded-lg shadow-sm %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title text-xl font-semibold mb-4 text-gray-800">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Area', 'personal-website'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in footer.', 'personal-website'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title text-lg font-semibold mb-4 text-white">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'personal_website_widgets_init');
