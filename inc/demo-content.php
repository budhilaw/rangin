<?php
/**
 * Demo content: create Portfolio categories and items on activation
 */

if (!defined('ABSPATH')) { exit; }

function rangin_ensure_portfolio_category($name, $slug, $description = '') {
    $term = get_term_by('slug', $slug, 'portfolio_category');
    if ($term && !is_wp_error($term)) {
        return (int) $term->term_id;
    }
    $result = wp_insert_term($name, 'portfolio_category', array(
        'slug' => $slug,
        'description' => $description,
    ));
    if (is_wp_error($result)) {
        $existing = get_term_by('name', $name, 'portfolio_category');
        return $existing && !is_wp_error($existing) ? (int) $existing->term_id : 0;
    }
    return (int) $result['term_id'];
}

function rangin_ensure_portfolio_item($title, $content, $category_slug, $technologies = '', $demo_link = '', $github_link = '') {
    $existing = get_page_by_title($title, OBJECT, 'portfolio');
    if ($existing && isset($existing->ID)) {
        return (int) $existing->ID;
    }
    // Create the portfolio item
    $post_id = wp_insert_post(array(
        'post_type'    => 'portfolio',
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => 'publish',
    ));
    if (is_wp_error($post_id) || !$post_id) { return 0; }

    // Assign taxonomy term
    if (!empty($category_slug)) {
        wp_set_object_terms($post_id, $category_slug, 'portfolio_category', false);
    }

    // Set meta fields used by theme
    if (!empty($technologies)) {
        update_post_meta($post_id, '_portfolio_technologies', $technologies);
    }
    if (!empty($demo_link)) {
        update_post_meta($post_id, '_portfolio_demo_link', esc_url_raw($demo_link));
    }
    if (!empty($github_link)) {
        update_post_meta($post_id, '_portfolio_github_link', esc_url_raw($github_link));
    }

    return (int) $post_id;
}

function rangin_create_demo_portfolios_on_activation() {
    // Ensure CPT and taxonomy are registered in this request
    if (function_exists('register_portfolio_post_type')) {
        register_portfolio_post_type();
    }
    if (function_exists('register_portfolio_categories_taxonomy')) {
        register_portfolio_categories_taxonomy();
    }

    // Create categories
    $frontend_term_id = rangin_ensure_portfolio_category(__('Frontend', 'rangin'), 'frontend', __('Frontend projects', 'rangin'));
    $backend_term_id  = rangin_ensure_portfolio_category(__('Backend', 'rangin'), 'backend', __('Backend services', 'rangin'));
    $mobile_term_id   = rangin_ensure_portfolio_category(__('Mobile', 'rangin'), 'mobile', __('Mobile apps', 'rangin'));

    // Only create demo items if there are no portfolio posts yet
    $existing = get_posts(array('post_type' => 'portfolio', 'posts_per_page' => 1, 'post_status' => 'any'));
    if (!empty($existing)) { return; }

    // Create demo items
    rangin_ensure_portfolio_item(
        __('Frontend Project (ReactJS)', 'rangin'),
        __('A sample ReactJS project showcasing a modern, responsive UI and component-driven architecture.', 'rangin'),
        'frontend',
        'ReactJS, TailwindCSS',
        home_url('/'),
        'https://github.com/'
    );

    rangin_ensure_portfolio_item(
        __('Backend Service (Go)', 'rangin'),
        __('A sample Go microservice with clean architecture, REST endpoints, and unit tests.', 'rangin'),
        'backend',
        'Go, REST, Docker',
        home_url('/'),
        'https://github.com/'
    );

    rangin_ensure_portfolio_item(
        __('Mobile App (Flutter)', 'rangin'),
        __('A sample Flutter app demonstrating multi-platform UI, state management, and theming.', 'rangin'),
        'mobile',
        'Flutter, Dart',
        home_url('/'),
        'https://github.com/'
    );
}

add_action('after_switch_theme', 'rangin_create_demo_portfolios_on_activation');

/**
 * Create a default Primary Menu with common links on activation
 */
