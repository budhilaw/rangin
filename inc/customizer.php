<?php
/**
 * Theme Customizer Settings
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add customizer settings
 */
function personal_website_customize_register($wp_customize) {
    
    // Personal Information Section removed; managed elsewhere

    // About Page General settings moved to Theme Options > About Page


    // Help Text for Portfolio
    $wp_customize->add_setting('portfolio_help', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('portfolio_help', array(
        'label'       => __('💡 How It Works', 'rangin'),
        'description' => __('<strong>Automatic Mode:</strong> Leave featured item IDs empty to show recent portfolio items.<br><strong>Manual Mode:</strong> Enter specific portfolio post IDs to feature those items.<br><br><strong>Finding Portfolio IDs:</strong> Go to Portfolio → All Portfolio Items, hover over an item title and look at the URL. The number after "post=" is the portfolio ID.', 'rangin'),
        'section'     => 'front_page_portfolio',
        'type'        => 'hidden',
    ));

    // Contact Information Section removed; managed in Theme Options > General
    

}
add_action('customize_register', 'personal_website_customize_register');

/**
 * Helper functions to get customizer values
 */
function get_personal_name() {
    return get_theme_mod('personal_name', 'Ericsson Budhilaw');
}

function get_job_title() {
    return get_theme_mod('job_title', 'Full-Stack Software Engineer');
}

function get_personal_bio() {
    return get_theme_mod('personal_bio', 'Passionate about building innovative solutions that make a difference');
}

function get_contact_email() {
    $opt = get_option('contact_email', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('contact_email', 'hello@budhilaw.com');
}

function get_contact_phone() {
    $opt = get_option('contact_phone', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('contact_phone', '+62 851-8666-2077');
}

function get_contact_location() {
    $opt = get_option('contact_location', 'Malang, Indonesia');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('contact_location', 'Jakarta, Indonesia');
}

function get_social_linkedin() {
    // Check theme options first, then customizer
    $theme_option = get_option('social_linkedin', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('social_linkedin', 'https://www.linkedin.com/in/ericsson-budhilaw');
}

function get_social_github() {
    // Check theme options first, then customizer
    $theme_option = get_option('social_github', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('social_github', 'https://github.com/budhilaw');
}

function get_social_gitlab() {
    // Theme options only for new platforms
    return get_option('social_gitlab', '');
}

function get_social_x() {
    // Theme options only for new platform name
    return get_option('social_x', '');
}

function get_social_facebook() {
    // Check theme options first, then customizer
    $theme_option = get_option('social_facebook', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('footer_social_facebook', '');
}

function get_social_instagram() {
    // Check theme options first, then customizer
    $theme_option = get_option('social_instagram', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('footer_social_instagram', '');
}

function get_social_threads() {
    // Theme options only for new platform
    return get_option('social_threads', '');
}

function get_social_twitter() {
    // Check theme options first (using 'x' for new platform name), then customizer
    $theme_option = get_option('social_x', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('social_twitter', 'https://twitter.com/ericsson_budhi');
}

function get_about_me_photo() {
    return get_option('about_me_photo', '');
}

function get_about_me_description() {
    return get_option('about_me_description', "With over 5 years of experience in software development, I specialize in creating scalable web applications and mobile solutions. I'm passionate about clean code, user experience, and staying up-to-date with the latest technologies.\n\nI enjoy tackling complex problems and turning innovative ideas into reality. When I'm not coding, you can find me exploring new technologies, contributing to open-source projects, or sharing knowledge through technical writing.");
}

function get_about_section_email() {
    $about_email = get_option('about_section_email', '');
    return !empty($about_email) ? $about_email : get_contact_email();
}

function get_years_experience() {
    return get_option('years_experience', '5');
}

function get_projects_completed() {
    return get_option('projects_completed', '50');
}

function get_hero_greeting() {
    return get_option('hero_greeting', "Hi, I'm");
}

function get_hero_description() {
    $custom_description = get_option('hero_description', '');
    if (!empty($custom_description)) {
        return $custom_description;
    }
    // Fallback to job title + bio format
    return get_job_title() . ' - ' . get_personal_bio();
}

function get_hero_background_image() {
    return get_option('hero_background_image', '');
}

function get_hero_primary_cta_text() {
    return get_option('hero_primary_cta_text', 'View My Work');
}

function get_hero_primary_cta_link() {
    return get_option('hero_primary_cta_link', '#portfolio');
}

function get_hero_secondary_cta_text() {
    return get_option('hero_secondary_cta_text', 'Hire Me');
}

function get_hero_secondary_cta_link() {
    return get_option('hero_secondary_cta_link', '#contact');
}

function get_hero_show_animated_bg() {
    return get_option('hero_show_animated_bg', true);
}

function get_contact_section_title() {
    // Check theme options first, then customizer
    $theme_option = get_option('contact_section_title', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('contact_section_title', 'Let\'s Work Together');
}

function get_contact_section_description() {
    // Check theme options first, then customizer
    $theme_option = get_option('contact_section_description', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('contact_section_description', 'Ready to start your next project? Let\'s discuss how I can help bring your ideas to life');
}

function get_front_contact_email() {
    // Check theme options first, then customizer, then general contact
    $theme_option = get_option('front_contact_email', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    $front_email = get_theme_mod('front_contact_email', '');
    return !empty($front_email) ? $front_email : get_contact_email();
}

function get_front_contact_phone() {
    // Check theme options first, then customizer, then general contact
    $theme_option = get_option('front_contact_phone', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    $front_phone = get_theme_mod('front_contact_phone', '');
    return !empty($front_phone) ? $front_phone : get_contact_phone();
}

function get_front_contact_location() {
    // Check theme options first, then customizer, then general contact
    $theme_option = get_option('front_contact_location', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    $front_location = get_theme_mod('front_contact_location', '');
    return !empty($front_location) ? $front_location : get_contact_location();
}

function get_front_social_twitter() {
    // Check theme options first (using 'x' instead of 'twitter' for new platform name)
    $theme_option = get_option('social_x', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    $front_twitter = get_theme_mod('front_social_twitter', '');
    return !empty($front_twitter) ? $front_twitter : get_social_twitter();
}

function get_front_social_linkedin() {
    // Check theme options first
    $theme_option = get_option('social_linkedin', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    $front_linkedin = get_theme_mod('front_social_linkedin', '');
    return !empty($front_linkedin) ? $front_linkedin : get_social_linkedin();
}

function get_front_social_github() {
    // Check theme options first
    $theme_option = get_option('social_github', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    $front_github = get_theme_mod('front_social_github', '');
    return !empty($front_github) ? $front_github : get_social_github();
}

function get_front_social_facebook() {
    // Check theme options first
    $theme_option = get_option('social_facebook', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('front_social_facebook', '');
}

function get_front_social_instagram() {
    // Check theme options first
    $theme_option = get_option('social_instagram', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('front_social_instagram', '');
}

function get_contact_cta_message() {
    // Check theme options first, then customizer
    $theme_option = get_option('contact_cta_message', '');
    if (!empty($theme_option)) {
        return $theme_option;
    }
    return get_theme_mod('contact_cta_message', 'I\'m currently available for freelance work and new opportunities. Whether you need a complete web application, mobile app, or just want to discuss your ideas, I\'d love to hear from you.');
}

function get_blog_section_title() {
    return get_option('blog_section_title', 'Latest Blog Posts');
}

function get_blog_section_description() {
    return get_option('blog_section_description', 'Insights and tutorials about software development and technology');
}

function get_featured_post_1() {
    return get_option('featured_post_1', '');
}

function get_featured_post_2() {
    return get_option('featured_post_2', '');
}

function get_featured_post_3() {
    return get_option('featured_post_3', '');
}

function get_featured_posts() {
    $featured_posts = array();
    
    // Get custom post IDs
    $post_1_id = get_featured_post_1();
    $post_2_id = get_featured_post_2();
    $post_3_id = get_featured_post_3();
    
    // If ANY post ID is provided, use custom selection mode
    if ($post_1_id || $post_2_id || $post_3_id) {
        // Process each post ID individually, maintaining order
        $post_ids_ordered = array($post_1_id, $post_2_id, $post_3_id);
        
        foreach ($post_ids_ordered as $post_id) {
            if ($post_id) {
                $post = get_post($post_id);
                if ($post && $post->post_status === 'publish') {
                    $featured_posts[] = array(
                        'ID' => $post->ID,
                        'post_title' => $post->post_title,
                        'post_content' => $post->post_content,
                        'post_date' => $post->post_date,
                        'post_status' => $post->post_status
                    );
                }
            }
        }
    } else {
        // Fallback to recent posts only if NO custom post IDs are provided at all
        $featured_posts = wp_get_recent_posts(array(
            'numberposts' => 3,
            'post_status' => 'publish'
        ));
    }
    
    return $featured_posts;
}

// =======================
// Footer Brand Functions
// =======================

function get_footer_brand_title() {
    $opt = get_option('footer_brand_title', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('footer_brand_title', get_bloginfo('name'));
}

function get_footer_brand_description() {
    $opt = get_option('footer_brand_description', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('footer_brand_description', 'Full-Stack Software Engineer specializing in creating innovative digital solutions. Let\'s build something amazing together.');
}

function get_footer_social_x() {
    // Prefer Theme Options (General tab), fallback to Customizer
    $opt = get_option('social_x', '');
    return !empty($opt) ? $opt : get_theme_mod('footer_social_x', '');
}

function get_footer_social_instagram() {
    $opt = get_option('social_instagram', '');
    return !empty($opt) ? $opt : get_theme_mod('footer_social_instagram', '');
}

function get_footer_social_linkedin() {
    $opt = get_option('social_linkedin', '');
    return !empty($opt) ? $opt : get_theme_mod('footer_social_linkedin', '');
}

function get_footer_social_github() {
    $opt = get_option('social_github', '');
    return !empty($opt) ? $opt : get_theme_mod('footer_social_github', '');
}

function get_footer_social_facebook() {
    $opt = get_option('social_facebook', '');
    return !empty($opt) ? $opt : get_theme_mod('footer_social_facebook', '');
}

function get_footer_social_gitlab() {
    $opt = get_option('social_gitlab', '');
    return !empty($opt) ? $opt : get_theme_mod('footer_social_gitlab', '');
}

function get_footer_social_threads() {
    $opt = get_option('social_threads', '');
    return !empty($opt) ? $opt : get_theme_mod('footer_social_threads', '');
}

// =======================
// Footer Bottom Functions
// =======================

function get_footer_copyright() {
    $opt = get_option('footer_copyright', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('footer_copyright', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
}

function get_footer_link_1_text() {
    $opt = get_option('footer_link_1_text', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('footer_link_1_text', 'Privacy Policy');
}

function get_footer_link_1_url() {
    $opt = get_option('footer_link_1_url', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('footer_link_1_url', '');
}

function get_footer_link_2_text() {
    $opt = get_option('footer_link_2_text', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('footer_link_2_text', 'Terms of Service');
}

function get_footer_link_2_url() {
    $opt = get_option('footer_link_2_url', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('footer_link_2_url', '');
}

function get_footer_made_with_text() {
    $opt = get_option('footer_made_with_text', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('footer_made_with_text', 'Made with ❤️ by Ericsson Budhilaw');
}

// Services Section Functions
function get_services_section_title() {
    return get_option('services_section_title', 'Services');
}

function get_services_section_subtitle() {
    return get_option('services_section_subtitle', 'I offer a range of software development services to help bring your ideas to life');
}

function get_services_list() {
    $services_json = get_option('services_list', '');
    if (empty($services_json)) {
        return array();
    }
    
    $services = json_decode($services_json, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($services)) {
        return array();
    }
    
    // Limit to maximum 3 services
    return array_slice($services, 0, 3);
}

function has_services() {
    $services = get_services_list();
    return !empty($services);
}

// About Page Skills Functions
function get_about_skills_show() {
    $opt = get_option('about_skills_show', null);
    if ($opt !== null && $opt !== '') {
        return wp_validate_boolean($opt);
    }
    return get_theme_mod('about_skills_show', true);
}

function get_about_skills_title() {
    $opt = get_option('about_skills_title', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('about_skills_title', 'Skills & Expertise');
}

function get_about_skills_subtitle() {
    $opt = get_option('about_skills_subtitle', '');
    if (!empty($opt)) {
        return $opt;
    }
    return get_theme_mod('about_skills_subtitle', 'Technologies and tools I work with to bring ideas to life');
}

function get_about_skills_list() {
    $skills_json = get_option('about_skills_list', '');
    if (empty($skills_json)) {
        $skills_json = get_theme_mod('about_skills_list', '');
    }
    
    if (empty($skills_json)) {
        // Return default skills if none are configured
        return array(
            array('name' => 'React', 'icon' => 'fab fa-react', 'color' => 'blue'),
            array('name' => 'Node.js', 'icon' => 'fab fa-node-js', 'color' => 'green'),
            array('name' => 'PHP', 'icon' => 'fab fa-php', 'color' => 'purple'),
            array('name' => 'JavaScript', 'icon' => 'fab fa-js-square', 'color' => 'yellow')
        );
    }
    
    $skills = json_decode($skills_json, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($skills)) {
        // Return default skills if JSON is invalid
        return array(
            array('name' => 'React', 'icon' => 'fab fa-react', 'color' => 'blue'),
            array('name' => 'Node.js', 'icon' => 'fab fa-node-js', 'color' => 'green'),
            array('name' => 'PHP', 'icon' => 'fab fa-php', 'color' => 'purple'),
            array('name' => 'JavaScript', 'icon' => 'fab fa-js-square', 'color' => 'yellow')
        );
    }
    
    // Limit to maximum 8 skills
    return array_slice($skills, 0, 8);
}

function has_about_skills() {
    return get_about_skills_show() && !empty(get_about_skills_list());
}

// About Page General Functions
function get_about_page_title() {
    // Prefer Theme Options value
    $opt = get_option('about_page_title', '');
    if (!empty($opt)) {
        return $opt;
    }
    // Backward compatibility with Customizer
    $custom_title = get_theme_mod('about_page_title', '');
    if (!empty($custom_title)) {
        return $custom_title;
    }
    // Fallback to page title if no custom title is set
    return get_the_title();
}

function get_about_page_subtitle() {
    // Prefer Theme Options value
    $opt = get_option('about_page_subtitle', '');
    if (!empty($opt)) {
        return $opt;
    }
    // Backward compatibility with Customizer
    $custom_subtitle = get_theme_mod('about_page_subtitle', '');
    if (!empty($custom_subtitle)) {
        return $custom_subtitle;
    }
    // Fallback to page excerpt if no custom subtitle is set
    return get_the_excerpt();
}

function has_about_page_subtitle() {
    $opt = get_option('about_page_subtitle', '');
    if (!empty($opt)) {
        return true;
    }
    $custom_subtitle = get_theme_mod('about_page_subtitle', '');
    return !empty($custom_subtitle) || !empty(get_the_excerpt());
}

// Portfolio Categories Functions
function get_portfolio_categories() {
    $categories_json = get_theme_mod('portfolio_categories', '');
    
    // Default categories if none are configured
    $default_categories = array(
        array('slug' => 'mobile', 'name' => 'Mobile', 'color' => 'green'),
        array('slug' => 'backend', 'name' => 'Backend', 'color' => 'blue'),
        array('slug' => 'frontend', 'name' => 'Frontend', 'color' => 'purple'),
        array('slug' => 'fullstack', 'name' => 'Full Stack', 'color' => 'orange')
    );
    
    if (empty($categories_json)) {
        return $default_categories;
    }
    
    $categories = json_decode($categories_json, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($categories)) {
        return $default_categories;
    }
    
    // Validate each category has required fields
    $valid_categories = array();
    foreach ($categories as $category) {
        if (isset($category['slug']) && isset($category['name'])) {
            $valid_categories[] = array(
                'slug' => sanitize_title($category['slug']),
                'name' => sanitize_text_field($category['name']),
                'color' => sanitize_text_field($category['color'] ?? 'blue')
            );
        }
    }
    
    return !empty($valid_categories) ? $valid_categories : $default_categories;
}

function get_portfolio_category_by_slug($slug) {
    if (empty($slug)) return null;
    // Prefer real taxonomy terms if available
    $term = get_term_by('slug', $slug, 'portfolio_category');
    if ($term && !is_wp_error($term)) {
        $colors = array('green','blue','purple','orange','red','teal','indigo','pink','gray');
        $index = abs(crc32($slug)) % count($colors);
        return array(
            'slug' => $slug,
            'name' => $term->name,
            'color' => $colors[$index],
        );
    }
    // Fallback to legacy Customizer categories (backward compatibility)
    $categories = get_portfolio_categories();
    foreach ($categories as $category) {
        if ($category['slug'] === $slug) {
            return $category;
        }
    }
    return null;
}

// Portfolio Section Functions for Front Page
function get_portfolio_section_show() {
    return get_option('portfolio_section_show', true);
}

function get_portfolio_section_title() {
    return get_option('portfolio_section_title', 'Featured Projects');
}

function get_portfolio_section_description() {
    return get_option('portfolio_section_description', "Here are some of the projects I've worked on recently");
}

function get_portfolio_posts_count() {
    return get_option('portfolio_posts_count', 6);
}

function get_featured_portfolio_1() {
    return get_option('featured_portfolio_1', '');
}

function get_featured_portfolio_2() {
    return get_option('featured_portfolio_2', '');
}

function get_featured_portfolio_3() {
    return get_option('featured_portfolio_3', '');
}

function get_front_page_portfolio_posts() {
    // Get custom portfolio IDs
    $portfolio_1_id = get_featured_portfolio_1();
    $portfolio_2_id = get_featured_portfolio_2();
    $portfolio_3_id = get_featured_portfolio_3();
    
    // Get the maximum number of posts to show
    $posts_count = get_portfolio_posts_count();
    
    $portfolio_posts = array();
    
    // If ANY portfolio ID is provided, use manual selection mode
    if ($portfolio_1_id || $portfolio_2_id || $portfolio_3_id) {
        // Process each portfolio ID individually, maintaining order
        $portfolio_ids_ordered = array($portfolio_1_id, $portfolio_2_id, $portfolio_3_id);
        
        foreach ($portfolio_ids_ordered as $portfolio_id) {
            if ($portfolio_id && count($portfolio_posts) < $posts_count) {
                $post = get_post($portfolio_id);
                if ($post && $post->post_status === 'publish' && $post->post_type === 'portfolio') {
                    $portfolio_posts[] = $post;
                }
            }
        }
        
        // If we still need more posts and haven't reached the limit, fill with recent posts
        if (count($portfolio_posts) < $posts_count) {
            $excluded_ids = wp_list_pluck($portfolio_posts, 'ID');
            $additional_posts = get_posts(array(
                'post_type' => 'portfolio',
                'posts_per_page' => $posts_count - count($portfolio_posts),
                'post_status' => 'publish',
                'exclude' => $excluded_ids,
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            $portfolio_posts = array_merge($portfolio_posts, $additional_posts);
        }
    } else {
        // Automatic mode: get recent portfolio posts
        $portfolio_posts = get_posts(array(
            'post_type' => 'portfolio',
            'posts_per_page' => $posts_count,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ));
    }
    
    return $portfolio_posts;
}

function has_portfolio_posts() {
    $posts = get_front_page_portfolio_posts();
    return !empty($posts);
}
