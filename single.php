<?php get_header(); ?>

<main id="main" class="main-content">
    <div class="container mx-auto px-4 py-20">
        <?php while (have_posts()): the_post(); ?>
            <article class="max-w-4xl mx-auto">
                <!-- Post Header -->
                <header class="text-center mb-12 animate-on-scroll">
                    <div class="mb-6">
                        <?php the_category(); ?>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6"><?php the_title(); ?></h1>
                    <div class="flex flex-wrap justify-center items-center gap-4 text-neutral-600 dark:text-neutral-400">
                        <time datetime="<?php echo get_the_date('c'); ?>">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <?php echo get_the_date(); ?>
                        </time>
                        <span>
                            <i class="fas fa-clock mr-2"></i>
                            <?php echo reading_time(); ?> min read
                        </span>
                        <span>
                            <i class="fas fa-user mr-2"></i>
                            <?php echo get_the_author(); ?>
                        </span>
                    </div>
                </header>

                <!-- Featured Image -->
                <?php if (has_post_thumbnail()): ?>
                    <div class="mb-12 animate-on-scroll">
                        <?php the_post_thumbnail('large', array('class' => 'w-full rounded-xl shadow-lg')); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Content -->
                <div class="prose prose-lg prose-neutral dark:prose-invert max-w-none animate-on-scroll">
                    <?php the_content(); ?>
                </div>

                <!-- Post Tags -->
                <?php if (has_tag()): ?>
                    <div class="mt-12 animate-on-scroll">
                        <h3 class="text-lg font-semibold mb-4">Tags:</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php the_tags('<span class="px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded-full text-sm">', '</span><span class="px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded-full text-sm">', '</span>'); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Author Bio -->
                <div class="mt-16 p-8 card animate-on-scroll">
                    <div class="flex items-start space-x-6">
                        <img src="<?php echo get_avatar_url(get_the_author_meta('ID')); ?>" 
                             alt="<?php echo get_the_author(); ?>" 
                             class="w-20 h-20 rounded-full">
                        <div>
                            <h3 class="text-xl font-semibold mb-2"><?php echo get_the_author(); ?></h3>
                            <p class="text-neutral-600 dark:text-neutral-400 mb-4">
                                <?php echo get_the_author_meta('description') ?: 'Software Engineer passionate about creating innovative digital solutions.'; ?>
                            </p>
                            <div class="flex space-x-4">
                                <?php if (get_social_linkedin()): ?>
                                <a href="<?php echo esc_url(get_social_linkedin()); ?>" 
                                   class="text-neutral-600 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-primary-400">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (get_social_twitter()): ?>
                                <a href="<?php echo esc_url(get_social_twitter()); ?>" 
                                   class="text-neutral-600 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-primary-400">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (get_social_github()): ?>
                                <a href="<?php echo esc_url(get_social_github()); ?>" 
                                   class="text-neutral-600 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-primary-400">
                                    <i class="fab fa-github"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post Navigation -->
                <div class="mt-16 animate-on-scroll">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php 
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        
                        if ($prev_post): ?>
                            <a href="<?php echo get_permalink($prev_post); ?>" class="group">
                                <div class="card p-6 h-full hover:shadow-lg transition-all duration-300">
                                    <div class="text-sm text-primary-600 mb-2">
                                        <i class="fas fa-arrow-left mr-2"></i>Previous Post
                                    </div>
                                    <h3 class="font-semibold group-hover:text-primary-600 transition-colors">
                                        <?php echo get_the_title($prev_post); ?>
                                    </h3>
                                </div>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($next_post): ?>
                            <a href="<?php echo get_permalink($next_post); ?>" class="group">
                                <div class="card p-6 h-full hover:shadow-lg transition-all duration-300">
                                    <div class="text-sm text-primary-600 mb-2 text-right">
                                        Next Post<i class="fas fa-arrow-right ml-2"></i>
                                    </div>
                                    <h3 class="font-semibold group-hover:text-primary-600 transition-colors text-right">
                                        <?php echo get_the_title($next_post); ?>
                                    </h3>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                        <!-- Comments -->
        <?php if (comments_open() || get_comments_number()): ?>
            <div class="mt-16 animate-on-scroll" id="comments-wrapper">
                <!-- Comments Toggle Button -->
                <div class="text-center py-8">
                    <button id="comments-toggle" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-lg hover:from-primary-600 hover:to-accent-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-comments mr-3 text-lg"></i>
                        <span class="font-medium">
                            <span id="comments-toggle-text">Show Comments</span>
                            <?php if (get_comments_number() > 0): ?>
                                <span class="ml-2 px-2 py-1 bg-white/20 rounded-full text-sm">
                                    <?php echo get_comments_number(); ?>
                                </span>
                            <?php endif; ?>
                        </span>
                        <i class="fas fa-chevron-down ml-3 transition-transform duration-200" id="comments-toggle-icon"></i>
                    </button>
                </div>

                <!-- Comments Content (hidden by default) -->
                <div id="comments-content" class="comments-content">
                    <?php comments_template(); ?>
                </div>
            </div>
        <?php endif; ?>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
