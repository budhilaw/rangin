<?php
/**
 * SEO Optimization Functions
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom meta tags for SEO
 */
function personal_website_meta_tags() {
    // Basic meta tags (viewport is already set in header.php)
    echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">' . "\n";

    // Build a robust meta description
    $description = '';
    if (is_singular()) {
        $qid = get_queried_object_id();
        $excerpt = $qid ? get_post_field('post_excerpt', $qid, 'raw') : '';
        if (!empty($excerpt)) {
            $description = $excerpt;
        } else {
            $content = $qid ? get_post_field('post_content', $qid, 'raw') : '';
            $description = wp_strip_all_tags($content);
        }
    } else {
        $description = get_bloginfo('description');
    }
    if (empty($description)) {
        $description = get_bloginfo('name');
    }
    $description = wp_html_excerpt($description, 160, '…');

    // Standard meta description
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";

    // Open Graph + Twitter
    if (is_singular()) {
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        if (has_post_thumbnail()) {
            $thumbnail_url = get_the_post_thumbnail_url(get_queried_object_id(), 'large');
            if ($thumbnail_url) {
                echo '<meta property="og:image" content="' . esc_url($thumbnail_url) . '">' . "\n";
            }
        }
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
        if (!empty($thumbnail_url)) {
            echo '<meta name="twitter:image" content="' . esc_url($thumbnail_url) . '">' . "\n";
        }
    } else {
        $site_name = get_bloginfo('name');
        echo '<meta property="og:title" content="' . esc_attr($site_name) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url()) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
    }
}
add_action('wp_head', 'personal_website_meta_tags', 5);

/**
 * Add structured data for SEO
 */
function personal_website_structured_data() {
    if (is_home() || is_front_page()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => 'Ericsson Budhilaw',
            'jobTitle' => 'Software Engineer',
            'url' => home_url(),
            'sameAs' => array(
                'https://www.linkedin.com/in/ericsson-budhilaw',
                'https://github.com/budhilaw',
                'https://twitter.com/ericsson_budhi'
            )
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
    
    if (is_single() && get_post_type() === 'post') {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => 'Ericsson Budhilaw'
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url()
            )
        );
        
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(get_the_ID(), 'large');
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
}
add_action('wp_head', 'personal_website_structured_data');
