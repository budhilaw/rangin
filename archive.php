<?php get_header(); ?>

<main id="main" class="main-content">
    <div class="container mx-auto px-4 py-20">
        <!-- Archive Header -->
        <header class="text-center mb-16 animate-on-scroll">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                <?php
                if (is_category()) {
                    single_cat_title();
                } elseif (is_tag()) {
                    single_tag_title();
                } elseif (is_author()) {
                    echo 'Posts by ' . get_the_author();
                } elseif (is_date()) {
                    if (is_year()) {
                        echo 'Posts from ' . get_the_date('Y');
                    } elseif (is_month()) {
                        echo 'Posts from ' . get_the_date('F Y');
                    } else {
                        echo 'Posts from ' . get_the_date();
                    }
                } else {
                    echo 'Blog Archive';
                }
                ?>
            </h1>
            <?php if (is_category() && category_description()): ?>
                <p class="text-xl text-neutral-700 dark:text-neutral-300 max-w-3xl mx-auto">
                    <?php echo category_description(); ?>
                </p>
            <?php endif; ?>
        </header>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>
                    <article class="blog-card card animate-on-scroll">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="h-48 overflow-hidden">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('blog-thumb', array('class' => 'w-full h-full object-cover hover:scale-110 transition-transform duration-300')); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between text-sm text-neutral-500 dark:text-neutral-400 mb-2">
                                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                <span><?php echo reading_time(); ?> min read</span>
                            </div>
                            
                            <h2 class="text-xl font-semibold mb-3 line-clamp-2">
                                <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <p class="text-neutral-700 dark:text-neutral-300 mb-4 line-clamp-3">
                                <?php echo get_the_excerpt(); ?>
                            </p>
                            
                            <!-- Tags (shown first on mobile, after read more on desktop) -->
                            <?php if (has_category()): ?>
                                <div class="flex flex-wrap gap-1 mb-4 md:hidden">
                                    <?php
                                    $categories = get_the_category();
                                    foreach ($categories as $category):
                                    ?>
                                        <span class="px-2 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded text-xs">
                                            <?php echo $category->name; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex items-center justify-between">
                                <a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium inline-flex items-center">
                                    Read More
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                                
                                <!-- Tags (hidden on mobile, shown on desktop) -->
                                <?php if (has_category()): ?>
                                    <div class="hidden md:flex flex-wrap gap-1">
                                        <?php
                                        $categories = get_the_category();
                                        foreach ($categories as $category):
                                        ?>
                                            <span class="px-2 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded text-xs">
                                                <?php echo $category->name; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-16">
                    <h2 class="text-2xl font-semibold mb-4">No posts found</h2>
                    <p class="text-neutral-700 dark:text-neutral-300 mb-8">Sorry, no posts were found for this archive.</p>
                    <a href="<?php echo home_url(); ?>" class="btn btn-primary">Back to Home</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (have_posts()): ?>
            <div class="mt-16 flex justify-center animate-on-scroll">
                <?php
                echo paginate_links(array(
                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                    'type' => 'list',
                    'class' => 'pagination flex items-center space-x-2'
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
