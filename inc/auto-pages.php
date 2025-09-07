<?php
/**
 * Auto-create core pages on theme activation
 *
 * Creates: Home, Blog, Portfolio, About, Contact
 * Sets Home as static front page and Blog as posts page when not already set.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ensure a page exists by slug/title; create if missing.
 *
 * @param string $title Page title.
 * @param string $slug  Page slug.
 * @param string $content Optional content.
 * @return int Page ID.
 */
function pw_ensure_page($title, $slug, $content = '') {
    // Look up strictly by page post type
    $page = get_page_by_path($slug, OBJECT, 'page');
    if (!$page) {
        $page = get_page_by_title($title);
    }

    if ($page && isset($page->ID)) {
        return (int) $page->ID;
    }

    $page_id = wp_insert_post([
        'post_title'   => $title,
        'post_name'    => sanitize_title($slug),
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
    ]);

    return (int) $page_id;
}

/**
 * Create core pages and set front/blog pages if not already configured.
 */
function pw_create_core_pages_on_activation() {
    // Create or get pages
    $home_id      = pw_ensure_page(__('Home', 'rangin'), 'home');
    $blog_id      = pw_ensure_page(__('Blog', 'rangin'), 'blog');
    $portfolio_id = pw_ensure_page(__('Portfolio', 'rangin'), 'portfolio');
    $about_id     = pw_ensure_page(__('About', 'rangin'), 'about');
    $contact_id   = pw_ensure_page(__('Contact', 'rangin'), 'contact');

    // Respect existing settings; only set if not configured
    $show_on_front = get_option('show_on_front');
    $current_front = (int) get_option('page_on_front');
    $current_posts = (int) get_option('page_for_posts');

    if ($show_on_front !== 'page' || empty($current_front)) {
        if ($home_id > 0) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home_id);
        }
    }

    if (empty($current_posts) && $blog_id > 0) {
        update_option('page_for_posts', $blog_id);
    }

    // No need to assign templates explicitly:
    // - front page uses front-page.php when set above
    // - pages with slugs 'portfolio' and 'about' will resolve to page-portfolio.php/page-about.php
}

// Run on theme activation
add_action('after_switch_theme', 'pw_create_core_pages_on_activation');
