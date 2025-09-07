<?php
/**
 * Custom Widgets for Personal Website Theme
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Rangin Search Widget
*/
class Rangin_Search_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'rangin_search_widget',
            __('Rangin - Search', 'personal-website'),
            array(
                'description' => __('A custom search widget with modern styling.', 'personal-website'),
            )
        );
    }
    
    /**
     * Widget frontend display
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        // Output the search form directly without title
        ?>
        <div class="rangin-search-widget">
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="relative search-form">
                <div class="search-widget-wrapper">
                    <input type="search" 
                           placeholder="Search articles, tutorials..." 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" 
                           class="search-field w-full px-4 py-3 pr-12 bg-neutral-50 dark:bg-neutral-700 border border-neutral-200 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900 dark:text-neutral-100 placeholder-neutral-500 dark:placeholder-neutral-400 transition-all">
                    <button type="submit" class="search-submit absolute right-3 top-1/2 transform -translate-y-1/2 w-8 h-8 flex items-center justify-center text-neutral-500 hover:text-white hover:bg-primary-600 rounded-md dark:text-neutral-400 dark:hover:text-white dark:hover:bg-primary-500 transition-all">
                        <i class="fas fa-search text-sm" aria-hidden="true"></i>
                    </button>
                </div>
            </form>
        </div>
        <?php
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget backend form
     */
    public function form($instance) {
        ?>
        <div style="padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; margin: 10px 0;">
            <div>
                <h4 style="margin: 0 0 15px 0; font-size: 14px; font-weight: 600; color: #23282d; border-bottom: 1px solid #ddd; padding-bottom: 8px;">
                    <?php _e('Search Widget Preview:', 'personal-website'); ?>
                </h4>
                <div style="margin-bottom: 15px;">
                    <div style="position: relative; display: flex; align-items: center; width: 100%; max-width: 300px;">
                        <input type="text" 
                               placeholder="Search articles, tutorials..." 
                               style="width: 100%; padding: 10px 45px 10px 15px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: #fff; color: #666;" 
                               disabled>
                        <button type="button" 
                                style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 32px; height: 32px; border: none; background: #0073aa; border-radius: 4px; color: #fff; cursor: default; display: flex; align-items: center; justify-content: center;" 
                                disabled>
                            <span class="dashicons dashicons-search" style="font-size: 16px; width: 16px; height: 16px;"></span>
                        </button>
                    </div>
                </div>
                <p style="margin: 0; font-size: 12px; color: #666; background: #fff; padding: 10px; border-radius: 4px; border-left: 4px solid #0073aa;">
                    <em style="font-style: normal; color: #555;"><?php _e('This widget displays a custom search form with modern styling. No configuration needed.', 'personal-website'); ?></em>
                </p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        return $new_instance;
    }
}

