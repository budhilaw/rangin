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
 * EBTW Search Widget
 */
class EBTW_Search_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'ebtw_search_widget',
            __('EBTW - Search', 'personal-website'),
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
        <div class="ebtw-search-widget">
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
 * EBTW Recent Posts Widget
 */
class EBTW_Recent_Posts_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'ebtw_recent_posts_widget',
            __('EBTW - Recent Posts', 'personal-website'),
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
            echo '<div class="ebtw-recent-posts-widget">';
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
            echo '<div class="ebtw-recent-posts-widget">';
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
 * Register the custom widgets
 */
function ebtw_register_custom_widgets() {
    register_widget('EBTW_Search_Widget');
    register_widget('EBTW_Recent_Posts_Widget');
}
add_action('widgets_init', 'ebtw_register_custom_widgets');
