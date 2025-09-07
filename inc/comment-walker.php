<?php
/**
 * Custom Comment Walker
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom walker class to implement an HTML list of comments.
 */
class Personal_Website_Comment_Walker extends Walker_Comment {
    
    /**
     * Outputs a comment in the HTML5 format.
     *
     * @see wp_list_comments()
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function html5_comment($comment, $depth, $args) {
        $tag = ('div' === $args['style']) ? 'div' : 'li';
        
        $commenter          = wp_get_current_commenter();
        $show_pending_links = !empty($commenter['comment_author']);

        if ($commenter['comment_author_email']) {
            $moderation_note = __('Your comment is awaiting moderation.', 'rangin');
        } else {
            $moderation_note = __('Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'rangin');
        }
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class($this->has_children ? 'parent comment-item card p-6 mb-6' : 'comment-item card p-6 mb-6', $comment); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                <div class="comment-meta flex items-start space-x-4">
                    <div class="comment-author-avatar flex-shrink-0">
                        <?php
                        $avatar_size = ('0' != $comment->comment_parent) ? 48 : 64;
                        if (0 != $args['avatar_size']) {
                            echo get_avatar($comment, $avatar_size, '', '', array('class' => 'rounded-full'));
                        }
                        ?>
                    </div>
                    
                    <div class="comment-metadata-content flex-1">
                        <div class="comment-author-info mb-2">
                            <div class="comment-author vcard flex items-center flex-wrap gap-2">
                                <?php
                                $comment_author = get_comment_author_link($comment);
                                if ('0' == $comment->comment_approved && !$show_pending_links) {
                                    $comment_author = get_comment_author($comment);
                                }
                                ?>
                                <span class="fn font-semibold text-lg text-neutral-900 dark:text-neutral-100">
                                    <?php echo $comment_author; ?>
                                </span>
                                
                                <?php if (user_can($comment->user_id, 'manage_options')): ?>
                                    <span class="comment-author-badge inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200">
                                        <i class="fas fa-crown mr-1"></i>
                                        <?php _e('Author', 'rangin'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="comment-metadata flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                <time datetime="<?php comment_time('c'); ?>" class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        <?php
                                        /* translators: 1: Comment date, 2: Comment time. */
                                        printf(__('%1$s at %2$s', 'rangin'), get_comment_date('', $comment), get_comment_time());
                                        ?>
                                    </a>
                                </time>
                                
                                <?php
                                comment_reply_link(
                                    array_merge(
                                        $args,
                                        array(
                                            'add_below' => 'div-comment',
                                            'depth'     => $depth,
                                            'max_depth' => $args['max_depth'],
                                            'before'    => '<div class="reply flex items-center">',
                                            'after'     => '</div>',
                                            'reply_text' => '<i class="fas fa-reply mr-1"></i>' . __('Reply', 'rangin'),
                                            'class'     => 'comment-reply-link text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 transition-colors text-sm font-medium'
                                        )
                                    ),
                                    $comment,
                                    get_the_ID()
                                );
                                ?>
                                
                                <?php edit_comment_link('<i class="fas fa-edit mr-1"></i>' . __('Edit', 'rangin'), '<div class="edit-link">', '</div>', null, 'text-neutral-600 hover:text-accent-600 dark:text-neutral-400 dark:hover:text-accent-400 transition-colors text-sm font-medium'); ?>
                            </div>
                        </div>
                        
                        <?php if ('0' == $comment->comment_approved): ?>
                            <div class="comment-awaiting-moderation bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-hourglass-half text-yellow-600 dark:text-yellow-400 mr-2"></i>
                                    <span class="text-sm text-yellow-800 dark:text-yellow-200"><?php echo $moderation_note; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="comment-content prose prose-sm dark:prose-invert max-w-none mt-4">
                            <?php comment_text(); ?>
                        </div>
                    </div>
                </div>
            </article>
        <?php
    }
}

/**
 * Enable threaded comments
 */
function personal_website_enqueue_comment_reply() {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'personal_website_enqueue_comment_reply');

/**
 * Improve comment form styling
 */
function personal_website_comment_form_fields($fields) {
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $html_req = ($req ? ' required' : '');
    $html5 = current_theme_supports('html5', 'comment-form');
    
    $fields['author'] = sprintf(
        '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"><div class="form-group"><label for="author" class="block text-sm font-medium mb-2">%s%s</label><input id="author" name="author" type="text" value="%s" size="30" maxlength="245"%s class="w-full px-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200" placeholder="%s"></div>',
        __('Name', 'rangin'),
        ($req ? ' <span class="text-red-500">*</span>' : ''),
        esc_attr($commenter['comment_author']),
        $html_req,
        __('Your Name', 'rangin')
    );
    
    $fields['email'] = sprintf(
        '<div class="form-group"><label for="email" class="block text-sm font-medium mb-2">%s%s</label><input id="email" name="email" %s value="%s" size="30" maxlength="100" aria-describedby="email-notes"%s class="w-full px-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200" placeholder="%s"></div></div>',
        __('Email', 'rangin'),
        ($req ? ' <span class="text-red-500">*</span>' : ''),
        ($html5 ? 'type="email"' : 'type="text"'),
        esc_attr($commenter['comment_author_email']),
        $html_req,
        __('your@email.com', 'rangin')
    );
    
    $fields['url'] = sprintf(
        '<div class="form-group mb-6"><label for="url" class="block text-sm font-medium mb-2">%s</label><input id="url" name="url" %s value="%s" size="30" maxlength="200" class="w-full px-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200" placeholder="%s"></div>',
        __('Website', 'rangin'),
        ($html5 ? 'type="url"' : 'type="text"'),
        esc_attr($commenter['comment_author_url']),
        __('https://yourwebsite.com (optional)', 'rangin')
    );
    
    return $fields;
}
add_filter('comment_form_default_fields', 'personal_website_comment_form_fields');