/**
 * Rangin Recent Posts Widget
*/
class Rangin_Recent_Posts_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'rangin_recent_posts_widget',
            __('Rangin - Recent Posts', 'personal-website'),
            array(
                'description' => __('A custom recent posts widget with modern styling and formatted dates.', 'personal-website'),
            )
        );
    }
    
    /**
     * Widget frontend display
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        // Get number of posts to show (default: 5)
        $number_of_posts = !empty($instance['number_of_posts']) ? absint($instance['number_of_posts']) : 5;
        
        // Widget title
        $title = !empty($instance['title']) ? $instance['title'] : __('Recent Posts', 'personal-website');
        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }
        
        // Query recent posts
        $recent_posts = wp_get_recent_posts(array(
            'numberposts' => $number_of_posts,
            'post_status' => 'publish'
        ));
        
        if (!empty($recent_posts)) {
            echo '<div class="rangin-recent-posts-widget">';
            echo '<ul class="recent-posts-list space-y-3">';
            
            foreach ($recent_posts as $post) {
                $post_date = mysql2date('j F Y', $post['post_date']);
                $post_title = esc_html($post['post_title']);
                $post_url = get_permalink($post['ID']);
                
                echo '<li class="recent-post-item group">';
                echo '<article class="block">';
                echo '<h4 class="recent-post-title mb-0! border-none">';
                echo '<a href="' . esc_url($post_url) . '" class="text-neutral-900 dark:text-neutral-100 hover:text-primary-600 dark:hover:text-primary-400 font-medium text-sm leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">';
                echo $post_title;
                echo '</a>';
                echo '</h4>';
                echo '<time class="recent-post-date text-xs text-neutral-600 dark:text-neutral-400 flex items-center">';
                echo '<i class="far fa-calendar-alt mr-1.5 text-primary-500 dark:text-primary-400 text-xs" aria-hidden="true"></i>';
                echo $post_date;
                echo '</time>';
                echo '</article>';
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="rangin-recent-posts-widget">';
            echo '<p class="text-neutral-600 dark:text-neutral-400 italic">' . __('No recent posts found.', 'personal-website') . '</p>';
            echo '</div>';
        }
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget backend form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Recent Posts', 'personal-website');
        $number_of_posts = !empty($instance['number_of_posts']) ? absint($instance['number_of_posts']) : 5;
        ?>
        <div style="padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; margin: 10px 0;">
            <div>
                <h4 style="margin: 0 0 15px 0; font-size: 14px; font-weight: 600; color: #23282d; border-bottom: 1px solid #ddd; padding-bottom: 8px;">
                    <?php _e('Recent Posts Widget Settings:', 'personal-website'); ?>
                </h4>
                
                <!-- Title field -->
                <div style="margin-bottom: 15px;">
                    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>" style="display: block; margin-bottom: 5px; font-weight: 600; color: #23282d;">
                        <?php _e('Title:', 'personal-website'); ?>
                    </label>
                    <input type="text" 
                           id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                           name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                           value="<?php echo esc_attr($title); ?>" 
                           style="width: 100%; padding: 8px 12px; border: 2px solid #ddd; border-radius: 4px; font-size: 14px;" />
                </div>
                
                <!-- Number of posts field -->
                <div style="margin-bottom: 15px;">
                    <label for="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>" style="display: block; margin-bottom: 5px; font-weight: 600; color: #23282d;">
                        <?php _e('Number of posts to show:', 'personal-website'); ?>
                    </label>
                    <input type="number" 
                           id="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>" 
                           name="<?php echo esc_attr($this->get_field_name('number_of_posts')); ?>" 
                           value="<?php echo esc_attr($number_of_posts); ?>" 
                           min="1" 
                           max="20" 
                           style="width: 100px; padding: 8px 12px; border: 2px solid #ddd; border-radius: 4px; font-size: 14px;" />
                </div>
                
                <!-- Preview -->
                <div style="margin-bottom: 15px;">
                    <h5 style="margin: 0 0 10px 0; font-size: 13px; font-weight: 600; color: #23282d;">
                        <?php _e('Preview:', 'personal-website'); ?>
                    </h5>
                    <div style="background: #fff; padding: 15px; border-radius: 6px; border: 1px solid #e0e0e0;">
                        <div style="margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #e0e0e0;">
                            <h4 style="margin: 0; font-size: 15px; font-weight: 600; color: #23282d;">Recent Posts</h4>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <h5 style="margin: 0 0 3px 0; font-size: 13px; line-height: 1.3;">
                                <a href="#" style="color: #0073aa; text-decoration: none; font-weight: 500;">Sample Post Title Here</a>
                            </h5>
                            <time style="font-size: 11px; color: #777; display: flex; align-items: center;">
                                <span class="dashicons dashicons-calendar-alt" style="font-size: 12px; margin-right: 4px; color: #0073aa;"></span>
                                <?php echo date('j F Y'); ?>
                            </time>
                        </div>
                        <div style="margin-bottom: 12px;">
                            <h5 style="margin: 0 0 3px 0; font-size: 13px; line-height: 1.3;">
                                <a href="#" style="color: #0073aa; text-decoration: none; font-weight: 500;">Another Great Article</a>
                            </h5>
                            <time style="font-size: 11px; color: #777; display: flex; align-items: center;">
                                <span class="dashicons dashicons-calendar-alt" style="font-size: 12px; margin-right: 4px; color: #0073aa;"></span>
                                <?php echo date('j F Y', strtotime('-2 days')); ?>
                            </time>
                        </div>
                        <div>
                            <h5 style="margin: 0 0 3px 0; font-size: 13px; line-height: 1.3;">
                                <a href="#" style="color: #0073aa; text-decoration: none; font-weight: 500;">How to Build Amazing Websites</a>
                            </h5>
                            <time style="font-size: 11px; color: #777; display: flex; align-items: center;">
                                <span class="dashicons dashicons-calendar-alt" style="font-size: 12px; margin-right: 4px; color: #0073aa;"></span>
                                <?php echo date('j F Y', strtotime('-5 days')); ?>
                            </time>
                        </div>
                    </div>
                </div>
                
                <p style="margin: 0; font-size: 12px; color: #666; background: #fff; padding: 10px; border-radius: 4px; border-left: 4px solid #0073aa;">
                    <em style="font-style: normal; color: #555;"><?php _e('This widget displays recent posts with titles and formatted dates. Adjust the number of posts and title as needed.', 'personal-website'); ?></em>
                </p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number_of_posts'] = (!empty($new_instance['number_of_posts'])) ? absint($new_instance['number_of_posts']) : 5;
        
        return $instance;
    }
}

/**
 * Rangin Categories Widget
*/
class Rangin_Categories_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'rangin_categories_widget',
            __('Rangin - Categories', 'personal-website'),
            array(
                'description' => __('Display a list of categories with modern styling.', 'personal-website')
            )
        );
    }
    
    /**
     * Widget frontend output
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Categories', 'personal-website');
        $show_count = !empty($instance['show_count']) ? $instance['show_count'] : false;
        
        echo $args['before_widget'];
        
        // Widget title with gradient bar
        if ($title) {
            echo '<div class="widget-title-wrapper mb-4">';
            echo '<h3 class="widget-title text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-2 flex items-center">';
            echo esc_html($title);
            echo '</h3>';
            echo '</div>';
        }
        
        // Get categories
        $categories = get_categories(array(
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC'
        ));
        
        if (!empty($categories)) {
            echo '<ul class="categories-list space-y-2">';
            
            foreach ($categories as $category) {
                $category_link = get_category_link($category->term_id);
                $category_name = esc_html($category->name);
                $post_count = $category->count;
                
                echo '<li class="category-item group">';
                echo '<div class="flex items-center justify-between py-2 px-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors duration-200">';
                echo '<a href="' . esc_url($category_link) . '" class="category-link flex items-center text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">';
                echo '<i class="fas fa-folder mr-2 text-primary-500 dark:text-primary-400 text-sm" aria-hidden="true"></i>';
                echo '<span class="font-medium text-sm">' . $category_name . '</span>';
                echo '</a>';
                
                if ($show_count) {
                    echo '<span class="category-count inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 ml-2">';
                    echo $post_count;
                    echo '</span>';
                }
                
                echo '</div>';
                echo '</li>';
            }
            
            echo '</ul>';
        } else {
            echo '<p class="text-neutral-600 dark:text-neutral-400 italic text-sm">' . __('No categories found.', 'personal-website') . '</p>';
        }
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget backend form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Categories', 'personal-website');
        $show_count = !empty($instance['show_count']) ? $instance['show_count'] : false;
        ?>
        <div style="padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; margin: 10px 0;">
            <div style="margin-bottom: 15px;">
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                       style="display: block; font-weight: 600; margin-bottom: 5px; color: #23282d;">
                    <?php _e('Title:', 'personal-website'); ?>
                </label>
                <input type="text" 
                       id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                       value="<?php echo esc_attr($title); ?>" 
                       style="width: 100%; padding: 8px 12px; border: 2px solid #ddd; border-radius: 4px; font-size: 14px;" />
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: flex; align-items: center; font-size: 14px; color: #23282d;">
                    <input type="checkbox" 
                           id="<?php echo esc_attr($this->get_field_id('show_count')); ?>" 
                           name="<?php echo esc_attr($this->get_field_name('show_count')); ?>" 
                           value="1" 
                           <?php checked($show_count, true); ?>
                           style="margin-right: 8px;" />
                    <?php _e('Show post count', 'personal-website'); ?>
                </label>
            </div>
            
            <!-- Preview -->
            <div style="margin-bottom: 15px;">
                <h5 style="margin: 0 0 10px 0; font-size: 13px; font-weight: 600; color: #23282d;">
                    <?php _e('Preview:', 'personal-website'); ?>
                </h5>
                <div style="background: #fff; padding: 15px; border-radius: 6px; border: 1px solid #e0e0e0;">
                    <div style="margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #e0e0e0;">
                        <h4 style="margin: 0; font-size: 15px; font-weight: 600; color: #23282d; display: flex; align-items: center;">
                            <span class="dashicons dashicons-category" style="margin-right: 6px; color: #0073aa;"></span>
                            Categories
                        </h4>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #f8f9fa; border-radius: 4px;">
                            <a href="#" style="color: #0073aa; text-decoration: none; font-weight: 500; font-size: 13px; display: flex; align-items: center;">
                                <span class="dashicons dashicons-portfolio" style="font-size: 14px; margin-right: 6px;"></span>
                                Technology
                            </a>
                            <span style="background: #0073aa; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 500;">5</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #f8f9fa; border-radius: 4px;">
                            <a href="#" style="color: #0073aa; text-decoration: none; font-weight: 500; font-size: 13px; display: flex; align-items: center;">
                                <span class="dashicons dashicons-portfolio" style="font-size: 14px; margin-right: 6px;"></span>
                                Web Development
                            </a>
                            <span style="background: #0073aa; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 500;">3</span>
                        </div>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #f8f9fa; border-radius: 4px;">
                            <a href="#" style="color: #0073aa; text-decoration: none; font-weight: 500; font-size: 13px; display: flex; align-items: center;">
                                <span class="dashicons dashicons-portfolio" style="font-size: 14px; margin-right: 6px;"></span>
                                Tutorials
                            </a>
                            <span style="background: #0073aa; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 500;">8</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <p style="margin: 0; font-size: 12px; color: #666; background: #fff; padding: 10px; border-radius: 4px; border-left: 4px solid #0073aa;">
                <strong>💡 Tip:</strong> This widget will display all categories that have posts. You can choose to show or hide the post count for each category.
            </p>
        </div>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_count'] = (!empty($new_instance['show_count'])) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Rangin Quick Links Widget
 * Displays navigation menu links in footer
 */
