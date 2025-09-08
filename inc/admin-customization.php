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

/**
 * Add Theme Options admin menu
 */
function personal_website_add_theme_options_menu() {
    add_menu_page(
        __('Theme Options', 'rangin'),     // Page title
        __('Theme Options', 'rangin'),     // Menu title
        'manage_options',                            // Capability
        'theme-options',                             // Menu slug
        'personal_website_theme_options_page',       // Callback function
        'dashicons-admin-customizer',                // Icon
        59                                           // Position (between Appearance=60 and Plugins=65)
    );
}
add_action('admin_menu', 'personal_website_add_theme_options_menu');

/**
 * Theme Options page content
 */
function personal_website_theme_options_page() {
    // Handle form submissions (save or bulk actions)
    if ((isset($_POST['submit']) || isset($_POST['run_bulk_image_conversion'])) && check_admin_referer('theme_options_nonce')) {
        $success_messages = array();
        
        // Site Information
        if (isset($_POST['site_title'])) {
            update_option('blogname', sanitize_text_field($_POST['site_title']));
            $success_messages[] = __('Site title updated.', 'rangin');
        }
        
        // Hero Section
        if (isset($_POST['hero_greeting'])) {
            update_option('hero_greeting', sanitize_text_field(stripslashes($_POST['hero_greeting'])));
        }
        if (isset($_POST['hero_description'])) {
            update_option('hero_description', sanitize_textarea_field(stripslashes($_POST['hero_description'])));
        }
        if (isset($_POST['hero_primary_cta_text'])) {
            update_option('hero_primary_cta_text', sanitize_text_field(stripslashes($_POST['hero_primary_cta_text'])));
        }
        if (isset($_POST['hero_primary_cta_link'])) {
            $primary_link = sanitize_text_field($_POST['hero_primary_cta_link']);
            // Allow anchor links (#), mailto:, tel:, and regular URLs
            if (strpos($primary_link, '#') === 0 || strpos($primary_link, 'mailto:') === 0 || strpos($primary_link, 'tel:') === 0) {
                update_option('hero_primary_cta_link', $primary_link);
            } else {
                update_option('hero_primary_cta_link', esc_url_raw($primary_link));
            }
        }
        if (isset($_POST['hero_secondary_cta_text'])) {
            update_option('hero_secondary_cta_text', sanitize_text_field(stripslashes($_POST['hero_secondary_cta_text'])));
        }
        if (isset($_POST['hero_secondary_cta_link'])) {
            $secondary_link = sanitize_text_field($_POST['hero_secondary_cta_link']);
            // Allow anchor links (#), mailto:, tel:, and regular URLs
            if (strpos($secondary_link, '#') === 0 || strpos($secondary_link, 'mailto:') === 0 || strpos($secondary_link, 'tel:') === 0) {
                update_option('hero_secondary_cta_link', $secondary_link);
            } else {
                update_option('hero_secondary_cta_link', esc_url_raw($secondary_link));
            }
        }
        if (isset($_POST['hero_show_animated_bg'])) {
            update_option('hero_show_animated_bg', wp_validate_boolean($_POST['hero_show_animated_bg']));
        } else {
            update_option('hero_show_animated_bg', false);
        }
        
        // About Me Section
        if (isset($_POST['about_me_photo'])) {
            update_option('about_me_photo', esc_url_raw($_POST['about_me_photo']));
        }
        if (isset($_POST['about_me_description'])) {
            update_option('about_me_description', sanitize_textarea_field(stripslashes($_POST['about_me_description'])));
        }
        if (isset($_POST['about_section_email'])) {
            update_option('about_section_email', sanitize_email($_POST['about_section_email']));
        }
        if (isset($_POST['years_experience'])) {
            update_option('years_experience', absint($_POST['years_experience']));
        }
        if (isset($_POST['projects_completed'])) {
            update_option('projects_completed', absint($_POST['projects_completed']));
        }
        
        // Services Section
        if (isset($_POST['services_section_title'])) {
            update_option('services_section_title', sanitize_text_field(stripslashes($_POST['services_section_title'])));
        }
        if (isset($_POST['services_section_subtitle'])) {
            update_option('services_section_subtitle', sanitize_textarea_field(stripslashes($_POST['services_section_subtitle'])));
        }
        if (isset($_POST['services_list'])) {
            update_option('services_list', wp_kses_post(stripslashes($_POST['services_list'])));
        }

        // Skills & Expertise Section removed
        
        // Portfolio Section
        if (isset($_POST['portfolio_section_title'])) {
            update_option('portfolio_section_title', sanitize_text_field(stripslashes($_POST['portfolio_section_title'])));
        }
        if (isset($_POST['portfolio_section_description'])) {
            update_option('portfolio_section_description', sanitize_textarea_field(stripslashes($_POST['portfolio_section_description'])));
        }
        if (isset($_POST['portfolio_posts_count'])) {
            update_option('portfolio_posts_count', absint($_POST['portfolio_posts_count']));
        }
        if (isset($_POST['portfolio_section_show'])) {
            update_option('portfolio_section_show', wp_validate_boolean($_POST['portfolio_section_show']));
        } else {
            update_option('portfolio_section_show', false);
        }
        if (isset($_POST['featured_portfolio_1'])) {
            update_option('featured_portfolio_1', absint($_POST['featured_portfolio_1']));
        }
        if (isset($_POST['featured_portfolio_2'])) {
            update_option('featured_portfolio_2', absint($_POST['featured_portfolio_2']));
        }
        if (isset($_POST['featured_portfolio_3'])) {
            update_option('featured_portfolio_3', absint($_POST['featured_portfolio_3']));
        }
        
        // Latest Posts Section
        if (isset($_POST['blog_section_title'])) {
            update_option('blog_section_title', sanitize_text_field(stripslashes($_POST['blog_section_title'])));
        }
        if (isset($_POST['blog_section_description'])) {
            update_option('blog_section_description', sanitize_textarea_field(stripslashes($_POST['blog_section_description'])));
        }
        if (isset($_POST['featured_post_1'])) {
            update_option('featured_post_1', absint($_POST['featured_post_1']));
        }
        if (isset($_POST['featured_post_2'])) {
            update_option('featured_post_2', absint($_POST['featured_post_2']));
        }
        if (isset($_POST['featured_post_3'])) {
            update_option('featured_post_3', absint($_POST['featured_post_3']));
        }
        
        // Social Media Links
        if (isset($_POST['social_github'])) {
            update_option('social_github', esc_url_raw($_POST['social_github']));
        }
        if (isset($_POST['social_gitlab'])) {
            update_option('social_gitlab', esc_url_raw($_POST['social_gitlab']));
        }
        if (isset($_POST['social_linkedin'])) {
            update_option('social_linkedin', esc_url_raw($_POST['social_linkedin']));
        }
        if (isset($_POST['social_x'])) {
            update_option('social_x', esc_url_raw($_POST['social_x']));
        }
        if (isset($_POST['social_facebook'])) {
            update_option('social_facebook', esc_url_raw($_POST['social_facebook']));
        }
        if (isset($_POST['social_instagram'])) {
            update_option('social_instagram', esc_url_raw($_POST['social_instagram']));
        }
        if (isset($_POST['social_threads'])) {
            update_option('social_threads', esc_url_raw($_POST['social_threads']));
        }

        // Contact Information (General) removed; use Front Page → Contact Section
        
        // Contact Information
        if (isset($_POST['contact_section_title'])) {
            update_option('contact_section_title', sanitize_text_field(stripslashes($_POST['contact_section_title'])));
        }
        if (isset($_POST['contact_section_description'])) {
            update_option('contact_section_description', sanitize_textarea_field(stripslashes($_POST['contact_section_description'])));
        }
        if (isset($_POST['front_contact_email'])) {
            update_option('front_contact_email', sanitize_email($_POST['front_contact_email']));
        }
        if (isset($_POST['front_contact_phone'])) {
            update_option('front_contact_phone', sanitize_text_field($_POST['front_contact_phone']));
        }
        if (isset($_POST['front_contact_location'])) {
            update_option('front_contact_location', sanitize_text_field($_POST['front_contact_location']));
        }
        if (isset($_POST['contact_cta_message'])) {
            update_option('contact_cta_message', sanitize_textarea_field(stripslashes($_POST['contact_cta_message'])));
        }

        // About Page - General
        if (isset($_POST['about_page_title'])) {
            update_option('about_page_title', sanitize_text_field(stripslashes($_POST['about_page_title'])));
        }
        if (isset($_POST['about_page_subtitle'])) {
            update_option('about_page_subtitle', sanitize_textarea_field(stripslashes($_POST['about_page_subtitle'])));
        }

        // Footer (Brand & Bottom)
        if (isset($_POST['footer_brand_title'])) {
            update_option('footer_brand_title', sanitize_text_field(stripslashes($_POST['footer_brand_title'])));
        }
        if (isset($_POST['footer_brand_description'])) {
            update_option('footer_brand_description', sanitize_textarea_field(stripslashes($_POST['footer_brand_description'])));
        }
        if (isset($_POST['footer_copyright'])) {
            update_option('footer_copyright', wp_kses_post(stripslashes($_POST['footer_copyright'])));
        }
        if (isset($_POST['footer_link_1_text'])) {
            update_option('footer_link_1_text', sanitize_text_field(stripslashes($_POST['footer_link_1_text'])));
        }
        if (isset($_POST['footer_link_1_url'])) {
            update_option('footer_link_1_url', esc_url_raw($_POST['footer_link_1_url']));
        }
        if (isset($_POST['footer_link_2_text'])) {
            update_option('footer_link_2_text', sanitize_text_field(stripslashes($_POST['footer_link_2_text'])));
        }
        if (isset($_POST['footer_link_2_url'])) {
            update_option('footer_link_2_url', esc_url_raw($_POST['footer_link_2_url']));
        }
        if (isset($_POST['footer_made_with_text'])) {
            update_option('footer_made_with_text', sanitize_text_field(stripslashes($_POST['footer_made_with_text'])));
        }

        // Image Optimization
        // Only allow enabling when Imagick is installed
        if (class_exists('Imagick') && isset($_POST['enable_image_optimization'])) {
            update_option('enable_image_optimization', wp_validate_boolean($_POST['enable_image_optimization']));
        } else {
            update_option('enable_image_optimization', false);
        }
        if (isset($_POST['image_opt_target_format'])) {
            $fmt = $_POST['image_opt_target_format'] === 'avif' ? 'avif' : 'webp';
            update_option('image_opt_target_format', $fmt);
        }
        if (isset($_POST['enable_cloudflare_cdn'])) {
            update_option('enable_cloudflare_cdn', wp_validate_boolean($_POST['enable_cloudflare_cdn']));
        } else {
            update_option('enable_cloudflare_cdn', false);
        }
        if (isset($_POST['cloudflare_cdn_domain'])) {
            update_option('cloudflare_cdn_domain', sanitize_text_field($_POST['cloudflare_cdn_domain']));
        }
        if (isset($_POST['cloudflare_image_quality'])) {
            update_option('cloudflare_image_quality', absint($_POST['cloudflare_image_quality']));
        }

        // Security - Cloudflare Turnstile
        if (isset($_POST['enable_turnstile'])) {
            update_option('enable_turnstile', wp_validate_boolean($_POST['enable_turnstile']));
        } else {
            update_option('enable_turnstile', false);
        }
        if (isset($_POST['turnstile_site_key'])) {
            update_option('turnstile_site_key', sanitize_text_field($_POST['turnstile_site_key']));
        }
        if (isset($_POST['turnstile_secret_key'])) {
            update_option('turnstile_secret_key', sanitize_text_field($_POST['turnstile_secret_key']));
        }

        if (isset($_POST['image_opt_force_modern_only'])) {
            update_option('image_opt_force_modern_only', wp_validate_boolean($_POST['image_opt_force_modern_only']));
        } else {
            update_option('image_opt_force_modern_only', false);
        }

        if (isset($_POST['image_opt_convert_original'])) {
            update_option('image_opt_convert_original', wp_validate_boolean($_POST['image_opt_convert_original']));
        } else {
            update_option('image_opt_convert_original', false);
        }
        if (isset($_POST['image_opt_max_mp'])) {
            update_option('image_opt_max_mp', max(1, absint($_POST['image_opt_max_mp'])));
        }

        if (isset($_POST['image_opt_convert_quality'])) {
            $q = max(1, min(100, absint($_POST['image_opt_convert_quality'])));
            update_option('image_opt_convert_quality', $q);
        }

        // Performance: Asset minification
        if (isset($_POST['enable_asset_minification'])) {
            update_option('enable_asset_minification', wp_validate_boolean($_POST['enable_asset_minification']));
        } else {
            update_option('enable_asset_minification', false);
        }
        if (isset($_POST['purge_asset_min_cache'])) {
            $uploads = wp_get_upload_dir();
            $dir = trailingslashit($uploads['basedir']) . 'rangin-cache/min';
            if (is_dir($dir)) {
                foreach (glob($dir . '/*') as $f) { @unlink($f); }
            }
            echo '<div class="theme-options-inline-msg" style="margin-top:12px;padding:12px;border:1px solid #c7d2fe;background:#eff6ff;border-radius:6px;color:#1e3a8a;">' . __('Minified cache purged.', 'personal-website') . '</div>';
        }
        if (isset($_POST['enable_asset_minify_js'])) {
            update_option('enable_asset_minify_js', wp_validate_boolean($_POST['enable_asset_minify_js']));
        } else {
            update_option('enable_asset_minify_js', false);
        }

        if (isset($_POST['run_bulk_image_conversion']) && wp_validate_boolean($_POST['run_bulk_image_conversion'])) {
            if (function_exists('image_opt_bulk_convert_with_log')) {
                $res = image_opt_bulk_convert_with_log(50);
                $converted = intval($res['count']);
                $logs = is_array($res['logs']) ? $res['logs'] : array();
                echo '<div class="theme-options-inline-msg" style="margin-top:12px;padding:12px;border:1px solid #c7d2fe;background:#eff6ff;border-radius:6px;color:#1e3a8a;">';
                echo '<strong>' . sprintf(__('Bulk conversion completed. Converted %d attachments (where supported).', 'personal-website'), $converted) . '</strong>';
                if (!empty($logs)) {
                    echo '<div style="margin-top:8px; max-height:260px; overflow:auto; background:#fff; border:1px solid #e5e7eb; border-radius:6px;">';
                    echo '<ul style="margin:0; padding:10px 14px; list-style:disc inside;">';
                    foreach ($logs as $line) {
                        echo '<li>' . wp_kses_post($line) . '</li>';
                    }
                    echo '</ul></div>';
                }
                echo '</div>';
                echo '<script>document.addEventListener("DOMContentLoaded",function(){var t=document.querySelector('."'".'.theme-options-tab[data-tab=\"image-optimization\"]'."'".');if(t){t.click();}});</script>';
            }
        }
        
        if (!empty($success_messages)) {
            echo '<div class="theme-options-notice notice notice-success is-dismissible"><p>' . __('Settings saved successfully!', 'personal-website') . '</p></div>';
        }
    }
    
    // Get current values
    $current_site_title = get_option('blogname');
    $hero_greeting = get_option('hero_greeting', "Hi, I'm");
    $hero_description = get_option('hero_description', '');
    $hero_primary_cta_text = get_option('hero_primary_cta_text', 'View My Work');
    $hero_primary_cta_link = get_option('hero_primary_cta_link', '#portfolio');
    $hero_secondary_cta_text = get_option('hero_secondary_cta_text', 'Hire Me');
    $hero_secondary_cta_link = get_option('hero_secondary_cta_link', '#contact');
    $hero_show_animated_bg = get_option('hero_show_animated_bg', true);
    $about_me_photo = get_option('about_me_photo', '');
    $about_me_description = get_option('about_me_description', "With over 5 years of experience in software development, I specialize in creating scalable web applications and mobile solutions. I'm passionate about clean code, user experience, and staying up-to-date with the latest technologies.\n\nI enjoy tackling complex problems and turning innovative ideas into reality. When I'm not coding, you can find me exploring new technologies, contributing to open-source projects, or sharing knowledge through technical writing.");
    $about_section_email = get_option('about_section_email', '');
    $years_experience = get_option('years_experience', 5);
    $projects_completed = get_option('projects_completed', 50);
    $services_section_title = get_option('services_section_title', 'Services');
    $services_section_subtitle = get_option('services_section_subtitle', 'I offer a range of software development services to help bring your ideas to life');
    $services_list = get_option('services_list', '');
    $portfolio_section_title = get_option('portfolio_section_title', 'Featured Projects');
    $portfolio_section_description = get_option('portfolio_section_description', "Here are some of the projects I've worked on recently");
    $portfolio_posts_count = get_option('portfolio_posts_count', 6);
    $portfolio_section_show = get_option('portfolio_section_show', true);
    $featured_portfolio_1 = get_option('featured_portfolio_1', '');
    $featured_portfolio_2 = get_option('featured_portfolio_2', '');
    $featured_portfolio_3 = get_option('featured_portfolio_3', '');
    $blog_section_title = get_option('blog_section_title', 'Latest Blog Posts');
    $blog_section_description = get_option('blog_section_description', 'Insights and tutorials about software development and technology');
    $featured_post_1 = get_option('featured_post_1', '');
    $featured_post_2 = get_option('featured_post_2', '');
    $featured_post_3 = get_option('featured_post_3', '');
    
    // Social Media Links
    $social_github = get_option('social_github', '');
    $social_gitlab = get_option('social_gitlab', '');
    $social_linkedin = get_option('social_linkedin', '');
    $social_x = get_option('social_x', '');
    $social_facebook = get_option('social_facebook', '');
    $social_instagram = get_option('social_instagram', '');
    $social_threads = get_option('social_threads', '');

    // Footer (Brand & Bottom)
    $footer_brand_title = get_option('footer_brand_title', get_theme_mod('footer_brand_title', get_bloginfo('name')));
    $footer_brand_description = get_option('footer_brand_description', get_theme_mod('footer_brand_description', 'Full-Stack Software Engineer specializing in creating innovative digital solutions. Let\'s build something amazing together.'));
    $footer_copyright = get_option('footer_copyright', get_theme_mod('footer_copyright', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.'));
    $footer_link_1_text = get_option('footer_link_1_text', get_theme_mod('footer_link_1_text', 'Privacy Policy'));
    $footer_link_1_url  = get_option('footer_link_1_url', get_theme_mod('footer_link_1_url', ''));
    $footer_link_2_text = get_option('footer_link_2_text', get_theme_mod('footer_link_2_text', 'Terms of Service'));
    $footer_link_2_url  = get_option('footer_link_2_url', get_theme_mod('footer_link_2_url', ''));
    $footer_made_with_text = get_option('footer_made_with_text', get_theme_mod('footer_made_with_text', 'Made with ❤️ by Ericsson Budhilaw'));
    
    // Contact Information
    $contact_section_title = get_option('contact_section_title', 'Let\'s Work Together');
    $contact_section_description = get_option('contact_section_description', 'Ready to start your next project? Let\'s discuss how I can help bring your ideas to life');
    $front_contact_email = get_option('front_contact_email', '');
    $front_contact_phone = get_option('front_contact_phone', '');
    $front_contact_location = get_option('front_contact_location', '');
    $contact_cta_message = get_option('contact_cta_message', 'I\'m currently available for freelance work and new opportunities. Whether you need a complete web application, mobile app, or just want to discuss your ideas, I\'d love to hear from you.');

    // Security - Cloudflare Turnstile
    $enable_turnstile   = (bool) get_option('enable_turnstile', false);
    $turnstile_site_key = get_option('turnstile_site_key', '');
    $turnstile_secret   = get_option('turnstile_secret_key', '');
    ?>
    <div class="theme-options-wrap">
        <div class="theme-options-header">
            <h1><span class="dashicons dashicons-admin-customizer"></span><?php _e('Theme Options', 'personal-website'); ?></h1>
            <p class="theme-options-description"><?php _e('Customize your website settings and preferences.', 'personal-website'); ?></p>
        </div>

        <!-- Tabs Navigation -->
        <div class="theme-options-tabs">
            <button type="button" class="theme-options-tab active" data-tab="general">
                <span class="dashicons dashicons-admin-site-alt3"></span>
                <?php _e('General', 'personal-website'); ?>
            </button>
            <button type="button" class="theme-options-tab" data-tab="front-page">
                <span class="dashicons dashicons-admin-home"></span>
                <?php _e('Front Page', 'personal-website'); ?>
            </button>
            <button type="button" class="theme-options-tab" data-tab="about-page">
                <span class="dashicons dashicons-id-alt"></span>
                <?php _e('About Page', 'personal-website'); ?>
            </button>
            <button type="button" class="theme-options-tab" data-tab="footer">
                <span class="dashicons dashicons-editor-underline"></span>
                <?php _e('Footer', 'personal-website'); ?>
            </button>
            <button type="button" class="theme-options-tab" data-tab="image-optimization">
                <span class="dashicons dashicons-format-image"></span>
                <?php _e('Image Optimization', 'personal-website'); ?>
            </button>
            <button type="button" class="theme-options-tab" data-tab="asset-optimization">
                <span class="dashicons dashicons-performance"></span>
                <?php _e('Asset Optimization', 'personal-website'); ?>
            </button>
            <button type="button" class="theme-options-tab" data-tab="security">
                <span class="dashicons dashicons-shield"></span>
                <?php _e('Security', 'personal-website'); ?>
            </button>
        </div>

        <div class="theme-options-content">
            <form method="post" action="">
                <?php wp_nonce_field('theme_options_nonce'); ?>
                
                <!-- General Tab -->
                <div class="theme-options-tab-content active" id="tab-general">
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-admin-site-alt3"></span><?php _e('Site Information', 'personal-website'); ?></h2>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="site_title"><?php _e('Site Title', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="site_title" 
                                name="site_title" 
                                value="<?php echo esc_attr($current_site_title); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('Enter your site title', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('This is the name of your website that appears in the browser title and search results.', 'personal-website'); ?></p>
                        </div>
                    </div>

                    <!-- Contact Information removed: use Front Page → Contact Section as single source of truth -->

                    <!-- Social Media Links Section -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-share"></span><?php _e('Social Media Links', 'personal-website'); ?></h2>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="social_github"><?php _e('GitHub URL', 'personal-website'); ?></label>
                            <input 
                                type="url" 
                                id="social_github" 
                                name="social_github" 
                                value="<?php echo esc_attr($social_github); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('https://github.com/username', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Your GitHub profile URL. Leave empty to hide.', 'personal-website'); ?></p>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="social_gitlab"><?php _e('GitLab URL', 'personal-website'); ?></label>
                            <input 
                                type="url" 
                                id="social_gitlab" 
                                name="social_gitlab" 
                                value="<?php echo esc_attr($social_gitlab); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('https://gitlab.com/username', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Your GitLab profile URL. Leave empty to hide.', 'personal-website'); ?></p>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="social_linkedin"><?php _e('LinkedIn URL', 'personal-website'); ?></label>
                            <input 
                                type="url" 
                                id="social_linkedin" 
                                name="social_linkedin" 
                                value="<?php echo esc_attr($social_linkedin); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('https://linkedin.com/in/username', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Your LinkedIn profile URL. Leave empty to hide.', 'personal-website'); ?></p>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="social_x"><?php _e('X (Twitter) URL', 'personal-website'); ?></label>
                            <input 
                                type="url" 
                                id="social_x" 
                                name="social_x" 
                                value="<?php echo esc_attr($social_x); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('https://x.com/username', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Your X (Twitter) profile URL. Leave empty to hide.', 'personal-website'); ?></p>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="social_facebook"><?php _e('Facebook URL', 'personal-website'); ?></label>
                            <input 
                                type="url" 
                                id="social_facebook" 
                                name="social_facebook" 
                                value="<?php echo esc_attr($social_facebook); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('https://facebook.com/username', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Your Facebook profile URL. Leave empty to hide.', 'personal-website'); ?></p>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="social_instagram"><?php _e('Instagram URL', 'personal-website'); ?></label>
                            <input 
                                type="url" 
                                id="social_instagram" 
                                name="social_instagram" 
                                value="<?php echo esc_attr($social_instagram); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('https://instagram.com/username', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Your Instagram profile URL. Leave empty to hide.', 'personal-website'); ?></p>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="social_threads"><?php _e('Threads URL', 'personal-website'); ?></label>
                            <input 
                                type="url" 
                                id="social_threads" 
                                name="social_threads" 
                                value="<?php echo esc_attr($social_threads); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('https://threads.net/@username', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Your Threads profile URL. Leave empty to hide.', 'personal-website'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- About Page Tab -->
                <div class="theme-options-tab-content" id="tab-about-page">
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-admin-page"></span><?php _e('About Page - General', 'personal-website'); ?></h2>
                        </div>

                        <?php 
                        $opt_about_page_title = get_option('about_page_title', get_theme_mod('about_page_title', ''));
                        $opt_about_page_subtitle = get_option('about_page_subtitle', get_theme_mod('about_page_subtitle', ''));
                        ?>

                        <div class="theme-options-field">
                            <label for="about_page_title"><?php _e('About Page Title', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="about_page_title" 
                                name="about_page_title" 
                                value="<?php echo esc_attr($opt_about_page_title); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('Leave empty to use page title', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Custom title for the About page. Leave empty to use the page title.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="about_page_subtitle"><?php _e('About Page Subtitle', 'personal-website'); ?></label>
                            <textarea 
                                id="about_page_subtitle" 
                                name="about_page_subtitle" 
                                class="theme-options-textarea"
                                rows="3"
                                placeholder="<?php _e('Leave empty to use the page excerpt', 'personal-website'); ?>"
                            ><?php echo esc_textarea($opt_about_page_subtitle); ?></textarea>
                            <p class="theme-options-help"><?php _e('Custom subtitle/description for the About page. Leave empty to use the page excerpt.', 'personal-website'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Front Page Tab -->
                <div class="theme-options-tab-content" id="tab-front-page">
                    <!-- Hero Section -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-format-image"></span><?php _e('Hero Section', 'personal-website'); ?></h2>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="hero_greeting"><?php _e('Hero Greeting Text', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="hero_greeting" 
                                name="hero_greeting" 
                                value="<?php echo esc_attr($hero_greeting); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e("Hi, I'm", 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('The greeting text before your name (e.g., "Hello, I\'m", "Welcome, I\'m").', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="hero_description"><?php _e('Hero Description', 'personal-website'); ?></label>
                            <textarea 
                                id="hero_description" 
                                name="hero_description" 
                                class="theme-options-textarea"
                                rows="4"
                                placeholder="<?php _e('Custom description for hero section...', 'personal-website'); ?>"
                            ><?php echo esc_textarea($hero_description); ?></textarea>
                            <p class="theme-options-help"><?php _e('Custom description for hero section. Leave empty to use "Job Title - Bio" format from Personal Information.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="hero_primary_cta_text"><?php _e('Primary Button Text', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="hero_primary_cta_text" 
                                name="hero_primary_cta_text" 
                                value="<?php echo esc_attr($hero_primary_cta_text); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('View My Work', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Text for the main call-to-action button.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="hero_primary_cta_link"><?php _e('Primary Button Link', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="hero_primary_cta_link" 
                                name="hero_primary_cta_link" 
                                value="<?php echo esc_attr($hero_primary_cta_link); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('#portfolio', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('URL or anchor link for primary button (e.g., #portfolio, /contact, external URL).', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="hero_secondary_cta_text"><?php _e('Secondary Button Text', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="hero_secondary_cta_text" 
                                name="hero_secondary_cta_text" 
                                value="<?php echo esc_attr($hero_secondary_cta_text); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('Hire Me', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Text for the secondary call-to-action button.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="hero_secondary_cta_link"><?php _e('Secondary Button Link', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="hero_secondary_cta_link" 
                                name="hero_secondary_cta_link" 
                                value="<?php echo esc_attr($hero_secondary_cta_link); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('#contact', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('URL or anchor link for secondary button (e.g., #contact, mailto:, external URL).', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label>
                                <input 
                                    type="checkbox" 
                                    name="hero_show_animated_bg" 
                                    value="1" 
                                    <?php checked($hero_show_animated_bg); ?>
                                />
                                <?php _e('Show Animated Background Shapes', 'personal-website'); ?>
                            </label>
                            <p class="theme-options-help"><?php _e('Display floating geometric shapes in the background.', 'personal-website'); ?></p>
                        </div>
                    </div>

                                        <!-- About Me Section -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-admin-users"></span><?php _e('About Me Section', 'personal-website'); ?></h2>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="about_me_photo"><?php _e('About Me Photo', 'personal-website'); ?></label>
                            <div class="theme-options-media-upload">
                                <input 
                                    type="hidden" 
                                    id="about_me_photo" 
                                    name="about_me_photo" 
                                    value="<?php echo esc_attr($about_me_photo); ?>"
                                />
                                <div class="media-preview">
                                    <?php if ($about_me_photo): ?>
                                        <img src="<?php echo esc_url($about_me_photo); ?>" alt="About Me Photo">
                                    <?php else: ?>
                                        <div class="no-image-placeholder">
                                            <span class="dashicons dashicons-format-image"></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="media-buttons">
                                    <button type="button" class="button upload-media-button" data-target="about_me_photo">
                                        <?php _e('Choose Image', 'personal-website'); ?>
                                    </button>
                                    <?php if ($about_me_photo): ?>
                                        <button type="button" class="button remove-media-button" data-target="about_me_photo">
                                            <?php _e('Remove Image', 'personal-website'); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p class="theme-options-help"><?php _e('Upload a portrait photo for the About Me section (recommended: 400x600px or similar portrait orientation).', 'personal-website'); ?></p>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="about_me_description"><?php _e('About Me Description', 'personal-website'); ?></label>
                            <textarea 
                                id="about_me_description" 
                                name="about_me_description" 
                                class="theme-options-textarea"
                                rows="8"
                                placeholder="<?php _e('Write a detailed description about yourself...', 'personal-website'); ?>"
                            ><?php echo esc_textarea($about_me_description); ?></textarea>
                            <p class="theme-options-help"><?php _e('Write a detailed description about yourself for the About Me section.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="about_section_email"><?php _e('About Section Email', 'personal-website'); ?></label>
                            <input 
                                type="email" 
                                id="about_section_email" 
                                name="about_section_email" 
                                value="<?php echo esc_attr($about_section_email); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('your@email.com', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Email address for "Get In Touch" button. Leave empty to use general contact email.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="years_experience"><?php _e('Years of Experience', 'personal-website'); ?></label>
                            <input 
                                type="number" 
                                id="years_experience" 
                                name="years_experience" 
                                value="<?php echo esc_attr($years_experience); ?>" 
                                class="theme-options-input"
                                min="0" 
                                max="50" 
                                step="1"
                                placeholder="5"
                            />
                            <p class="theme-options-help"><?php _e('Number of years of professional experience.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="projects_completed"><?php _e('Projects Completed', 'personal-website'); ?></label>
                            <input 
                                type="number" 
                                id="projects_completed" 
                                name="projects_completed" 
                                value="<?php echo esc_attr($projects_completed); ?>" 
                                class="theme-options-input"
                                min="0" 
                                max="1000" 
                                step="1"
                                placeholder="50"
                            />
                            <p class="theme-options-help"><?php _e('Number of projects you have completed.', 'personal-website'); ?></p>
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-admin-tools"></span><?php _e('Services Section', 'personal-website'); ?></h2>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="services_section_title"><?php _e('Services Section Title', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="services_section_title" 
                                name="services_section_title" 
                                value="<?php echo esc_attr($services_section_title); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('Services', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('The main title for the services section.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="services_section_subtitle"><?php _e('Services Section Subtitle', 'personal-website'); ?></label>
                            <textarea 
                                id="services_section_subtitle" 
                                name="services_section_subtitle" 
                                class="theme-options-textarea"
                                rows="3"
                                placeholder="<?php _e('I offer a range of software development services to help bring your ideas to life', 'personal-website'); ?>"
                            ><?php echo esc_textarea($services_section_subtitle); ?></textarea>
                            <p class="theme-options-help"><?php _e('A subtitle or description for the services section.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="services_list"><?php _e('Services List (JSON Format)', 'personal-website'); ?></label>
                            <textarea 
                                id="services_list" 
                                name="services_list" 
                                class="theme-options-textarea"
                                rows="8"
                                placeholder='<?php _e('[{"title":"Web Development","description":"Custom web applications","icon":"fas fa-code","background":"#7c3aed","features":["Responsive Design","SEO Optimization"]}]', 'personal-website'); ?>'
                            ><?php echo esc_textarea($services_list); ?></textarea>
                            <p class="theme-options-help">
                                <?php _e('Enter services in JSON format. Maximum 3 services. Each service should have: title, description, icon, background color, and features array.', 'personal-website'); ?>
                                <br><strong><?php _e('Example:', 'personal-website'); ?></strong> 
                                <code>[{"title":"Web Development","description":"Custom web applications using modern technologies","icon":"fas fa-code","background":"#7c3aed","features":["Responsive Design","SEO Optimization","Performance Focused"]}]</code>
                            </p>
                        </div>
                    </div>

                    <!-- Skills & Expertise Section removed -->

                    <!-- Portfolio Section -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-portfolio"></span><?php _e('Portfolio Section', 'personal-website'); ?></h2>
                        </div>
                        
                        <div class="theme-options-field">
                            <label>
                                <input 
                                    type="checkbox" 
                                    name="portfolio_section_show" 
                                    value="1" 
                                    <?php checked($portfolio_section_show, true); ?>
                                />
                                <?php _e('Show Portfolio Section', 'personal-website'); ?>
                            </label>
                            <p class="theme-options-help"><?php _e('Display the portfolio section on the front page.', 'personal-website'); ?></p>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="portfolio_section_title"><?php _e('Portfolio Section Title', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="portfolio_section_title" 
                                name="portfolio_section_title" 
                                value="<?php echo esc_attr($portfolio_section_title); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('Featured Projects', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Title for the portfolio section on the front page.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="portfolio_section_description"><?php _e('Portfolio Section Description', 'personal-website'); ?></label>
                            <textarea 
                                id="portfolio_section_description" 
                                name="portfolio_section_description" 
                                class="theme-options-textarea"
                                rows="3"
                                placeholder="<?php _e('Here are some of the projects I\'ve worked on recently', 'personal-website'); ?>"
                            ><?php echo esc_textarea($portfolio_section_description); ?></textarea>
                            <p class="theme-options-help"><?php _e('Description under the portfolio section title.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="portfolio_posts_count"><?php _e('Number of Portfolio Items', 'personal-website'); ?></label>
                            <input 
                                type="number" 
                                id="portfolio_posts_count" 
                                name="portfolio_posts_count" 
                                value="<?php echo esc_attr($portfolio_posts_count); ?>" 
                                class="theme-options-input"
                                min="1" 
                                max="12" 
                                step="1"
                                placeholder="6"
                            />
                            <p class="theme-options-help"><?php _e('How many portfolio items to display (1-12).', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="featured_portfolio_1"><?php _e('Featured Portfolio Item 1 (ID)', 'personal-website'); ?></label>
                            <input 
                                type="number" 
                                id="featured_portfolio_1" 
                                name="featured_portfolio_1" 
                                value="<?php echo esc_attr($featured_portfolio_1); ?>" 
                                class="theme-options-input"
                                step="1"
                                placeholder=""
                            />
                            <p class="theme-options-help"><?php _e('Enter portfolio post ID for first featured item. Leave empty for automatic selection.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="featured_portfolio_2"><?php _e('Featured Portfolio Item 2 (ID)', 'personal-website'); ?></label>
                            <input 
                                type="number" 
                                id="featured_portfolio_2" 
                                name="featured_portfolio_2" 
                                value="<?php echo esc_attr($featured_portfolio_2); ?>" 
                                class="theme-options-input"
                                step="1"
                                placeholder=""
                            />
                            <p class="theme-options-help"><?php _e('Enter portfolio post ID for second featured item. Leave empty for automatic selection.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="featured_portfolio_3"><?php _e('Featured Portfolio Item 3 (ID)', 'personal-website'); ?></label>
                            <input 
                                type="number" 
                                id="featured_portfolio_3" 
                                name="featured_portfolio_3" 
                                value="<?php echo esc_attr($featured_portfolio_3); ?>" 
                                class="theme-options-input"
                                step="1"
                                placeholder=""
                            />
                            <p class="theme-options-help"><?php _e('Enter portfolio post ID for third featured item. Leave empty for automatic selection.', 'personal-website'); ?></p>
                        </div>
                    </div>

                    <!-- Latest Posts Section -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-admin-post"></span><?php _e('Latest Posts Section', 'personal-website'); ?></h2>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="blog_section_title"><?php _e('Blog Section Title', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="blog_section_title" 
                                name="blog_section_title" 
                                value="<?php echo esc_attr($blog_section_title); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('Latest Blog Posts', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Title for the blog section on the front page.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="blog_section_description"><?php _e('Blog Section Description', 'personal-website'); ?></label>
                            <textarea 
                                id="blog_section_description" 
                                name="blog_section_description" 
                                class="theme-options-textarea"
                                rows="3"
                                placeholder="<?php _e('Insights and tutorials about software development and technology', 'personal-website'); ?>"
                            ><?php echo esc_textarea($blog_section_description); ?></textarea>
                            <p class="theme-options-help"><?php _e('Description under the blog section title.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="featured_post_1"><?php _e('Featured Post 1 (ID)', 'personal-website'); ?></label>
                            <input 
                                type="number" 
                                id="featured_post_1" 
                                name="featured_post_1" 
                                value="<?php echo esc_attr($featured_post_1); ?>" 
                                class="theme-options-input"
                                step="1"
                                placeholder=""
                            />
                            <p class="theme-options-help"><?php _e('Enter the post ID for the first featured post. Leave empty for no post.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="featured_post_2"><?php _e('Featured Post 2 (ID)', 'personal-website'); ?></label>
                            <input 
                                type="number" 
                                id="featured_post_2" 
                                name="featured_post_2" 
                                value="<?php echo esc_attr($featured_post_2); ?>" 
                                class="theme-options-input"
                                step="1"
                                placeholder=""
                            />
                            <p class="theme-options-help"><?php _e('Enter the post ID for the second featured post. Leave empty for no post.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="featured_post_3"><?php _e('Featured Post 3 (ID)', 'personal-website'); ?></label>
                            <input 
                                type="number" 
                                id="featured_post_3" 
                                name="featured_post_3" 
                                value="<?php echo esc_attr($featured_post_3); ?>" 
                                class="theme-options-input"
                                step="1"
                                placeholder=""
                            />
                            <p class="theme-options-help"><?php _e('Enter the post ID for the third featured post. Leave empty for no post.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <p class="theme-options-help">
                                <strong><?php _e('💡 How to find Post IDs:', 'personal-website'); ?></strong><br>
                                <?php _e('Go to Posts > All Posts, hover over a post title, and look at the URL in your browser\'s status bar. The ID will be shown as "post=123".', 'personal-website'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-email"></span><?php _e('Contact Section', 'personal-website'); ?></h2>
                        </div>
                        
                        <div class="theme-options-field">
                            <label for="contact_section_title"><?php _e('Contact Section Title', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="contact_section_title" 
                                name="contact_section_title" 
                                value="<?php echo esc_attr($contact_section_title); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('Let\'s Work Together', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Main heading for the contact section on the front page.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="contact_section_description"><?php _e('Contact Section Description', 'personal-website'); ?></label>
                            <textarea 
                                id="contact_section_description" 
                                name="contact_section_description" 
                                class="theme-options-textarea"
                                rows="3"
                                placeholder="<?php _e('Ready to start your next project? Let\'s discuss how I can help bring your ideas to life', 'personal-website'); ?>"
                            ><?php echo esc_textarea($contact_section_description); ?></textarea>
                            <p class="theme-options-help"><?php _e('Subtitle/description under the main heading.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="front_contact_email"><?php _e('Contact Email', 'personal-website'); ?></label>
                            <input 
                                type="email" 
                                id="front_contact_email" 
                                name="front_contact_email" 
                                value="<?php echo esc_attr($front_contact_email); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('your@email.com', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Email address for the front page contact section. Leave empty to use general contact email from Customizer.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="front_contact_phone"><?php _e('Contact Phone', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="front_contact_phone" 
                                name="front_contact_phone" 
                                value="<?php echo esc_attr($front_contact_phone); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('+1 (555) 123-4567', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Phone number for the front page contact section. Leave empty to use general contact phone from Customizer.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="front_contact_location"><?php _e('Contact Location', 'personal-website'); ?></label>
                            <input 
                                type="text" 
                                id="front_contact_location" 
                                name="front_contact_location" 
                                value="<?php echo esc_attr($front_contact_location); ?>" 
                                class="theme-options-input"
                                placeholder="<?php _e('Jakarta, Indonesia', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Location for the front page contact section. Leave empty to use general contact location from Customizer.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="contact_cta_message"><?php _e('Contact CTA Message', 'personal-website'); ?></label>
                            <textarea 
                                id="contact_cta_message" 
                                name="contact_cta_message" 
                                class="theme-options-textarea"
                                rows="5"
                                placeholder="<?php _e('I\'m currently available for freelance work and new opportunities...', 'personal-website'); ?>"
                            ><?php echo esc_textarea($contact_cta_message); ?></textarea>
                            <p class="theme-options-help"><?php _e('Call-to-action message in the contact section right panel.', 'personal-website'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Footer Tab -->
                <div class="theme-options-tab-content" id="tab-footer">
                    <!-- Footer Brand -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-admin-appearance"></span><?php _e('Footer Brand', 'personal-website'); ?></h2>
                        </div>
                        <div class="theme-options-field">
                            <label for="footer_brand_title"><?php _e('Brand Title', 'personal-website'); ?></label>
                            <input
                                type="text"
                                id="footer_brand_title"
                                name="footer_brand_title"
                                value="<?php echo esc_attr($footer_brand_title); ?>"
                                class="theme-options-input"
                                placeholder="<?php _e('Your Brand / Site Name', 'personal-website'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Footer brand title shown in the footer heading.', 'personal-website'); ?></p>
                        </div>
                        <div class="theme-options-field">
                            <label for="footer_brand_description"><?php _e('Brand Description', 'personal-website'); ?></label>
                            <textarea
                                id="footer_brand_description"
                                name="footer_brand_description"
                                class="theme-options-textarea"
                                rows="3"
                                placeholder="<?php _e('Short description about you/brand', 'personal-website'); ?>"
                            ><?php echo esc_textarea($footer_brand_description); ?></textarea>
                            <p class="theme-options-help"><?php _e('Short description displayed beneath the brand title.', 'personal-website'); ?></p>
                        </div>
                    </div>

                    <!-- Footer Bottom -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-editor-insertmore"></span><?php _e('Footer Bottom', 'personal-website'); ?></h2>
                        </div>
                        <div class="theme-options-field">
                            <label for="footer_copyright"><?php _e('Copyright Text (HTML allowed)', 'personal-website'); ?></label>
                            <input
                                type="text"
                                id="footer_copyright"
                                name="footer_copyright"
                                value="<?php echo esc_attr($footer_copyright); ?>"
                                class="theme-options-input"
                                placeholder="<?php echo esc_attr('&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '.'); ?>"
                            />
                            <p class="theme-options-help"><?php _e('Displayed as the main copyright line in the footer.', 'personal-website'); ?></p>
                        </div>
                        <div class="theme-options-field">
                            <label for="footer_link_1_text"><?php _e('Footer Link 1 Text', 'personal-website'); ?></label>
                            <input type="text" id="footer_link_1_text" name="footer_link_1_text" value="<?php echo esc_attr($footer_link_1_text); ?>" class="theme-options-input" />
                        </div>
                        <div class="theme-options-field">
                            <label for="footer_link_1_url"><?php _e('Footer Link 1 URL', 'personal-website'); ?></label>
                            <input type="url" id="footer_link_1_url" name="footer_link_1_url" value="<?php echo esc_attr($footer_link_1_url); ?>" class="theme-options-input" />
                        </div>
                        <div class="theme-options-field">
                            <label for="footer_link_2_text"><?php _e('Footer Link 2 Text', 'personal-website'); ?></label>
                            <input type="text" id="footer_link_2_text" name="footer_link_2_text" value="<?php echo esc_attr($footer_link_2_text); ?>" class="theme-options-input" />
                        </div>
                        <div class="theme-options-field">
                            <label for="footer_link_2_url"><?php _e('Footer Link 2 URL', 'personal-website'); ?></label>
                            <input type="url" id="footer_link_2_url" name="footer_link_2_url" value="<?php echo esc_attr($footer_link_2_url); ?>" class="theme-options-input" />
                        </div>
                        <div class="theme-options-field">
                            <label for="footer_made_with_text"><?php _e('Made With Text', 'personal-website'); ?></label>
                            <input type="text" id="footer_made_with_text" name="footer_made_with_text" value="<?php echo esc_attr($footer_made_with_text); ?>" class="theme-options-input" placeholder="<?php _e('Made with ❤️ by Your Name', 'personal-website'); ?>" />
                            <p class="theme-options-help"><?php _e('Short signature line shown at the very bottom.', 'personal-website'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div class="theme-options-tab-content" id="tab-security">
                    <!-- Cloudflare Turnstile Section -->
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-shield"></span><?php _e('Cloudflare Turnstile', 'personal-website'); ?></h2>
                        </div>
                        <p class="theme-options-help" style="margin-top:0;">
                            <?php echo esc_html__('Cloudflare Turnstile is a simple and free CAPTCHA replacement solution that delivers better experiences and greater security for your users.', 'personal-website'); ?>
                        </p>

                        <div class="theme-options-field">
                            <label for="enable_turnstile">
                                <input type="checkbox" id="enable_turnstile" name="enable_turnstile" value="1" <?php checked($enable_turnstile); ?> />
                                <?php _e('Enable Cloudflare Turnstile (login and comments)', 'personal-website'); ?>
                            </label>
                            <p class="theme-options-help"><?php _e('When enabled, a Turnstile challenge is shown on the WordPress login form and on comment forms to prevent spam and abuse.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="turnstile_site_key"><?php _e('Site Key', 'personal-website'); ?></label>
                            <input type="text" id="turnstile_site_key" name="turnstile_site_key" value="<?php echo esc_attr($turnstile_site_key); ?>" class="theme-options-input" placeholder="<?php esc_attr_e('0x00000000000000000000000000000000AA', 'personal-website'); ?>" />
                            <p class="theme-options-help"><?php _e('Get your Site Key from Cloudflare Turnstile dashboard.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="turnstile_secret_key"><?php _e('Secret Key', 'personal-website'); ?></label>
                            <input type="text" id="turnstile_secret_key" name="turnstile_secret_key" value="<?php echo esc_attr($turnstile_secret); ?>" class="theme-options-input" placeholder="<?php esc_attr_e('0x00000000000000000000000000000000BB', 'personal-website'); ?>" />
                            <p class="theme-options-help"><?php _e('Your server-side verification key. Keep this private.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-notice" style="margin-top:16px;">
                            <p><?php _e('Note: If enabled but keys are missing, Turnstile will not be enforced.', 'personal-website'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Image Optimization Tab -->
                <div class="theme-options-tab-content" id="tab-image-optimization">
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-performance"></span><?php _e('Media Conversion & Compression', 'personal-website'); ?></h2>
                        </div>

                        <?php
                        $enable_image_optimization = (bool) get_option('enable_image_optimization', false);
                        $image_opt_target_format = get_option('image_opt_target_format', 'webp');
                        $image_opt_convert_original = (bool) get_option('image_opt_convert_original', false);
                        $image_opt_max_mp = absint(get_option('image_opt_max_mp', 12));
                        // Detect Imagick and format support early (used to toggle input disabled state)
                        $imagick_available = class_exists('Imagick');
                        $imagick_ver = '';
                        $webp_support = false;
                        $avif_support = false;
                        if ($imagick_available) {
                            if (method_exists('Imagick', 'getVersion')) {
                                $ver = Imagick::getVersion();
                                if (is_array($ver) && !empty($ver['versionString'])) {
                                    $imagick_ver = $ver['versionString'];
                                }
                            }
                            if (method_exists('Imagick', 'queryFormats')) {
                                $webp_support = !empty(Imagick::queryFormats('WEBP'));
                                $avif_support = !empty(Imagick::queryFormats('AVIF'));
                            }
                        }
                        ?>
                        <div class="theme-options-field">
                            <label for="enable_image_optimization">
                                <input type="checkbox" id="enable_image_optimization" name="enable_image_optimization" value="1" <?php checked($enable_image_optimization); ?> <?php disabled(!$imagick_available); ?> />
                                <?php _e('Enable image optimization (create WebP/AVIF on upload)', 'personal-website'); ?>
                            </label>
                            <p class="theme-options-help"><?php _e('Requires Imagick with WebP/AVIF support on the server. Non-supported formats will be skipped silently.', 'personal-website'); ?></p>
                            <?php $gd_avif_support = function_exists('imageavif'); ?>
                            <div style="margin-top:8px; padding:10px; border:1px solid #e5e7eb; border-radius:6px; background:#fafafa;">
                                <strong><?php _e('Imagick Status:', 'personal-website'); ?></strong>
                                <?php if ($imagick_available): ?>
                                    <span style="color:#15803d; font-weight:600;">Available</span>
                                    <?php if ($imagick_ver): ?>
                                        <span style="color:#6b7280;">(<?php echo esc_html($imagick_ver); ?>)</span>
                                    <?php endif; ?><br>
                                    <span>WebP: <strong style="color:<?php echo $webp_support ? '#15803d' : '#b91c1c'; ?>;"><?php echo $webp_support ? __('Yes','personal-website') : __('No','personal-website'); ?></strong></span>
                                    &nbsp;|&nbsp;
                                    <span>AVIF: <strong style="color:<?php echo $avif_support ? '#15803d' : '#b91c1c'; ?>;"><?php echo $avif_support ? __('Yes','personal-website') : __('No','personal-website'); ?></strong></span>
                                <?php else: ?>
                                    <span style="color:#b91c1c; font-weight:600;">Not Installed</span>
                                    <p class="theme-options-help" style="margin:6px 0 0 0;"><?php _e('Install and enable the PHP Imagick extension for best results (WebP/AVIF generation).', 'personal-website'); ?></p>
                                <?php endif; ?>
                                <div style="margin-top:6px; color:#374151;">
                                    <strong>GD AVIF:</strong>
                                    <span style="color:<?php echo $gd_avif_support ? '#15803d' : '#b91c1c'; ?>; font-weight:600;"><?php echo $gd_avif_support ? __('Yes','personal-website') : __('No','personal-website'); ?></span>
                                    <?php if (!$gd_avif_support): ?>
                                        <span class="theme-options-help" style="margin-left:6px; color:#6b7280;">(<?php _e('Requires PHP 8.1+ with GD compiled with libavif.', 'personal-website'); ?>)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="theme-options-field">
                            <label style="display:block; font-weight:600; margin-bottom:6px;">
                                <?php _e('Conversion format (choose one)', 'personal-website'); ?>
                            </label>
                            <label style="margin-right:12px;">
                                <input type="radio" name="image_opt_target_format" value="webp" <?php checked($image_opt_target_format !== 'avif'); ?> /> WEBP
                            </label>
                            <label>
                                <input type="radio" name="image_opt_target_format" value="avif" <?php checked($image_opt_target_format === 'avif'); ?> /> AVIF
                            </label>
                            <p class="theme-options-help"><?php _e('Only one format will be created per image. AVIF typically offers better compression but requires client support.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <?php $force_modern = (bool) get_option('image_opt_force_modern_only', false); ?>
                            <label>
                                <input type="checkbox" name="image_opt_force_modern_only" value="1" <?php checked($force_modern); ?> />
                                <?php _e('Force modern format only (no JPEG fallback)', 'personal-website'); ?>
                            </label>
                            <p class="theme-options-help"><?php _e('Renders only AVIF/WebP on the frontend when a variant exists. Older browsers that lack support may not display images.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <?php $convert_quality = absint(get_option('image_opt_convert_quality', 85)); ?>
                            <label for="image_opt_convert_quality"><?php _e('Conversion quality (1–100)', 'personal-website'); ?></label>
                            <input type="number" id="image_opt_convert_quality" name="image_opt_convert_quality" min="1" max="100" value="<?php echo esc_attr($convert_quality); ?>" class="theme-options-input" style="width:120px;" />
                            <p class="theme-options-help"><?php _e('Applies to stored AVIF/WebP variants generated on upload/bulk. Lower is smaller files (e.g., 75).', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label>
                                <input type="checkbox" name="image_opt_convert_original" value="1" <?php checked($image_opt_convert_original); ?> />
                                <?php _e('Also convert original file (may be slow).', 'personal-website'); ?>
                            </label>
                            <p class="theme-options-help"><?php _e('If unchecked, only WordPress-generated sizes are converted. This avoids very large originals that can be slow to encode.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="image_opt_max_mp"><?php _e('Max megapixels to convert', 'personal-website'); ?></label>
                            <input type="number" id="image_opt_max_mp" name="image_opt_max_mp" min="1" max="100" value="<?php echo esc_attr($image_opt_max_mp); ?>" class="theme-options-input" style="width:120px;" />
                            <p class="theme-options-help"><?php _e('Images larger than this (width x height / 1,000,000) will be skipped to prevent timeouts. Default: 12 MP.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <button type="button" id="image-opt-bulk-btn" class="button button-secondary">
                                <?php _e('Run Bulk Conversion (AJAX)', 'personal-website'); ?>
                            </button>
                            <span id="image-opt-bulk-status" style="margin-left:8px; color:#6b7280;"></span>
                            <div id="image-opt-bulk-logs" style="display:none; margin-top:8px; max-height:260px; overflow:auto; background:#fff; border:1px solid #e5e7eb; border-radius:6px;"></div>
                            <p class="theme-options-help"><?php _e('Progresses in small steps to avoid timeouts. Keep this page open until complete.', 'personal-website'); ?></p>
                        </div>
                    </div>

                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-cloud"></span><?php _e('Cloudflare CDN Integration (optional)', 'personal-website'); ?></h2>
                        </div>

                        <?php
                        $enable_cloudflare_cdn = (bool) get_option('enable_cloudflare_cdn', false);
                        $cloudflare_cdn_domain = get_option('cloudflare_cdn_domain', '');
                        $cloudflare_image_quality = absint(get_option('cloudflare_image_quality', 85));
                        ?>

                        <div class="theme-options-field">
                            <label for="enable_cloudflare_cdn">
                                <input type="checkbox" id="enable_cloudflare_cdn" name="enable_cloudflare_cdn" value="1" <?php checked($enable_cloudflare_cdn); ?> />
                                <?php _e('Enable Cloudflare CDN image rewriting', 'personal-website'); ?>
                            </label>
                            <p class="theme-options-help"><?php _e('Rewrites image URLs to use Cloudflare Image Resizing: format=auto and quality control. Ensure your site or CDN subdomain is proxied by Cloudflare.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="cloudflare_cdn_domain"><?php _e('Cloudflare CDN Domain (optional)', 'personal-website'); ?></label>
                            <input type="text" id="cloudflare_cdn_domain" name="cloudflare_cdn_domain" value="<?php echo esc_attr($cloudflare_cdn_domain); ?>" class="theme-options-input" placeholder="cdn.example.com" />
                            <p class="theme-options-help"><?php _e('Leave empty to use your primary domain. Provide a proxied subdomain if you use a dedicated CDN hostname.', 'personal-website'); ?></p>
                        </div>

                        <div class="theme-options-field">
                            <label for="cloudflare_image_quality"><?php _e('Cloudflare Quality (1–100)', 'personal-website'); ?></label>
                            <input type="number" id="cloudflare_image_quality" name="cloudflare_image_quality" value="<?php echo esc_attr($cloudflare_image_quality); ?>" min="1" max="100" class="theme-options-input" />
                        </div>
                    </div>
                </div>

                <!-- Asset Optimization Tab -->
                <div class="theme-options-tab-content" id="tab-asset-optimization">
                    <div class="theme-options-section">
                        <div class="theme-options-section-header">
                            <h2><span class="dashicons dashicons-editor-code"></span><?php _e('Asset Minification', 'personal-website'); ?></h2>
                        </div>
                        <?php 
                        $enable_asset_min = (bool) get_option('enable_asset_minification', false);
                        $enable_asset_min_js = (bool) get_option('enable_asset_minify_js', false);
                        $mm_available = function_exists('asset_opt_mm_available') && asset_opt_mm_available();
                        ?>
                        <div class="theme-options-field" style="margin-bottom:8px;">
                            <strong><?php _e('Minifier engine:', 'personal-website'); ?></strong>
                            <?php if ($mm_available): ?>
                                <span style="color:#15803d; font-weight:600;">MatthiasMullie/Minify</span>
                            <?php else: ?>
                                <span style="color:#b45309; font-weight:600;">Fallback (safe packer)</span>
                                <p class="theme-options-help" style="margin-top:6px;">
                                    <?php _e('For best results, install the Composer package:', 'personal-website'); ?>
                                    <code>composer require matthiasmullie/minify</code>
                                    <br><?php _e('Place vendor/autoload.php in project or theme and it will be detected automatically.', 'personal-website'); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="theme-options-field">
                            <label>
                                <input type="checkbox" name="enable_asset_minification" value="1" <?php checked($enable_asset_min); ?> />
                                <?php _e('Minify theme CSS/JS automatically', 'personal-website'); ?>
                            </label>
                            <p class="theme-options-help"><?php _e('Minifies theme-local CSS/JS and serves them from an uploads cache. External/CDN files are left untouched.', 'personal-website'); ?></p>
                        </div>
                        <div class="theme-options-field">
                            <label>
                                <input type="checkbox" name="enable_asset_minify_js" value="1" <?php checked($enable_asset_min_js); ?> />
                                <?php _e('Also minify JS (experimental, conservative)', 'personal-website'); ?>
                            </label>
                            <p class="theme-options-help"><?php _e('JS minifier is very conservative to avoid syntax issues. Disable if you see console errors.', 'personal-website'); ?></p>
                        </div>
                        <div class="theme-options-field">
                            <button type="submit" name="purge_asset_min_cache" value="1" class="button">
                                <?php _e('Purge Minified Cache', 'personal-website'); ?>
                            </button>
                            <p class="theme-options-help"><?php _e('Deletes cached minified files from uploads/rangin-cache/min. They will be regenerated on next load.', 'personal-website'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="theme-options-actions">
                    <?php submit_button(__('Save Settings', 'personal-website'), 'primary', 'submit', false, array('class' => 'theme-options-submit')); ?>
                </div>
            </form>
            <div class="theme-options-trademark" style="margin-top:24px; padding:16px; border-top:1px solid #e5e7eb; text-align:center; color:#555;">
                &copy; <?php echo date('Y'); ?>
                <a href="https://budhilaw.com" target="_blank" rel="noopener noreferrer" style="text-decoration:none; font-weight:600;">
                    Ericsson Budhilaw
                </a>.
                All rights reserved. <br /> <br /> Inquiries:
                <a href="mailto:hello@budhilaw.com" style="text-decoration:none; font-weight:600;">hello@budhilaw.com</a>.
            </div>
        </div>
    </div>
    <?php
}

/**
 * Enqueue Theme Options admin styles
 */
function personal_website_theme_options_admin_styles($hook) {
    // Only load on Theme Options page
    if ($hook !== 'toplevel_page_theme-options') {
        return;
    }
    
    // Enqueue the separate compiled admin stylesheet
    $admin_min = THEME_DIR . '/assets/css/admin-theme-options.min.css';
    $admin_url = file_exists($admin_min) ? THEME_URL . '/assets/css/admin-theme-options.min.css'
                                         : THEME_URL . '/assets/css/admin-theme-options.css';
    wp_enqueue_style('theme-options-admin', $admin_url, array(), THEME_VERSION);
}
add_action('admin_enqueue_scripts', 'personal_website_theme_options_admin_styles');

/**
 * Add viewport meta tag to Theme Options page
 */
function personal_website_theme_options_viewport() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'toplevel_page_theme-options') {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />' . "\n";
    }
}
add_action('admin_head', 'personal_website_theme_options_viewport');

/**
 * Enqueue scripts for Theme Options page
 */
function personal_website_theme_options_enqueue_scripts($hook) {
    // Only load on Theme Options page
    if ($hook !== 'toplevel_page_theme-options') {
        return;
    }
    
    // Make sure jQuery and WordPress media scripts are loaded
    wp_enqueue_script('jquery');
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'personal_website_theme_options_enqueue_scripts');

/**
 * Add JavaScript directly to Theme Options page
 */
function personal_website_theme_options_scripts() {
    global $pagenow;
    
    // Check if we're on the Theme Options page
    if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'theme-options') {
        ?>
        <script type="text/javascript">
        function initThemeOptionsTabs() {
            var tabs = document.querySelectorAll('.theme-options-tab');
            var tabContents = document.querySelectorAll('.theme-options-tab-content');
            
            if (tabs.length === 0) return;
            
            tabs.forEach(function(tab) {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    var targetTab = this.getAttribute('data-tab');
                    
                    // Remove active from all
                    tabs.forEach(function(t) { t.classList.remove('active'); });
                    tabContents.forEach(function(c) { c.classList.remove('active'); });
                    
                    // Add active to clicked tab and content
                    this.classList.add('active');
                    var targetContent = document.getElementById('tab-' + targetTab);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });
        }

        function initMediaUploader() {
            // WordPress Media Uploader functionality
            jQuery(document).ready(function($) {
                var mediaUploader;
                
                $('.upload-media-button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var targetField = button.attr('data-target');
                    
                    // Create the media frame
                    mediaUploader = wp.media.frames.file_frame = wp.media({
                        title: 'Choose Image',
                        button: {
                            text: 'Choose Image'
                        },
                        multiple: false,
                        library: {
                            type: 'image'
                        }
                    });
                    
                    // When an image is selected, run a callback
                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        var imageUrl = attachment.url;
                        
                        // Set the image URL in the hidden input
                        $('#' + targetField).val(imageUrl);
                        
                        // Update the preview
                        var previewContainer = button.closest('.theme-options-media-upload').find('.media-preview');
                        previewContainer.html('<img src="' + imageUrl + '" alt="Preview">');
                        
                        // Show remove button if not already present
                        if (!button.siblings('.remove-media-button').length) {
                            button.after('<button type="button" class="button remove-media-button" data-target="' + targetField + '">Remove Image</button>');
                        }
                    });
                    
                    // Open the media uploader
                    mediaUploader.open();
                });
                
                // Remove image functionality
                $(document).on('click', '.remove-media-button', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var targetField = button.attr('data-target');
                    
                    // Clear the hidden input
                    $('#' + targetField).val('');
                    
                    // Update the preview to show placeholder
                    var previewContainer = button.closest('.theme-options-media-upload').find('.media-preview');
                    previewContainer.html('<div class="no-image-placeholder"><span class="dashicons dashicons-format-image"></span></div>');
                    
                    // Remove the remove button
                    button.remove();
                });
            });
        }

        function initImageOptBulk() {
            var btn = document.getElementById('image-opt-bulk-btn');
            if (!btn) return;
            var statusEl = document.getElementById('image-opt-bulk-status');
            var logsEl = document.getElementById('image-opt-bulk-logs');
            var running = false;

            function step() {
                if (!running) return;
                statusEl.textContent = 'Processing...';
                var fd = new FormData();
                fd.append('action', 'image_opt_bulk_step');
                fd.append('limit', 2);
                fd.append('nonce', '<?php echo wp_create_nonce('image_opt_nonce'); ?>');

                fetch(ajaxurl, { method: 'POST', credentials: 'same-origin', body: fd })
                    .then(r => r.json())
                    .then(function(res) {
                        if (!res || !res.success) {
                            statusEl.textContent = 'Error. Stopping.';
                            running = false;
                            return;
                        }
                        if (res.data && res.data.logs && res.data.logs.length) {
                            logsEl.style.display = 'block';
                            var ul = logsEl.querySelector('ul');
                            if (!ul) { ul = document.createElement('ul'); ul.style.margin='0'; ul.style.padding='10px 14px'; ul.style.listStyle='disc inside'; logsEl.appendChild(ul); }
                            res.data.logs.forEach(function(line){ var li=document.createElement('li'); li.textContent=line; ul.appendChild(li); });
                            logsEl.scrollTop = logsEl.scrollHeight;
                        }
                        if (res.data && res.data.remaining > 0 && running) {
                            statusEl.textContent = 'Remaining: ' + res.data.remaining;
                            setTimeout(step, 200);
                        } else {
                            statusEl.textContent = 'Done';
                            running = false;
                        }
                    })
                    .catch(function(){ statusEl.textContent = 'Request failed'; running=false; });
            }

            btn.addEventListener('click', function(){
                if (running) return; running = true; logsEl.innerHTML=''; logsEl.style.display='none'; statusEl.textContent='Starting...'; step();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initThemeOptionsTabs();
                initMediaUploader();
                initImageOptBulk();
            });
        } else {
            initThemeOptionsTabs();
            initMediaUploader();
            initImageOptBulk();
        }
        </script>
        <?php
    }
}
add_action('admin_head', 'personal_website_theme_options_scripts');
