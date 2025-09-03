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
        __('Theme Options', 'personal-website'),     // Page title
        __('Theme Options', 'personal-website'),     // Menu title
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
    // Handle form submissions
    if (isset($_POST['submit']) && check_admin_referer('theme_options_nonce')) {
        $success_messages = array();
        
        // Site Information
        if (isset($_POST['site_title'])) {
            update_option('blogname', sanitize_text_field($_POST['site_title']));
            $success_messages[] = __('Site title updated.', 'personal-website');
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
                </div>

                <div class="theme-options-actions">
                    <?php submit_button(__('Save Settings', 'personal-website'), 'primary', 'submit', false, array('class' => 'theme-options-submit')); ?>
                </div>
            </form>
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
    wp_enqueue_style(
        'theme-options-admin',
        THEME_URL . '/assets/css/admin-theme-options.css',
        array(),
        THEME_VERSION
    );
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
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initThemeOptionsTabs();
                initMediaUploader();
            });
        } else {
            initThemeOptionsTabs();
            initMediaUploader();
        }
        </script>
        <?php
    }
}
add_action('admin_head', 'personal_website_theme_options_scripts');