class Rangin_Quick_Links_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'rangin_quick_links',
            __('Rangin - Quick Links', 'personal-website'),
            array(
                'description' => __('Display navigation menu links in footer.', 'personal-website'),
                'classname' => 'rangin-quick-links-widget',
            )
        );
    }
    
    /**
     * Display the widget
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Quick Links', 'personal-website');
        $menu_id = !empty($instance['menu']) ? $instance['menu'] : '';
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo '<h4 class="text-lg font-semibold mb-4 text-neutral-700 dark:text-neutral-300">' . esc_html($title) . '</h4>';
        }
        
        if (!empty($menu_id)) {
            wp_nav_menu(array(
                'menu' => $menu_id,
                'container' => false,
                'menu_class' => 'space-y-2',
                'fallback_cb' => false,
                'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                'link_before' => '',
                'link_after' => '',
                'depth' => 1,
                'walker' => new Rangin_Footer_Nav_Walker(),
            ));
        } else {
            echo '<p class="text-neutral-400 text-sm">' . __('Please select a menu in widget settings.', 'personal-website') . '</p>';
        }
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form in admin
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Quick Links', 'personal-website');
        $menu_id = !empty($instance['menu']) ? $instance['menu'] : '';
        
        $menus = wp_get_nav_menus();
        ?>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #007cba;">
            <h4 style="margin: 0 0 10px 0; color: #1d2327; font-size: 14px;">
                🔗 Rangin Quick Links Widget
            </h4>
            <p style="margin: 0; color: #646970; font-size: 12px; line-height: 1.4;">
                <strong>📍 Features:</strong> Display navigation menu links in footer with custom styling
            </p>
            <p style="margin: 8px 0 0 0; color: #646970; font-size: 12px; line-height: 1.4;">
                <strong>💡 Setup:</strong> Choose a menu from Appearance > Menus, then select it here
            </p>
        </div>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'personal-website'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('menu')); ?>"><?php _e('Select Menu:', 'personal-website'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('menu')); ?>" name="<?php echo esc_attr($this->get_field_name('menu')); ?>">
                <option value=""><?php _e('— Select Menu —', 'personal-website'); ?></option>
                <?php foreach ($menus as $menu): ?>
                    <option value="<?php echo esc_attr($menu->term_id); ?>" <?php selected($menu_id, $menu->term_id); ?>>
                        <?php echo esc_html($menu->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small><?php _e('Create and manage menus in Appearance > Menus', 'personal-website'); ?></small>
        </p>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['menu'] = (!empty($new_instance['menu'])) ? absint($new_instance['menu']) : '';
        
        return $instance;
    }
}

/**
 * Rangin Contact Info Widget
 * Displays contact information in footer
 */
