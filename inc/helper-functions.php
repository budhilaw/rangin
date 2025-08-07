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
 * Custom search form
 */
function personal_website_search_form() {
    $form = '<form role="search" method="get" action="' . home_url('/') . '" class="relative">
        <input type="search" 
               placeholder="Search posts..." 
               value="' . get_search_query() . '" 
               name="s" 
               class="w-full px-4 py-3 pr-12 bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-transparent text-neutral-900 dark:text-neutral-100">
        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-neutral-500 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-primary-400">
            <i class="fas fa-search"></i>
        </button>
    </form>';
    
    return $form;
}

// Override WordPress default search form
add_filter('get_search_form', 'personal_website_search_form');

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
