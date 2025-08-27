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
        'rewrite'            => array('slug' => 'portfolio-item'),
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
    $category = get_post_meta($post->ID, '_portfolio_category', true);
    $demo_link = get_post_meta($post->ID, '_portfolio_demo_link', true);
    $github_link = get_post_meta($post->ID, '_portfolio_github_link', true);
    $technologies = get_post_meta($post->ID, '_portfolio_technologies', true);
    
    // Get dynamic categories from customizer
    $categories = get_portfolio_categories();
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="portfolio_category"><?php _e('Category', 'personal-website'); ?></label>
            </th>
            <td>
                <select id="portfolio_category" name="portfolio_category" class="regular-text">
                    <option value=""><?php _e('Select Category', 'personal-website'); ?></option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo esc_attr($cat['slug']); ?>" <?php selected($category, $cat['slug']); ?>>
                        <?php echo esc_html($cat['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <p class="description">
                    <?php _e('Select the main category for this portfolio item.', 'personal-website'); ?>
                    <?php if (current_user_can('customize')): ?>
                    <br><a href="<?php echo admin_url('customize.php?autofocus[section]=portfolio_settings'); ?>" target="_blank">
                        <?php _e('Customize categories', 'personal-website'); ?> →
                    </a>
                    <?php endif; ?>
                </p>
            </td>
        </tr>
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

    // Save meta fields
    if (isset($_POST['portfolio_category'])) {
        update_post_meta($post_id, '_portfolio_category', sanitize_text_field($_POST['portfolio_category']));
    }

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
            $category = get_post_meta($post_id, '_portfolio_category', true);
            if ($category) {
                $category_data = get_portfolio_category_by_slug($category);
                echo $category_data ? esc_html($category_data['name']) : esc_html(ucfirst($category));
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
    return get_post_meta($post_id, '_portfolio_category', true);
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
    $category_data = get_portfolio_category_by_slug($category_slug);
    return $category_data ? $category_data['name'] : ucfirst($category_slug);
}

function get_portfolio_category_color($category_slug) {
    $category_data = get_portfolio_category_by_slug($category_slug);
    return $category_data ? $category_data['color'] : 'blue';
}

/**
 * Flush rewrite rules on theme activation
 */
function portfolio_flush_rewrite_rules() {
    register_portfolio_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'portfolio_flush_rewrite_rules');