class Rangin_Contact_Info_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'rangin_contact_info',
            __('Rangin - Contact Info', 'personal-website'),
            array(
                'description' => __('Display contact information with icons in footer.', 'personal-website'),
                'classname' => 'rangin-contact-info-widget',
            )
        );
    }
    
    /**
     * Display the widget
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Get in Touch', 'personal-website');
        $email = !empty($instance['email']) ? $instance['email'] : '';
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $address = !empty($instance['address']) ? $instance['address'] : '';
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo '<h4 class="text-lg font-semibold mb-4 text-neutral-700 dark:text-neutral-300">' . esc_html($title) . '</h4>';
        }
        
        echo '<ul class="space-y-3">';
        
        if (!empty($email)) {
            echo '<li class="flex items-center">';
            echo '<i class="fas fa-envelope text-primary-400 w-4 mr-3"></i>';
            echo '<a href="mailto:' . esc_attr($email) . '" class="text-neutral-700 dark:text-neutral-300 hover:text-primary-400 dark:hover:text-primary-300 transition-colors">';
            echo esc_html($email);
            echo '</a>';
            echo '</li>';
        }
        
        if (!empty($phone)) {
            echo '<li class="flex items-center">';
            echo '<i class="fas fa-phone text-primary-400 w-4 mr-3"></i>';
            echo '<a href="tel:' . esc_attr(str_replace(' ', '', $phone)) . '" class="text-neutral-700 dark:text-neutral-300 hover:text-primary-400 dark:hover:text-primary-300 transition-colors">';
            echo esc_html($phone);
            echo '</a>';
            echo '</li>';
        }
        
        if (!empty($address)) {
            echo '<li class="flex items-center">';
            echo '<i class="fas fa-map-marker-alt text-primary-400 w-4 mr-3"></i>';
            echo '<span class="text-neutral-700 dark:text-neutral-300">' . esc_html($address) . '</span>';
            echo '</li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form in admin
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Get in Touch', 'personal-website');
        $email = !empty($instance['email']) ? $instance['email'] : '';
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $address = !empty($instance['address']) ? $instance['address'] : '';
        ?>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #007cba;">
            <h4 style="margin: 0 0 10px 0; color: #1d2327; font-size: 14px;">
                📞 Rangin Contact Info Widget
            </h4>
            <p style="margin: 0; color: #646970; font-size: 12px; line-height: 1.4;">
                <strong>📍 Features:</strong> Display contact information with Font Awesome icons
            </p>
            <p style="margin: 8px 0 0 0; color: #646970; font-size: 12px; line-height: 1.4;">
                <strong>💡 Setup:</strong> Fill in the contact details you want to display. Leave empty to hide.
            </p>
        </div>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'personal-website'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php _e('Email Address:', 'personal-website'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="email" value="<?php echo esc_attr($email); ?>" placeholder="hello@example.com">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php _e('Phone Number:', 'personal-website'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="tel" value="<?php echo esc_attr($phone); ?>" placeholder="+1 (555) 123-4567">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php _e('Location/Address:', 'personal-website'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" type="text" value="<?php echo esc_attr($address); ?>" placeholder="City, Country">
        </p>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        
        // Fix email sanitization - sanitize_email can return false for valid emails in some cases
        if (!empty($new_instance['email'])) {
            $sanitized_email = sanitize_email($new_instance['email']);
            $instance['email'] = $sanitized_email ? $sanitized_email : sanitize_text_field($new_instance['email']);
        } else {
            $instance['email'] = '';
        }
        
        $instance['phone'] = (!empty($new_instance['phone'])) ? sanitize_text_field($new_instance['phone']) : '';
        $instance['address'] = (!empty($new_instance['address'])) ? sanitize_text_field($new_instance['address']) : '';
        
        return $instance;
    }
}

/**
 * Custom Footer Navigation Walker
 * For formatting footer menu links
 */
class Rangin_Footer_Nav_Walker extends Walker_Nav_Menu {
    
    // Start Level
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }
    
    // End Level
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    // Start Element
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target) ? ' target="' . esc_attr($item->target) .'"' : '';
        $attributes .= ! empty($item->xfn) ? ' rel="'    . esc_attr($item->xfn) .'"' : '';
        $attributes .= ! empty($item->url) ? ' href="'   . esc_attr($item->url) .'"' : '';
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="text-neutral-700 dark:text-neutral-300 hover:text-primary-400 dark:hover:text-primary-300 transition-colors">';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    // End Element
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

/**
 * Register the custom widgets
 */
function rangin_register_custom_widgets() {
    register_widget('Rangin_Search_Widget');
    register_widget('Rangin_Recent_Posts_Widget');
    register_widget('Rangin_Categories_Widget');
    register_widget('Rangin_Quick_Links_Widget');
    register_widget('Rangin_Contact_Info_Widget');
}
add_action('widgets_init', 'rangin_register_custom_widgets');