function rangin_create_primary_menu_on_activation() {
    // Determine the Primary Menu to use or create
    $locations = get_theme_mod('nav_menu_locations');
    $menu_id = 0;
    if (is_array($locations) && !empty($locations['primary'])) {
        $menu_id = (int) $locations['primary'];
    } else {
        $menu_name = __('Primary Menu', 'rangin');
        $menu = wp_get_nav_menu_object($menu_name);
        if (!$menu) {
            $menu_id_created = wp_create_nav_menu($menu_name);
            $menu = wp_get_nav_menu_object($menu_id_created);
        }
        if ($menu) {
            $menu_id = (int) $menu->term_id;
        }
    }
    if (!$menu_id) { return; }

    $items = wp_get_nav_menu_items($menu_id, array('update_post_term_cache' => false));

    // Resolve pages
    $home_id = (int) get_option('page_on_front');
    $blog_id = (int) get_option('page_for_posts');
    $about   = get_page_by_path('about', OBJECT, 'page');
    $contact = get_page_by_path('contact', OBJECT, 'page');
    $portfolio = get_page_by_path('portfolio', OBJECT, 'page');

    // Build desired items
    $to_add = array();
    if ($home_id) {
        $to_add[] = array('title' => __('Home', 'rangin'), 'object_id' => $home_id);
    } else {
        // Fallback to home URL as custom link
        $to_add[] = array('title' => __('Home', 'rangin'), 'custom' => home_url('/'));
    }
    if ($blog_id) {
        $to_add[] = array('title' => __('Blog', 'rangin'), 'object_id' => $blog_id);
    } else {
        $to_add[] = array('title' => __('Blog', 'rangin'), 'custom' => home_url('/blog/'));
    }
    if ($about)     { $to_add[] = array('title' => __('About', 'rangin'), 'object_id' => (int)$about->ID); }
    if ($portfolio) { $to_add[] = array('title' => __('Portfolio', 'rangin'), 'object_id' => (int)$portfolio->ID); }
    if ($contact)   { $to_add[] = array('title' => __('Contact', 'rangin'), 'object_id' => (int)$contact->ID); }

    if (empty($items)) {
        // Create items in desired order
        foreach ($to_add as $item) {
            if (!empty($item['object_id'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'     => $item['title'],
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => (int) $item['object_id'],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
            } elseif (!empty($item['custom'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'  => $item['title'],
                    'menu-item-url'    => esc_url_raw($item['custom']),
                    'menu-item-type'   => 'custom',
                    'menu-item-status' => 'publish',
                ));
            }
        }
    } else {
        // Reorder existing menu items to: Home, Blog, About, Portfolio, Contact
        $home_id = (int) get_option('page_on_front');
        $blog_id = (int) get_option('page_for_posts');
        $about   = get_page_by_path('about', OBJECT, 'page');
        $contact = get_page_by_path('contact', OBJECT, 'page');
        $portfolio = get_page_by_path('portfolio', OBJECT, 'page');

        $find_item = function($matcher) use ($items) {
            foreach ((array)$items as $it) {
                if (call_user_func($matcher, $it)) { return $it; }
            }
            return null;
        };

        $home_item = $find_item(function($it) use ($home_id) {
            return ($it->object === 'page' && (int)$it->object_id === $home_id) || untrailingslashit($it->url) === untrailingslashit(home_url('/'));
        });
        $blog_item = $find_item(function($it) use ($blog_id) {
            return ($it->object === 'page' && (int)$it->object_id === $blog_id) || untrailingslashit($it->url) === untrailingslashit(home_url('/blog/'));
        });
        $about_item = ($about) ? $find_item(function($it) use ($about) {
            return ($it->object === 'page' && (int)$it->object_id === (int)$about->ID);
        }) : null;
        $portfolio_item = ($portfolio) ? $find_item(function($it) use ($portfolio) {
            return ($it->object === 'page' && (int)$it->object_id === (int)$portfolio->ID);
        }) : null;
        $contact_item = ($contact) ? $find_item(function($it) use ($contact) {
            return ($it->object === 'page' && (int)$it->object_id === (int)$contact->ID);
        }) : null;

        $ordered = array_values(array_filter([
            $home_item, $blog_item, $about_item, $portfolio_item, $contact_item
        ]));

        if (!empty($ordered)) {
            $pos = 1;
            foreach ($ordered as $mi) {
                if ($mi && isset($mi->ID)) {
                    wp_update_post(array('ID' => (int)$mi->ID, 'menu_order' => $pos));
                    $pos++;
                }
            }
        }
    }

    // Assign to primary location
    $locations = get_theme_mod('nav_menu_locations');
    if (!is_array($locations)) { $locations = array(); }
    $locations['primary'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
}

add_action('after_switch_theme', 'rangin_create_primary_menu_on_activation');

/**
 * Create a default Footer Menu with Blog, About, Contact on activation
 */
function rangin_create_footer_menu_on_activation() {
    // If a footer menu location is already assigned, do nothing
    $locations = get_theme_mod('nav_menu_locations');
    if (is_array($locations) && !empty($locations['footer'])) {
        return;
    }

    // Find or create the menu by name
    $menu_name = __('Footer Menu', 'rangin');
    $menu = wp_get_nav_menu_object($menu_name);
    if (!$menu) {
        $menu_id = wp_create_nav_menu($menu_name);
        $menu = wp_get_nav_menu_object($menu_id);
    }
    if (!$menu) { return; }

    $menu_id = (int) $menu->term_id;

    // Check if the menu already has items; if yes, just assign it
    $items = wp_get_nav_menu_items($menu_id);
    if (empty($items)) {
        // Resolve target pages
        $blog_id = (int) get_option('page_for_posts');
        $about   = get_page_by_path('about', OBJECT, 'page');
        $contact = get_page_by_path('contact', OBJECT, 'page');

        $to_add = array();
        if ($blog_id) {
            $to_add[] = array('title' => __('Blog', 'rangin'), 'object_id' => $blog_id);
        } else {
            $to_add[] = array('title' => __('Blog', 'rangin'), 'custom' => home_url('/blog/'));
        }
        if ($about)   { $to_add[] = array('title' => __('About', 'rangin'),   'object_id' => (int)$about->ID); }
        if ($contact) { $to_add[] = array('title' => __('Contact', 'rangin'), 'object_id' => (int)$contact->ID); }

        foreach ($to_add as $item) {
            if (!empty($item['object_id'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'     => $item['title'],
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => (int) $item['object_id'],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
            } elseif (!empty($item['custom'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'  => $item['title'],
                    'menu-item-url'    => esc_url_raw($item['custom']),
                    'menu-item-type'   => 'custom',
                    'menu-item-status' => 'publish',
                ));
            }
        }
    }

    // Assign to footer location
    $locations = get_theme_mod('nav_menu_locations');
    if (!is_array($locations)) { $locations = array(); }
    $locations['footer'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
}

add_action('after_switch_theme', 'rangin_create_footer_menu_on_activation');

/**
 * Place default widgets into sidebars on activation
 */
function rangin_setup_default_widgets_on_activation() {
    // Prepare sidebars structure
    $sidebars = get_option('sidebars_widgets', array());
    if (!is_array($sidebars)) { $sidebars = array(); }
    if (!isset($sidebars['wp_inactive_widgets'])) { $sidebars['wp_inactive_widgets'] = array(); }
    if (!isset($sidebars['sidebar-1']) || !is_array($sidebars['sidebar-1'])) { $sidebars['sidebar-1'] = array(); }
    if (!isset($sidebars['footer-1']) || !is_array($sidebars['footer-1'])) { $sidebars['footer-1'] = array(); }

    // Helper to create a widget instance and return its unique id (id_base-index)
    $create_widget = function($id_base, $instance_values = array()) {
        $opt_key = 'widget_' . $id_base;
        $instances = get_option($opt_key, array());
        if (!is_array($instances)) { $instances = array(); }
        // Determine next numeric index
        $numeric_keys = array_filter(array_keys($instances), 'is_int');
        $next = 2; // WordPress convention often starts at 2 due to _multiwidget
        if (!empty($numeric_keys)) {
            $next = max($numeric_keys) + 1;
        }
        $instances[$next] = $instance_values;
        update_option($opt_key, $instances);
        return $id_base . '-' . $next;
    };

    // Always reset Blog Sidebar: move existing to Inactive, then add our defaults
    if (!empty($sidebars['sidebar-1'])) {
        foreach ($sidebars['sidebar-1'] as $wid) {
            if (!in_array($wid, $sidebars['wp_inactive_widgets'], true)) {
                $sidebars['wp_inactive_widgets'][] = $wid;
            }
        }
    }
    $sidebars['sidebar-1'] = array();

    // Add Rangin - Search
    $search_id = $create_widget('rangin_search_widget', array());
    $sidebars['sidebar-1'][] = $search_id;

    // Add Rangin - Recent Posts
    $recent_id = $create_widget('rangin_recent_posts_widget', array(
        'title' => __('Recent Posts', 'rangin'),
        'number_of_posts' => 5,
    ));
    $sidebars['sidebar-1'][] = $recent_id;

    // Add Rangin - Categories
    $cats_id = $create_widget('rangin_categories_widget', array(
        'title' => __('Categories', 'rangin'),
        'show_count' => true,
    ));
    $sidebars['sidebar-1'][] = $cats_id;

    // Helper: resolve Footer Menu ID (preferred), with fallbacks
    $resolve_footer_menu = function() {
        $locations = get_theme_mod('nav_menu_locations');
        if (is_array($locations) && !empty($locations['footer'])) {
            return (int) $locations['footer'];
        }
        $menu_obj = wp_get_nav_menu_object(__('Footer Menu', 'rangin'));
        if ($menu_obj) { return (int) $menu_obj->term_id; }
        if (is_array($locations) && !empty($locations['primary'])) {
            return (int) $locations['primary'];
        }
        $menu_obj = wp_get_nav_menu_object(__('Primary Menu', 'rangin'));
        return $menu_obj ? (int) $menu_obj->term_id : 0;
    };

    // Helper: populate defaults for any existing Rangin - Contact Info widgets
    $populate_contact_defaults = function() {
        if (!function_exists('get_contact_email')) { require_once THEME_DIR . '/inc/customizer.php'; }
        $default_email    = function_exists('get_contact_email') ? get_contact_email() : get_option('admin_email');
        $default_phone    = function_exists('get_contact_phone') ? get_contact_phone() : '';
        $default_location = function_exists('get_contact_location') ? get_contact_location() : '';

        $opt_key = 'widget_rangin_contact_info';
        $instances = get_option($opt_key, array());
        if (!is_array($instances)) { $instances = array(); }
        $changed = false;
        foreach ($instances as $k => $inst) {
            if (!is_array($inst)) { continue; }
            $new = $inst;
            if (empty($new['email']))   { $new['email'] = $default_email; }
            if (empty($new['phone']))   { $new['phone'] = $default_phone; }
            if (empty($new['address'])) { $new['address'] = $default_location; }
            if ($new !== $inst) { $instances[$k] = $new; $changed = true; }
        }
        if ($changed) { update_option($opt_key, $instances); }
        return array($default_email, $default_phone, $default_location);
    };

    // Only add Footer widgets if empty
    if (empty($sidebars['footer-1'])) {
        // Resolve Footer Menu (preferred) or fallback to Primary Menu for Quick Links
        $menu_id = $resolve_footer_menu();

        // Rangin - Quick Links
        $ql_id = $create_widget('rangin_quick_links', array(
            'title' => __('Quick Links', 'rangin'),
            'menu'  => $menu_id,
        ));
        if (!in_array($ql_id, $sidebars['footer-1'], true)) {
            $sidebars['footer-1'][] = $ql_id;
        }

        // Rangin - Contact Info with defaults
        list($default_email, $default_phone, $default_location) = $populate_contact_defaults();

        $ci_id = $create_widget('rangin_contact_info', array(
            'title'   => __('Get in Touch', 'rangin'),
            'email'   => $default_email,
            'phone'   => $default_phone,
            'address' => $default_location,
        ));
        if (!in_array($ci_id, $sidebars['footer-1'], true)) {
            $sidebars['footer-1'][] = $ci_id;
        }
    } else {
        // Footer already has widgets — ensure any existing Contact Info widgets are populated
        $populate_contact_defaults();

        // Also set Footer Menu for any existing Rangin - Quick Links widgets
        $footer_menu_id = $resolve_footer_menu();
        $locations = get_theme_mod('nav_menu_locations');
        $primary_menu_id = 0;
        if (is_array($locations) && !empty($locations['primary'])) {
            $primary_menu_id = (int) $locations['primary'];
        } else {
            $pm = wp_get_nav_menu_object(__('Primary Menu', 'rangin'));
            if ($pm) { $primary_menu_id = (int) $pm->term_id; }
        }

        if ($footer_menu_id) {
            $opt_key = 'widget_rangin_quick_links';
            $instances = get_option($opt_key, array());
            if (!is_array($instances)) { $instances = array(); }
            $changed = false;
            foreach ($instances as $k => $inst) {
                if (!is_array($inst)) { continue; }
                $current = isset($inst['menu']) ? (int) $inst['menu'] : 0;
                if ($current === 0 || $current === $primary_menu_id) {
                    $inst['menu'] = $footer_menu_id;
                    $instances[$k] = $inst;
                    $changed = true;
                }
            }
            if ($changed) { update_option($opt_key, $instances); }
        }
    }

    update_option('sidebars_widgets', $sidebars);
}

// Run after other activation tasks to ensure pages/menus exist
add_action('after_switch_theme', 'rangin_setup_default_widgets_on_activation', 30);
