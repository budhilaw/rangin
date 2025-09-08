<?php
/**
 * Personal Website Theme Functions
 * 
 * @package PersonalWebsite
 * @author Ericsson Budhilaw
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('THEME_VERSION', '1.0.0');
define('THEME_DIR', get_template_directory());
define('THEME_URL', get_template_directory_uri());

/**
 * Include theme setup and configuration files
 */
require_once THEME_DIR . '/inc/theme-setup.php';
require_once THEME_DIR . '/inc/enqueue-scripts.php';
require_once THEME_DIR . '/inc/seo-optimization.php';
require_once THEME_DIR . '/inc/performance.php';
require_once THEME_DIR . '/inc/nav-walker.php';
require_once THEME_DIR . '/inc/helper-functions.php';
require_once THEME_DIR . '/inc/custom-widgets.php';
require_once THEME_DIR . '/inc/customizer.php';
require_once THEME_DIR . '/inc/widgets.php';
require_once THEME_DIR . '/inc/admin-customization.php';
require_once THEME_DIR . '/inc/comment-walker.php';
require_once THEME_DIR . '/inc/portfolio-post-type.php';
require_once THEME_DIR . '/inc/image-optimization.php';
require_once THEME_DIR . '/inc/asset-optimization.php';
require_once THEME_DIR . '/inc/auto-pages.php';
require_once THEME_DIR . '/inc/demo-content.php';
require_once THEME_DIR . '/inc/security.php';
