<?php
/**
 * Custom block pattern helpers.
 *
 * @package PersonalWebsite
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom block pattern categories.
 */
function rangin_register_block_pattern_categories() {
    if (!function_exists('register_block_pattern_category')) {
        return;
    }

    register_block_pattern_category(
        'rangin',
        array(
            'label' => esc_html__('Rangin Theme', 'rangin'),
        )
    );
}
add_action('init', 'rangin_register_block_pattern_categories');

/**
 * Register custom block patterns.
 */
function rangin_register_block_patterns() {
    if (!function_exists('register_block_pattern')) {
        return;
    }

    $pattern_file = THEME_DIR . '/patterns/about-section.php';

    if (file_exists($pattern_file)) {
        ob_start();
        include $pattern_file;
        $pattern_content = ob_get_clean();

        if (!empty($pattern_content)) {
            register_block_pattern(
                'rangin/about-section',
                array(
                    'title'       => esc_html__('About Section', 'rangin'),
                    'categories'  => array('rangin', 'text', 'featured'),
                    'description' => esc_html__('Hero-inspired About layout with portrait, stats, feature list, and CTAs.', 'rangin'),
                    'content'     => $pattern_content,
                )
            );
        }
    }
}
add_action('init', 'rangin_register_block_patterns');
