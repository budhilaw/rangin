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
    
    // Personal Information Section
    $wp_customize->add_section('personal_info', array(
        'title'    => __('Personal Information', 'personal-website'),
        'priority' => 30,
    ));
    
    // Name
    $wp_customize->add_setting('personal_name', array(
        'default'           => 'Ericsson Budhilaw',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('personal_name', array(
        'label'   => __('Full Name', 'personal-website'),
        'section' => 'personal_info',
        'type'    => 'text',
    ));
    
    // Job Title
    $wp_customize->add_setting('job_title', array(
        'default'           => 'Full-Stack Software Engineer',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('job_title', array(
        'label'   => __('Job Title', 'personal-website'),
        'section' => 'personal_info',
        'type'    => 'text',
    ));
    
    // Bio/Description
    $wp_customize->add_setting('personal_bio', array(
        'default'           => 'Passionate about building innovative solutions that make a difference',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('personal_bio', array(
        'label'   => __('Bio/Description', 'personal-website'),
        'section' => 'personal_info',
        'type'    => 'textarea',
    ));
    
    // Front Page Hero Section
    $wp_customize->add_section('front_page_hero', array(
        'title'    => __('Front Page - Hero', 'personal-website'),
        'priority' => 31,
    ));
    
    // Hero Greeting Text
    $wp_customize->add_setting('hero_greeting', array(
        'default'           => 'Hi, I\'m',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_greeting', array(
        'label'       => __('Hero Greeting Text', 'personal-website'),
        'description' => __('The greeting text before your name (e.g., "Hello, I\'m", "Welcome, I\'m")', 'personal-website'),
        'section'     => 'front_page_hero',
        'type'        => 'text',
    ));
    
    // Hero Description Override
    $wp_customize->add_setting('hero_description', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('hero_description', array(
        'label'       => __('Hero Description', 'personal-website'),
        'description' => __('Custom description for hero section. Leave empty to use "Job Title - Bio" format.', 'personal-website'),
        'section'     => 'front_page_hero',
        'type'        => 'textarea',
    ));
    
    // Hero Background Image
    $wp_customize->add_setting('hero_background_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_background_image', array(
        'label'       => __('Hero Background Image', 'personal-website'),
        'description' => __('Optional background image for hero section (will overlay with gradient)', 'personal-website'),
        'section'     => 'front_page_hero',
    )));
    
    // Primary CTA Button Text
    $wp_customize->add_setting('hero_primary_cta_text', array(
        'default'           => 'View My Work',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_primary_cta_text', array(
        'label'       => __('Primary Button Text', 'personal-website'),
        'description' => __('Text for the main call-to-action button', 'personal-website'),
        'section'     => 'front_page_hero',
        'type'        => 'text',
    ));
    
    // Primary CTA Button Link
    $wp_customize->add_setting('hero_primary_cta_link', array(
        'default'           => '#portfolio',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('hero_primary_cta_link', array(
        'label'       => __('Primary Button Link', 'personal-website'),
        'description' => __('URL or anchor link for primary button (e.g., #portfolio, /contact, external URL)', 'personal-website'),
        'section'     => 'front_page_hero',
        'type'        => 'url',
    ));
    
    // Secondary CTA Button Text
    $wp_customize->add_setting('hero_secondary_cta_text', array(
        'default'           => 'Hire Me',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_secondary_cta_text', array(
        'label'       => __('Secondary Button Text', 'personal-website'),
        'description' => __('Text for the secondary call-to-action button', 'personal-website'),
        'section'     => 'front_page_hero',
        'type'        => 'text',
    ));
    
    // Secondary CTA Button Link
    $wp_customize->add_setting('hero_secondary_cta_link', array(
        'default'           => '#contact',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('hero_secondary_cta_link', array(
        'label'       => __('Secondary Button Link', 'personal-website'),
        'description' => __('URL or anchor link for secondary button (e.g., #contact, mailto:, external URL)', 'personal-website'),
        'section'     => 'front_page_hero',
        'type'        => 'url',
    ));
    
    // Show/Hide Animated Background
    $wp_customize->add_setting('hero_show_animated_bg', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('hero_show_animated_bg', array(
        'label'       => __('Show Animated Background Shapes', 'personal-website'),
        'description' => __('Display floating geometric shapes in the background', 'personal-website'),
        'section'     => 'front_page_hero',
        'type'        => 'checkbox',
    ));

    // Front Page About Me Section
    $wp_customize->add_section('front_page_about', array(
        'title'    => __('Front Page - About Me', 'personal-website'),
        'priority' => 32,
    ));
    
    // About Me Photo
    $wp_customize->add_setting('about_me_photo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'about_me_photo', array(
        'label'       => __('About Me Photo', 'personal-website'),
        'description' => __('Upload a portrait photo for the About Me section (recommended: portrait orientation, 400x600px)', 'personal-website'),
        'section'     => 'front_page_about',
    )));
    
    // About Me Description
    $wp_customize->add_setting('about_me_description', array(
        'default'           => 'With over 5 years of experience in software development, I specialize in creating scalable web applications and mobile solutions. I\'m passionate about clean code, user experience, and staying up-to-date with the latest technologies.\n\nI enjoy tackling complex problems and turning innovative ideas into reality. When I\'m not coding, you can find me exploring new technologies, contributing to open-source projects, or sharing knowledge through technical writing.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('about_me_description', array(
        'label'       => __('About Me Description', 'personal-website'),
        'description' => __('Write a detailed description about yourself for the About Me section', 'personal-website'),
        'section'     => 'front_page_about',
        'type'        => 'textarea',
        'settings'    => 'about_me_description',
    ));
    
    // About Section Email
    $wp_customize->add_setting('about_section_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('about_section_email', array(
        'label'       => __('About Section Email', 'personal-website'),
        'description' => __('Email address for "Get In Touch" button. Leave empty to use general contact email.', 'personal-website'),
        'section'     => 'front_page_about',
        'type'        => 'email',
    ));
    
    // Years of Experience
    $wp_customize->add_setting('years_experience', array(
        'default'           => '5',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('years_experience', array(
        'label'       => __('Years of Experience', 'personal-website'),
        'description' => __('Number of years of professional experience', 'personal-website'),
        'section'     => 'front_page_about',
        'type'        => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
        ),
    ));
    
    // Projects Completed
    $wp_customize->add_setting('projects_completed', array(
        'default'           => '50',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('projects_completed', array(
        'label'       => __('Projects Completed', 'personal-website'),
        'description' => __('Number of projects you have completed', 'personal-website'),
        'section'     => 'front_page_about',
        'type'        => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 1000,
            'step' => 1,
        ),
    ));
    
    // Front Page Contact Section
    $wp_customize->add_section('front_page_contact', array(
        'title'    => __('Front Page - Contact', 'personal-website'),
        'priority' => 33,
    ));
    
    // Contact Section Title
    $wp_customize->add_setting('contact_section_title', array(
        'default'           => 'Let\'s Work Together',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_section_title', array(
        'label'       => __('Contact Section Title', 'personal-website'),
        'description' => __('Main heading for the contact section', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'text',
    ));
    
    // Contact Section Description
    $wp_customize->add_setting('contact_section_description', array(
        'default'           => 'Ready to start your next project? Let\'s discuss how I can help bring your ideas to life',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('contact_section_description', array(
        'label'       => __('Contact Section Description', 'personal-website'),
        'description' => __('Subtitle/description under the main heading', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'textarea',
    ));
    
    // Front Page Contact Email
    $wp_customize->add_setting('front_contact_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('front_contact_email', array(
        'label'       => __('Contact Email', 'personal-website'),
        'description' => __('Email address for the front page contact section. Leave empty to use general contact email.', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'email',
    ));
    
    // Front Page Contact Phone
    $wp_customize->add_setting('front_contact_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('front_contact_phone', array(
        'label'       => __('Contact Phone', 'personal-website'),
        'description' => __('Phone number for the front page contact section. Leave empty to use general contact phone.', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'text',
    ));
    
    // Front Page Contact Location
    $wp_customize->add_setting('front_contact_location', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('front_contact_location', array(
        'label'       => __('Contact Location', 'personal-website'),
        'description' => __('Location for the front page contact section. Leave empty to use general contact location.', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'text',
    ));
    
    // Social Media Links for Front Page
    // X (Twitter)
    $wp_customize->add_setting('front_social_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('front_social_twitter', array(
        'label'       => __('X (Twitter) URL', 'personal-website'),
        'description' => __('Your X (Twitter) profile URL. Leave empty to hide.', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'url',
    ));
    
    // LinkedIn
    $wp_customize->add_setting('front_social_linkedin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('front_social_linkedin', array(
        'label'       => __('LinkedIn URL', 'personal-website'),
        'description' => __('Your LinkedIn profile URL. Leave empty to hide.', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'url',
    ));
    
    // GitHub
    $wp_customize->add_setting('front_social_github', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('front_social_github', array(
        'label'       => __('GitHub URL', 'personal-website'),
        'description' => __('Your GitHub profile URL. Leave empty to hide.', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'url',
    ));
    
    // Facebook
    $wp_customize->add_setting('front_social_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('front_social_facebook', array(
        'label'       => __('Facebook URL', 'personal-website'),
        'description' => __('Your Facebook profile URL. Leave empty to hide.', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'url',
    ));
    
    // Instagram
    $wp_customize->add_setting('front_social_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('front_social_instagram', array(
        'label'       => __('Instagram URL', 'personal-website'),
        'description' => __('Your Instagram profile URL. Leave empty to hide.', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'url',
    ));
    
    // Contact CTA Message
    $wp_customize->add_setting('contact_cta_message', array(
        'default'           => 'I\'m currently available for freelance work and new opportunities. Whether you need a complete web application, mobile app, or just want to discuss your ideas, I\'d love to hear from you.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('contact_cta_message', array(
        'label'       => __('Contact CTA Message', 'personal-website'),
        'description' => __('Call-to-action message in the contact section right panel', 'personal-website'),
        'section'     => 'front_page_contact',
        'type'        => 'textarea',
    ));
    
    // Front Page Latest Posts Section
    $wp_customize->add_section('front_page_latest_posts', array(
        'title'    => __('Front Page - Latest Posts', 'personal-website'),
        'priority' => 34,
    ));
    
    // Blog Section Title
    $wp_customize->add_setting('blog_section_title', array(
        'default'           => 'Latest Blog Posts',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('blog_section_title', array(
        'label'       => __('Blog Section Title', 'personal-website'),
        'description' => __('Title for the blog section on front page', 'personal-website'),
        'section'     => 'front_page_latest_posts',
        'type'        => 'text',
    ));
    
    // Blog Section Description
    $wp_customize->add_setting('blog_section_description', array(
        'default'           => 'Insights and tutorials about software development and technology',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('blog_section_description', array(
        'label'       => __('Blog Section Description', 'personal-website'),
        'description' => __('Description under the blog section title', 'personal-website'),
        'section'     => 'front_page_latest_posts',
        'type'        => 'textarea',
    ));
    
    // Featured Post 1
    $wp_customize->add_setting('featured_post_1', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('featured_post_1', array(
        'label'       => __('Featured Post 1 (ID)', 'personal-website'),
        'description' => __('Enter the post ID for the first featured post. Leave empty for no post.', 'personal-website'),
        'section'     => 'front_page_latest_posts',
        'type'        => 'number',
        'input_attrs' => array(
            'min' => 1,
            'step' => 1,
        ),
    ));
    
    // Featured Post 2
    $wp_customize->add_setting('featured_post_2', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('featured_post_2', array(
        'label'       => __('Featured Post 2 (ID)', 'personal-website'),
        'description' => __('Enter the post ID for the second featured post. Leave empty for no post.', 'personal-website'),
        'section'     => 'front_page_latest_posts',
        'type'        => 'number',
        'input_attrs' => array(
            'min' => 1,
            'step' => 1,
        ),
    ));
    
    // Featured Post 3
    $wp_customize->add_setting('featured_post_3', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('featured_post_3', array(
        'label'       => __('Featured Post 3 (ID)', 'personal-website'),
        'description' => __('Enter the post ID for the third featured post. Leave empty for no post.', 'personal-website'),
        'section'     => 'front_page_latest_posts',
        'type'        => 'number',
        'input_attrs' => array(
            'min' => 1,
            'step' => 1,
        ),
    ));
    
    // Help Text for Finding Post IDs
    $wp_customize->add_setting('featured_posts_help', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('featured_posts_help', array(
        'label'       => __('💡 How It Works', 'personal-website'),
        'description' => __('• Enter specific post IDs to feature those posts<br>• Leave fields empty for no post in that slot<br>• If ALL fields are empty, recent posts will be shown<br><br><strong>Finding Post IDs:</strong> Go to Posts → All Posts, hover over a post title and look at the URL. The number after "post=" is the post ID. Example: post=123 means ID is 123.', 'personal-website'),
        'section'     => 'front_page_latest_posts',
        'type'        => 'hidden',
    ));
    
    // Contact Information Section
    $wp_customize->add_section('contact_info', array(
        'title'    => __('Contact Information', 'personal-website'),
        'priority' => 35,
    ));
    
    // Email
    $wp_customize->add_setting('contact_email', array(
        'default'           => 'hello@ericssonbudhilaw.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('contact_email', array(
        'label'   => __('Email Address', 'personal-website'),
        'section' => 'contact_info',
        'type'    => 'email',
    ));
    
    // Phone
    $wp_customize->add_setting('contact_phone', array(
        'default'           => '+1 (555) 123-4567',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_phone', array(
        'label'   => __('Phone Number', 'personal-website'),
        'section' => 'contact_info',
        'type'    => 'text',
    ));
    
    // Location
    $wp_customize->add_setting('contact_location', array(
        'default'           => 'Jakarta, Indonesia',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_location', array(
        'label'   => __('Location', 'personal-website'),
        'section' => 'contact_info',
        'type'    => 'text',
    ));
    
    // Social Media Section
    $wp_customize->add_section('social_media', array(
        'title'    => __('Social Media Links', 'personal-website'),
        'priority' => 40,
    ));
    
    // LinkedIn
    $wp_customize->add_setting('social_linkedin', array(
        'default'           => 'https://www.linkedin.com/in/ericsson-budhilaw',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('social_linkedin', array(
        'label'   => __('LinkedIn URL', 'personal-website'),
        'section' => 'social_media',
        'type'    => 'url',
    ));
    
    // GitHub
    $wp_customize->add_setting('social_github', array(
        'default'           => 'https://github.com/budhilaw',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('social_github', array(
        'label'   => __('GitHub URL', 'personal-website'),
        'section' => 'social_media',
        'type'    => 'url',
    ));
    
    // Twitter
    $wp_customize->add_setting('social_twitter', array(
        'default'           => 'https://twitter.com/ericsson_budhi',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('social_twitter', array(
        'label'   => __('Twitter URL', 'personal-website'),
        'section' => 'social_media',
        'type'    => 'url',
    ));
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
    return get_theme_mod('contact_email', 'hello@ericssonbudhilaw.com');
}

function get_contact_phone() {
    return get_theme_mod('contact_phone', '+1 (555) 123-4567');
}

function get_contact_location() {
    return get_theme_mod('contact_location', 'Jakarta, Indonesia');
}

function get_social_linkedin() {
    return get_theme_mod('social_linkedin', 'https://www.linkedin.com/in/ericsson-budhilaw');
}

function get_social_github() {
    return get_theme_mod('social_github', 'https://github.com/budhilaw');
}

function get_social_twitter() {
    return get_theme_mod('social_twitter', 'https://twitter.com/ericsson_budhi');
}

function get_about_me_photo() {
    return get_theme_mod('about_me_photo', '');
}

function get_about_me_description() {
    return get_theme_mod('about_me_description', 'With over 5 years of experience in software development, I specialize in creating scalable web applications and mobile solutions. I\'m passionate about clean code, user experience, and staying up-to-date with the latest technologies.\n\nI enjoy tackling complex problems and turning innovative ideas into reality. When I\'m not coding, you can find me exploring new technologies, contributing to open-source projects, or sharing knowledge through technical writing.');
}

function get_about_section_email() {
    $about_email = get_theme_mod('about_section_email', '');
    return !empty($about_email) ? $about_email : get_contact_email();
}

function get_years_experience() {
    return get_theme_mod('years_experience', '5');
}

function get_projects_completed() {
    return get_theme_mod('projects_completed', '50');
}

function get_hero_greeting() {
    return get_theme_mod('hero_greeting', 'Hi, I\'m');
}

function get_hero_description() {
    $custom_description = get_theme_mod('hero_description', '');
    if (!empty($custom_description)) {
        return $custom_description;
    }
    // Fallback to job title + bio format
    return get_job_title() . ' - ' . get_personal_bio();
}

function get_hero_background_image() {
    return get_theme_mod('hero_background_image', '');
}

function get_hero_primary_cta_text() {
    return get_theme_mod('hero_primary_cta_text', 'View My Work');
}

function get_hero_primary_cta_link() {
    return get_theme_mod('hero_primary_cta_link', '#portfolio');
}

function get_hero_secondary_cta_text() {
    return get_theme_mod('hero_secondary_cta_text', 'Hire Me');
}

function get_hero_secondary_cta_link() {
    return get_theme_mod('hero_secondary_cta_link', '#contact');
}

function get_hero_show_animated_bg() {
    return get_theme_mod('hero_show_animated_bg', true);
}

function get_contact_section_title() {
    return get_theme_mod('contact_section_title', 'Let\'s Work Together');
}

function get_contact_section_description() {
    return get_theme_mod('contact_section_description', 'Ready to start your next project? Let\'s discuss how I can help bring your ideas to life');
}

function get_front_contact_email() {
    $front_email = get_theme_mod('front_contact_email', '');
    return !empty($front_email) ? $front_email : get_contact_email();
}

function get_front_contact_phone() {
    $front_phone = get_theme_mod('front_contact_phone', '');
    return !empty($front_phone) ? $front_phone : get_contact_phone();
}

function get_front_contact_location() {
    $front_location = get_theme_mod('front_contact_location', '');
    return !empty($front_location) ? $front_location : get_contact_location();
}

function get_front_social_twitter() {
    $front_twitter = get_theme_mod('front_social_twitter', '');
    return !empty($front_twitter) ? $front_twitter : get_social_twitter();
}

function get_front_social_linkedin() {
    $front_linkedin = get_theme_mod('front_social_linkedin', '');
    return !empty($front_linkedin) ? $front_linkedin : get_social_linkedin();
}

function get_front_social_github() {
    $front_github = get_theme_mod('front_social_github', '');
    return !empty($front_github) ? $front_github : get_social_github();
}

function get_front_social_facebook() {
    return get_theme_mod('front_social_facebook', '');
}

function get_front_social_instagram() {
    return get_theme_mod('front_social_instagram', '');
}

function get_contact_cta_message() {
    return get_theme_mod('contact_cta_message', 'I\'m currently available for freelance work and new opportunities. Whether you need a complete web application, mobile app, or just want to discuss your ideas, I\'d love to hear from you.');
}

function get_blog_section_title() {
    return get_theme_mod('blog_section_title', 'Latest Blog Posts');
}

function get_blog_section_description() {
    return get_theme_mod('blog_section_description', 'Insights and tutorials about software development and technology');
}

function get_featured_post_1() {
    return get_theme_mod('featured_post_1', '');
}

function get_featured_post_2() {
    return get_theme_mod('featured_post_2', '');
}

function get_featured_post_3() {
    return get_theme_mod('featured_post_3', '');
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
