<?php
/**
 * Comments Template
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Return early if post is password protected
if (post_password_required()) {
    return;
}

$comment_count = get_comments_number();
?>

<div id="comments" class="comments-section">
    <?php if ($comment_count > 0): ?>
        <div class="comments-header mb-8">
            <h3 class="text-2xl font-bold flex items-center">
                <i class="fas fa-comments mr-3 text-primary-500"></i>
                <?php
                /* translators: 1: comment count number, 2: title. */
                comments_number(__('Leave a Comment', 'rangin'), __('One Comment', 'rangin'), __('% Comments', 'rangin'));
                ?>
            </h3>
            <div class="w-16 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mt-2"></div>
        </div>

        <!-- Comments List -->
        <div class="comments-list mb-12">
            <ol class="comment-list space-y-6">
                <?php
                wp_list_comments(array(
                    'walker'            => new Personal_Website_Comment_Walker(),
                    'style'             => 'ol',
                    'callback'          => null,
                    'end-callback'      => null,
                    'type'              => 'all',
                    'page'              => '',
                    'per_page'          => '',
                    'avatar_size'       => 64,
                    'reverse_top_level' => null,
                    'reverse_children'  => null,
                    'format'            => 'html5',
                    'short_ping'        => false,
                    'echo'              => true,
                    'moderation'        => __('Your comment is awaiting moderation.', 'rangin'),
                ));
                ?>
            </ol>

            <?php
            // Comment pagination
            if (get_comment_pages_count() > 1 && get_option('page_comments')):
            ?>
                <div class="comments-pagination mt-8 flex justify-center">
                    <?php
                    paginate_comments_links(array(
                        'prev_text' => '<i class="fas fa-chevron-left mr-2"></i>' . __('Previous', 'rangin'),
                        'next_text' => __('Next', 'rangin') . '<i class="fas fa-chevron-right ml-2"></i>',
                        'type'      => 'list',
                    ));
                    ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Comment Form -->
    <?php if (comments_open()): ?>
        <div class="comment-respond card p-8">
            <h3 class="comment-reply-title text-2xl font-bold mb-6 flex items-center">
                <i class="fas fa-edit mr-3 text-accent-500"></i>
                <?php comment_form_title(__('Leave a Reply', 'rangin'), __('Leave a Reply to %s', 'rangin')); ?>
            </h3>
            <div class="w-16 h-1 bg-gradient-to-r from-accent-500 to-secondary-500 mb-6"></div>

            <?php
            $fields = array(
                'author' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="form-group">
                                    <label for="author" class="block text-sm font-medium mb-2">' . __('Name', 'rangin') . ' <span class="text-red-500">*</span></label>
                                    <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245" required class="w-full px-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200" placeholder="' . __('Your Name', 'rangin') . '">
                                </div>',
                'email'  => '   <div class="form-group">
                                    <label for="email" class="block text-sm font-medium mb-2">' . __('Email', 'rangin') . ' <span class="text-red-500">*</span></label>
                                    <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes" required class="w-full px-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200" placeholder="' . __('your@email.com', 'rangin') . '">
                                </div>
                            </div>',
                'url'    => '<div class="form-group mb-6">
                                <label for="url" class="block text-sm font-medium mb-2">' . __('Website', 'rangin') . '</label>
                                <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" class="w-full px-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200" placeholder="' . __('https://yourwebsite.com (optional)', 'rangin') . '">
                            </div>',
            );

            $args = array(
                'fields'                => $fields,
                'comment_field'         => '<div class="form-group mb-6">
                                                <label for="comment" class="block text-sm font-medium mb-2">' . __('Comment', 'rangin') . ' <span class="text-red-500">*</span></label>
                                                <textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required class="w-full px-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 resize-vertical" placeholder="' . __('Share your thoughts...', 'rangin') . '"></textarea>
                                            </div>',
                'must_log_in'           => '<p class="must-log-in bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">' . sprintf(__('You must be <a href="%s" class="text-primary-600 hover:text-primary-800 underline">logged in</a> to post a comment.', 'rangin'), wp_login_url(apply_filters('the_permalink', get_permalink($post->ID)))) . '</p>',
                'logged_in_as'          => '<p class="logged-in-as bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">' . sprintf(__('Logged in as <a href="%1$s" class="text-primary-600 hover:text-primary-800 underline font-medium">%2$s</a>. <a href="%3$s" title="Log out of this account" class="text-red-600 hover:text-red-800 underline">Log out?</a>', 'rangin'), get_edit_user_link(), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink($post->ID)))) . '</p>',
                'comment_notes_before'  => '<p class="comment-notes text-sm text-neutral-600 dark:text-neutral-400 mb-6">' . __('Your email address will not be published. Required fields are marked *', 'rangin') . '</p>',
                'comment_notes_after'   => '',
                'id_form'               => 'commentform',
                'id_submit'             => 'submit',
                'class_form'            => 'comment-form',
                'class_submit'          => 'btn btn-primary px-8 py-3 font-semibold inline-flex items-center',
                'name_submit'           => 'submit',
                'title_reply'           => '',
                'title_reply_to'        => '',
                'title_reply_before'    => '',
                'title_reply_after'     => '',
                'cancel_reply_before'   => '<div class="cancel-reply-link-wrapper mb-4">',
                'cancel_reply_after'    => '</div>',
                'cancel_reply_link'     => __('Cancel reply', 'rangin'),
                'label_submit'          => __('Post Comment', 'rangin'),
                'submit_button'         => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s"><i class="fas fa-paper-plane mr-2"></i>%4$s</button>',
                'submit_field'          => '<div class="form-submit">%1$s %2$s</div>',
                'format'                => 'html5',
            );

            comment_form($args);
            ?>

            <?php cancel_comment_reply_link('<i class="fas fa-times mr-2"></i>' . __('Cancel Reply', 'rangin'), '', 'inline-flex items-center text-sm text-neutral-600 hover:text-red-600 dark:text-neutral-400 dark:hover:text-red-400 transition-colors bg-neutral-100 dark:bg-neutral-800 px-3 py-1 rounded-md'); ?>
        </div>
    <?php elseif (!comments_open() && $comment_count > 0): ?>
        <div class="comments-closed card p-6 text-center">
            <i class="fas fa-lock text-3xl text-neutral-400 mb-4"></i>
            <p class="text-neutral-600 dark:text-neutral-400">
                <?php _e('Comments are closed for this post.', 'rangin'); ?>
            </p>
        </div>
    <?php endif; ?>
</div>
