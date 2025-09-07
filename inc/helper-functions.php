<?php
/**
 * Helper Functions
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Calculate reading time for a post
 */
function reading_time($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed is 200 words per minute
    
    return max($reading_time, 1); // Minimum 1 minute
}

/**
 * Custom excerpt length
 */
function personal_website_custom_excerpt_length($length) {
    if (is_home() || is_archive()) {
        return 30;
    }
    return $length;
}
add_filter('excerpt_length', 'personal_website_custom_excerpt_length');

/**
 * Custom excerpt more
 */
function personal_website_custom_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'personal_website_custom_excerpt_more');

/**
 * Add custom classes to pagination
 */
function personal_website_pagination_classes($template, $class) {
    $template = str_replace('<ul class=\'page-numbers\'>', '<ul class=\'flex items-center space-x-2 justify-center\'>', $template);
    $template = str_replace('<li><span', '<li><span class="px-4 py-2 bg-primary-600 text-white rounded-lg"', $template);
    $template = str_replace('<li><a', '<li><a class="px-4 py-2 bg-neutral-100 dark:bg-neutral-800 hover:bg-primary-600 hover:text-white rounded-lg transition-colors"', $template);
    
    return $template;
}
add_filter('navigation_markup_template', 'personal_website_pagination_classes', 10, 2);

/**
 * Optimize images for better performance
 */
function personal_website_add_image_sizes() {
    add_image_size('blog-list', 600, 400, true);
    add_image_size('sidebar-thumb', 80, 80, true);
}
add_action('after_setup_theme', 'personal_website_add_image_sizes');

/**
 * Add custom body classes
 */
function personal_website_body_classes($classes) {
    // Add page-specific classes
    if (is_home() || is_archive()) {
        $classes[] = 'blog-page';
    }
    
    if (is_single()) {
        $classes[] = 'single-post';
    }
    
    // Add theme classes
    $classes[] = 'personal-website-theme';
    
    return $classes;
}
add_filter('body_class', 'personal_website_body_classes');

/**
 * Social Media Helper Functions
 */

/**
 * Get social media link with fallback to customizer
 */
function get_social_media_link($platform) {
    $option_key = 'social_' . $platform;
    $customizer_key = 'footer_social_' . $platform;
    
    // Check theme options first
    $link = get_option($option_key, '');
    
    // Fallback to customizer if not set in theme options
    if (empty($link)) {
        $link = get_theme_mod($customizer_key, '');
    }
    
    return $link;
}

/**
 * Get all social media links
 */
function get_all_social_media_links() {
    $platforms = array('github', 'gitlab', 'linkedin', 'x', 'facebook', 'instagram', 'threads');
    $links = array();
    
    foreach ($platforms as $platform) {
        $link = get_social_media_link($platform);
        if (!empty($link)) {
            $links[$platform] = $link;
        }
    }
    
    return $links;
}

/**
 * Display social media icons
 */
function display_social_media_icons($classes = '') {
    $social_links = get_all_social_media_links();
    
    if (empty($social_links)) {
        return;
    }
    
    $icon_map = array(
        'github' => 'fab fa-github',
        'gitlab' => 'fab fa-gitlab',
        'linkedin' => 'fab fa-linkedin',
        'x' => 'fab fa-x-twitter',
        'facebook' => 'fab fa-facebook',
        'instagram' => 'fab fa-instagram',
        'threads' => 'fab fa-threads'
    );
    
    $label_map = array(
        'github' => 'GitHub',
        'gitlab' => 'GitLab',
        'linkedin' => 'LinkedIn',
        'x' => 'X (Twitter)',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'threads' => 'Threads'
    );
    
    echo '<div class="social-media-links ' . esc_attr($classes) . '">';
    foreach ($social_links as $platform => $url) {
        $icon = isset($icon_map[$platform]) ? $icon_map[$platform] : 'fas fa-link';
        $label = isset($label_map[$platform]) ? $label_map[$platform] : ucfirst($platform);
        
        echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" class="social-link social-' . esc_attr($platform) . '" aria-label="' . esc_attr($label) . '">';
        echo '<i class="' . esc_attr($icon) . '" aria-hidden="true"></i>';
        echo '</a>';
    }
    echo '</div>';
}

/**
 * Contact Information Helper Functions
 */

/**
 * Get front page contact information with fallback to customizer
 */
function get_front_contact_info($field) {
    $theme_option_key = 'front_contact_' . $field;
    $customizer_key = 'contact_' . $field; // backwards-compat only
    
    // Check theme options first
    $value = get_option($theme_option_key, '');
    
    // Fallback to customizer if still empty (backward compatibility)
    if (empty($value)) {
        $value = get_theme_mod($customizer_key, '');
    }
    
    return $value;
}
