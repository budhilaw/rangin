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
