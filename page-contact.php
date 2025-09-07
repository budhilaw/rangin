<?php
/**
 * Template Name: Contact Page
 *
 * A clean, professional contact page that highlights your contact
 * information and social links. No custom form is included.
 */

get_header(); ?>

<main id="main" class="main-content">
    <!-- Contact Hero -->
    <section class="py-20 bg-gradient-to-r from-primary-50 to-secondary-50 dark:from-primary-900 dark:to-secondary-900">
        <div class="container mx-auto px-4">
            <div class="text-center animate-on-scroll">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Contact</h1>
                <?php if (has_contact_section_description()): ?>
                    <p class="text-xl text-neutral-600 dark:text-neutral-400 max-w-3xl mx-auto mb-8">
                        <?php echo esc_html(get_contact_section_description()); ?>
                    </p>
                <?php endif; ?>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto"></div>
            </div>
        </div>
    </section>

    <!-- Contact Info + Socials -->
    <section class="py-16">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="grid grid-cols-1 items-center">
                <!-- Contact Card -->
                <div class="lg:col-span-1 card p-8 mb-4">
                    <h2 class="text-2xl font-bold mb-6 flex items-center">
                        <i class="fas fa-envelope-open-text text-primary-500 mr-3"></i>
                        <?php _e('Contact Information', 'rangin'); ?>
                    </h2>

                    <ul class="space-y-4 text-neutral-700 dark:text-neutral-300">
                        <?php $email = function_exists('get_front_contact_email') ? get_front_contact_email() : get_contact_email(); ?>
                        <?php if (!empty($email)): ?>
                        <li class="flex items-start">
                            <i class="fas fa-envelope text-primary-500 w-6 mt-1"></i>
                            <div>
                                <div class="text-sm uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php _e('Email', 'rangin'); ?></div>
                                <a href="mailto:<?php echo esc_attr($email); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                    <?php echo esc_html($email); ?>
                                </a>
                            </div>
                        </li>
                        <?php endif; ?>

                        <?php $phone = function_exists('get_front_contact_phone') ? get_front_contact_phone() : get_contact_phone(); ?>
                        <?php if (!empty($phone)): ?>
                        <li class="flex items-start">
                            <i class="fas fa-phone text-primary-500 w-6 mt-1"></i>
                            <div>
                                <div class="text-sm uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php _e('Phone', 'rangin'); ?></div>
                                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                    <?php echo esc_html($phone); ?>
                                </a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php if (!empty($email)): ?>
                            <a href="mailto:<?php echo esc_attr($email); ?>?subject=Project%20Inquiry" class="btn btn-primary w-full inline-flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                <?php _e('Send Email', 'rangin'); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($phone)): ?>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="btn btn-secondary w-full inline-flex items-center justify-center">
                                <i class="fas fa-phone mr-2"></i>
                                <?php _e('Call', 'rangin'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Content + Socials -->
                <div class="lg:col-span-2">
                    <?php if (have_posts()): while (have_posts()): the_post(); ?>
                        <?php if (get_the_content()): ?>
                            <div class="card p-8 mb-8">
                                <div class="prose prose-neutral dark:prose-invert max-w-none">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; endif; wp_reset_postdata(); ?>

                    <div class="card p-8">
                        <h3 class="text-xl font-semibold mb-6 flex items-center">
                            <i class="fas fa-share-alt text-accent-500 mr-3"></i>
                            <?php _e('Connect on Social Media', 'rangin'); ?>
                        </h3>

                        <div class="flex flex-wrap items-center gap-3">
                            <?php
                                $links = array(
                                    array('fn' => 'get_social_linkedin',  'icon' => 'fab fa-linkedin',  'label' => __('LinkedIn', 'rangin')),
                                    array('fn' => 'get_social_github',    'icon' => 'fab fa-github',    'label' => __('GitHub', 'rangin')),
                                    array('fn' => 'get_social_gitlab',    'icon' => 'fab fa-gitlab',    'label' => __('GitLab', 'rangin')),
                                    array('fn' => 'get_social_x',         'icon' => 'fab fa-x-twitter', 'label' => __('X', 'rangin')),
                                    array('fn' => 'get_social_twitter',   'icon' => 'fab fa-twitter',   'label' => __('Twitter', 'rangin')),
                                    array('fn' => 'get_social_facebook',  'icon' => 'fab fa-facebook-f','label' => __('Facebook', 'rangin')),
                                    array('fn' => 'get_social_instagram', 'icon' => 'fab fa-instagram', 'label' => __('Instagram', 'rangin')),
                                    array('fn' => 'get_social_threads',   'icon' => 'fab fa-threads',   'label' => __('Threads', 'rangin')),
                                );
                                foreach ($links as $l) {
                                    if (function_exists($l['fn'])) {
                                        $url = call_user_func($l['fn']);
                                        if (!empty($url)) {
                                            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 rounded-md bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-neutral-800 dark:text-neutral-200 transition-colors">';
                                            echo '<i class="' . esc_attr($l['icon']) . ' mr-2"></i>' . esc_html($l['label']);
                                            echo '</a>';
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer();

