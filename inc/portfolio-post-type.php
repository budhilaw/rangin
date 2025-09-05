<?php
/**
 * Portfolio Custom Post Type
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Portfolio Custom Post Type
 */
function register_portfolio_post_type() {
    $labels = array(
        'name'                  => _x('Portfolio', 'Post type general name', 'personal-website'),
        'singular_name'         => _x('Portfolio Item', 'Post type singular name', 'personal-website'),
        'menu_name'             => _x('Portfolio', 'Admin Menu text', 'personal-website'),
        'name_admin_bar'        => _x('Portfolio Item', 'Add New on Toolbar', 'personal-website'),
        'add_new'               => __('Add New', 'personal-website'),
        'add_new_item'          => __('Add New Portfolio Item', 'personal-website'),
        'new_item'              => __('New Portfolio Item', 'personal-website'),
        'edit_item'             => __('Edit Portfolio Item', 'personal-website'),
        'view_item'             => __('View Portfolio Item', 'personal-website'),
        'all_items'             => __('All Portfolio Items', 'personal-website'),
        'search_items'          => __('Search Portfolio Items', 'personal-website'),
        'parent_item_colon'     => __('Parent Portfolio Items:', 'personal-website'),
        'not_found'             => __('No portfolio items found.', 'personal-website'),
        'not_found_in_trash'    => __('No portfolio items found in Trash.', 'personal-website'),
        'featured_image'        => _x('Portfolio Featured Image', 'Overrides the "Featured Image" phrase', 'personal-website'),
        'set_featured_image'    => _x('Set portfolio featured image', 'Overrides the "Set featured image" phrase', 'personal-website'),
        'remove_featured_image' => _x('Remove portfolio featured image', 'Overrides the "Remove featured image" phrase', 'personal-website'),
        'use_featured_image'    => _x('Use as portfolio featured image', 'Overrides the "Use as featured image" phrase', 'personal-website'),
        'archives'              => _x('Portfolio archives', 'The post type archive label used in nav menus', 'personal-website'),
        'insert_into_item'      => _x('Insert into portfolio item', 'Overrides the "Insert into post"', 'personal-website'),
        'uploaded_to_this_item' => _x('Uploaded to this portfolio item', 'Overrides the "Uploaded to this post"', 'personal-website'),
        'filter_items_list'     => _x('Filter portfolio items list', 'Screen reader text for the filter links', 'personal-website'),
        'items_list_navigation' => _x('Portfolio items list navigation', 'Screen reader text for the pagination', 'personal-website'),
        'items_list'            => _x('Portfolio items list', 'Screen reader text for the items list', 'personal-website'),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Portfolio items to showcase your work.', 'personal-website'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array(
            'slug' => 'portfolio-item',
            'with_front' => false,
            'feeds' => true,
            'pages' => true
        ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('portfolio', $args);
}
add_action('init', 'register_portfolio_post_type');

/**
 * Register Portfolio Categories taxonomy
 */
function register_portfolio_categories_taxonomy() {
    $labels = array(
        'name'              => _x('Portfolio Categories', 'taxonomy general name', 'personal-website'),
        'singular_name'     => _x('Portfolio Category', 'taxonomy singular name', 'personal-website'),
        'search_items'      => __('Search Portfolio Categories', 'personal-website'),
        'all_items'         => __('All Portfolio Categories', 'personal-website'),
        'parent_item'       => __('Parent Portfolio Category', 'personal-website'),
        'parent_item_colon' => __('Parent Portfolio Category:', 'personal-website'),
        'edit_item'         => __('Edit Portfolio Category', 'personal-website'),
        'update_item'       => __('Update Portfolio Category', 'personal-website'),
        'add_new_item'      => __('Add New Portfolio Category', 'personal-website'),
        'new_item_name'     => __('New Portfolio Category Name', 'personal-website'),
        'menu_name'         => __('Categories', 'personal-website'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'portfolio-category', 'with_front' => false),
        'show_in_rest'      => true,
    );

    register_taxonomy('portfolio_category', array('portfolio'), $args);
}
add_action('init', 'register_portfolio_categories_taxonomy');

/**
 * Add Portfolio Meta Boxes
 */
function add_portfolio_meta_boxes() {
    add_meta_box(
        'portfolio_details',
        __('Portfolio Details', 'personal-website'),
        'portfolio_details_callback',
        'portfolio',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_portfolio_meta_boxes');

/**
 * Portfolio Details Meta Box Callback
 */
function portfolio_details_callback($post) {
    // Add nonce field for security
    wp_nonce_field('portfolio_details_nonce', 'portfolio_details_nonce');

    // Get current values
    $demo_link = get_post_meta($post->ID, '_portfolio_demo_link', true);
    $github_link = get_post_meta($post->ID, '_portfolio_github_link', true);
    $technologies = get_post_meta($post->ID, '_portfolio_technologies', true);
    
    ?>
    <table class="form-table">
        
        <tr>
            <th scope="row">
                <label for="portfolio_demo_link"><?php _e('Demo Link', 'personal-website'); ?></label>
            </th>
            <td>
                <input type="url" id="portfolio_demo_link" name="portfolio_demo_link" value="<?php echo esc_attr($demo_link); ?>" class="regular-text" placeholder="https://example.com/demo">
                <p class="description"><?php _e('Enter the live demo URL (optional). Leave empty to hide the demo button.', 'personal-website'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="portfolio_github_link"><?php _e('GitHub Link', 'personal-website'); ?></label>
            </th>
            <td>
                <input type="url" id="portfolio_github_link" name="portfolio_github_link" value="<?php echo esc_attr($github_link); ?>" class="regular-text" placeholder="https://github.com/username/repository">
                <p class="description"><?php _e('Enter the GitHub repository URL (optional). Leave empty to hide the GitHub button.', 'personal-website'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="portfolio_technologies"><?php _e('Technologies Used', 'personal-website'); ?></label>
            </th>
            <td>
                <input type="text" id="portfolio_technologies" name="portfolio_technologies" value="<?php echo esc_attr($technologies); ?>" class="regular-text" placeholder="React, Node.js, MongoDB">
                <p class="description"><?php _e('Enter technologies used, separated by commas (optional).', 'personal-website'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save Portfolio Meta Data
 */
function save_portfolio_meta_data($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['portfolio_details_nonce']) || !wp_verify_nonce($_POST['portfolio_details_nonce'], 'portfolio_details_nonce')) {
        return;
    }

    // Check if user has permissions to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save meta fields (category handled by taxonomy UI)

    if (isset($_POST['portfolio_demo_link'])) {
        update_post_meta($post_id, '_portfolio_demo_link', esc_url_raw($_POST['portfolio_demo_link']));
    }

    if (isset($_POST['portfolio_github_link'])) {
        update_post_meta($post_id, '_portfolio_github_link', esc_url_raw($_POST['portfolio_github_link']));
    }

    if (isset($_POST['portfolio_technologies'])) {
        update_post_meta($post_id, '_portfolio_technologies', sanitize_text_field($_POST['portfolio_technologies']));
    }
}
add_action('save_post', 'save_portfolio_meta_data');

/**
 * Add custom columns to Portfolio admin list
 */
function add_portfolio_admin_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['portfolio_featured_image'] = __('Featured Image', 'personal-website');
    $new_columns['portfolio_category'] = __('Category', 'personal-website');
    $new_columns['portfolio_technologies'] = __('Technologies', 'personal-website');
    $new_columns['portfolio_links'] = __('Links', 'personal-website');
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_portfolio_posts_columns', 'add_portfolio_admin_columns');

/**
 * Display custom column content
 */
function display_portfolio_admin_columns($column, $post_id) {
    switch ($column) {
        case 'portfolio_featured_image':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50));
            } else {
                echo '<span style="color: #999;">No image</span>';
            }
            break;
            
        case 'portfolio_category':
            $terms = get_the_terms($post_id, 'portfolio_category');
            if ($terms && !is_wp_error($terms)) {
                $names = wp_list_pluck($terms, 'name');
                echo esc_html(implode(', ', $names));
            } else {
                echo '<span style="color: #999;">Not set</span>';
            }
            break;
            
        case 'portfolio_technologies':
            $technologies = get_post_meta($post_id, '_portfolio_technologies', true);
            echo $technologies ? esc_html($technologies) : '<span style="color: #999;">Not set</span>';
            break;
            
        case 'portfolio_links':
            $demo_link = get_post_meta($post_id, '_portfolio_demo_link', true);
            $github_link = get_post_meta($post_id, '_portfolio_github_link', true);
            
            $links = array();
            if ($demo_link) {
                $links[] = '<a href="' . esc_url($demo_link) . '" target="_blank">Demo</a>';
            }
            if ($github_link) {
                $links[] = '<a href="' . esc_url($github_link) . '" target="_blank">GitHub</a>';
            }
            
            echo !empty($links) ? implode(' | ', $links) : '<span style="color: #999;">No links</span>';
            break;
    }
}
add_action('manage_portfolio_posts_custom_column', 'display_portfolio_admin_columns', 10, 2);

/**
 * Helper functions to get portfolio meta data
 */
function get_portfolio_category($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    $terms = wp_get_post_terms($post_id, 'portfolio_category');
    if (is_wp_error($terms) || empty($terms)) {
        return '';
    }
    return $terms[0]->slug;
}

function get_portfolio_demo_link($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    return get_post_meta($post_id, '_portfolio_demo_link', true);
}

function get_portfolio_github_link($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    return get_post_meta($post_id, '_portfolio_github_link', true);
}

function get_portfolio_technologies($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    return get_post_meta($post_id, '_portfolio_technologies', true);
}

function get_portfolio_category_name($category_slug) {
    if (empty($category_slug)) return '';
    $term = get_term_by('slug', $category_slug, 'portfolio_category');
    return $term && !is_wp_error($term) ? $term->name : ucfirst($category_slug);
}

function get_portfolio_category_color($category_slug) {
    if (empty($category_slug)) return 'blue';
    $colors = array('green','blue','purple','orange','red','teal','indigo','pink','gray');
    $index = abs(crc32($category_slug)) % count($colors);
    return $colors[$index];
}

/**
 * Flush rewrite rules on theme activation and when needed
 */
function portfolio_flush_rewrite_rules() {
    register_portfolio_post_type();
    flush_rewrite_rules();
}

// Hook into theme activation and switching
add_action('after_switch_theme', 'portfolio_flush_rewrite_rules');

// Flush rewrite rules if portfolio post type is not registered properly
function check_portfolio_rewrite_rules() {
    if (get_option('portfolio_rewrite_rules_flushed') !== 'yes') {
        register_portfolio_post_type();
        flush_rewrite_rules();
        update_option('portfolio_rewrite_rules_flushed', 'yes');
    }
}
add_action('init', 'check_portfolio_rewrite_rules', 20);

// Force flush rewrite rules once to fix the current issue
function force_initial_portfolio_flush() {
    if (get_option('portfolio_links_fixed_v2') !== 'yes') {
        register_portfolio_post_type();
        flush_rewrite_rules();
        update_option('portfolio_links_fixed_v2', 'yes');
    }
}
add_action('wp_loaded', 'force_initial_portfolio_flush');

/**
 * Force flush rewrite rules - can be called from admin
 */
function force_portfolio_rewrite_flush() {
    delete_option('portfolio_rewrite_rules_flushed');
    register_portfolio_post_type();
    flush_rewrite_rules();
    update_option('portfolio_rewrite_rules_flushed', 'yes');
}

/**
 * Add admin notice to flush rewrite rules if needed
 */
function portfolio_admin_notices() {
    global $pagenow;
    
    if ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'portfolio') {
        // Check if we have portfolio posts but rewrite rules might not be working
        $portfolio_posts = get_posts(array(
            'post_type' => 'portfolio',
            'numberposts' => 1,
            'post_status' => 'publish'
        ));
        
        if (!empty($portfolio_posts)) {
            $test_permalink = get_permalink($portfolio_posts[0]->ID);
            // If permalink contains '?p=' it means rewrite rules aren't working
            if (strpos($test_permalink, '?p=') !== false) {
                echo '<div class="notice notice-warning is-dismissible">';
                echo '<p><strong>Portfolio permalinks may not be working correctly.</strong> ';
                echo '<a href="' . admin_url('options-permalink.php') . '">Visit Permalinks settings</a> and click "Save Changes" to refresh them.</p>';
                echo '</div>';
            }
        }
    }
}
add_action('admin_notices', 'portfolio_admin_notices');
