<?php get_header(); ?>

<main id="main" class="main-content">
    <!-- Blog Header -->
    <section class="py-20 bg-gradient-to-r from-primary-50 to-secondary-50 dark:from-primary-900 dark:to-secondary-900">
        <div class="container mx-auto px-4">
            <div class="text-center animate-on-scroll">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php 
                    if (is_home()) {
                        echo 'Blog';
                    } elseif (is_category()) {
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
                        echo 'Blog';
                    }
                    ?>
                </h1>
                <p class="text-xl text-neutral-700 dark:text-neutral-300 max-w-3xl mx-auto">
                    Insights, tutorials, and thoughts about software development and technology
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-6"></div>
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <?php if (have_posts()): ?>
                        <div class="space-y-8">
                            <?php while (have_posts()): the_post(); ?>
                                <article class="blog-card card animate-on-scroll">
                                    <?php if (has_post_thumbnail()): ?>
                                        <div class="h-64 overflow-hidden">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('large', array('class' => 'w-full h-full object-cover hover:scale-110 transition-transform duration-300')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="p-8">
                                        <!-- Post Meta -->
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-500 dark:text-neutral-400 mb-4">
                                            <time datetime="<?php echo get_the_date('c'); ?>">
                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                <?php echo get_the_date(); ?>
                                            </time>
                                            <span>
                                                <i class="fas fa-user mr-2"></i>
                                                <?php echo get_the_author_meta('display_name'); ?>
                                            </span>
                                            <?php if (has_category()): ?>
                                                <span class="flex items-center">
                                                    <i class="fas fa-folder mr-2"></i>
                                                    <?php
                                                    $categories = get_the_category();
                                                    if ($categories) {
                                                        $category_links = array();
                                                        foreach ($categories as $category) {
                                                            $category_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="inline-flex items-center rounded text-xs dark:hover:text-primary-400 hover:text-primary-600 transition-colors duration-200 font-medium">' . esc_html($category->name) . '</a>';
                                                        }
                                                        echo implode(' ', $category_links);
                                                    }
                                                    ?>
                                                </span>
                                            <?php endif; ?>
                                            <span>
                                                <i class="fas fa-clock mr-2"></i>
                                                <?php echo reading_time(); ?> min read
                                            </span>
                                        </div>
                                        
                                        <!-- Post Title -->
                                        <h2 class="text-2xl md:text-3xl font-bold mb-4">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                                <?php the_title(); ?>
                                            </a>
                                        </h2>
                                        
                                        <!-- Post Excerpt -->
                                        <div class="prose prose-neutral dark:prose-invert max-w-none mb-6">
                                            <?php the_excerpt(); ?>
                                        </div>
                                        
                                        <!-- Tags (shown first on mobile, after read more on desktop) -->
                                        <?php if (has_tag()): ?>
                                            <div class="flex flex-wrap gap-2 mb-4 md:hidden">
                                                <?php the_tags('<span class="px-2 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded text-xs hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">', '</span><span class="px-2 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded text-xs hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">', '</span>'); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Post Footer -->
                                        <div class="flex items-center justify-between">
                                            <a href="<?php the_permalink(); ?>" 
                                               class="btn btn-primary px-6 py-2 text-sm inline-flex items-center">
                                                Read More
                                                <i class="fas fa-arrow-right ml-2"></i>
                                            </a>
                                            
                                            <!-- Tags (hidden on mobile, shown on desktop) -->
                                            <?php if (has_tag()): ?>
                                                <div class="hidden md:flex flex-wrap gap-2">
                                                    <?php the_tags('<span class="px-2 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded text-xs hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">', '</span><span class="px-2 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded text-xs hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">', '</span>'); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12 flex justify-center animate-on-scroll">
                            <?php
                            echo paginate_links(array(
                                'prev_text' => '<i class="fas fa-chevron-left mr-2"></i>Previous',
                                'next_text' => 'Next<i class="fas fa-chevron-right ml-2"></i>',
                                'type' => 'list',
                                'class' => 'pagination flex items-center space-x-2'
                            ));
                            ?>
                        </div>
                        
                    <?php else: ?>
                        <div class="text-center py-16 animate-on-scroll">
                            <div class="w-24 h-24 bg-neutral-100 dark:bg-neutral-800 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-search text-2xl text-neutral-400"></i>
                            </div>
                            <h2 class="text-2xl font-bold mb-4">No posts found</h2>
                            <p class="text-neutral-700 dark:text-neutral-300 mb-8">
                                Sorry, no posts were found. Check back later for new content!
                            </p>
                            <a href="<?php echo home_url(); ?>" class="btn btn-primary">Back to Home</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="space-y-8 sticky top-24">
                        <?php if (is_active_sidebar('sidebar-1')): ?>
                            <?php dynamic_sidebar('sidebar-1'); ?>
                        <?php else: ?>
                            <!-- Default Widgets if no sidebar widgets are configured -->
                            
                            <!-- Search Widget -->
                            <div class="card p-6 animate-on-scroll">
                                <?php get_search_form(); ?>
                            </div>
                            
                            <!-- Recent Posts Widget -->
                            <div class="card p-6 animate-on-scroll">
                                <h3 class="text-xl font-semibold mb-4">Recent Posts</h3>
                                <div class="space-y-4">
                                    <?php
                                    $recent_posts = wp_get_recent_posts(array(
                                        'numberposts' => 5,
                                        'post_status' => 'publish'
                                    ));
                                    
                                    if ($recent_posts):
                                        foreach ($recent_posts as $post):
                                            $permalink = get_permalink($post['ID']);
                                        ?>
                                        <div class="flex items-start space-x-3">
                                            <?php if (has_post_thumbnail($post['ID'])): ?>
                                                <div class="w-16 h-16 flex-shrink-0 overflow-hidden rounded">
                                                    <?php echo get_the_post_thumbnail($post['ID'], 'thumbnail', array('class' => 'w-full h-full object-cover')); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="flex-1">
                                                <h4 class="font-medium text-sm leading-tight mb-1">
                                                    <a href="<?php echo $permalink; ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                                        <?php echo $post['post_title']; ?>
                                                    </a>
                                                </h4>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    <?php echo date('M j, Y', strtotime($post['post_date'])); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php 
                                        endforeach;
                                    else:
                                    ?>
                                        <p class="text-neutral-700 dark:text-neutral-300 text-sm">No recent posts available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Categories Widget -->
                            <div class="card p-6 animate-on-scroll">
                                <h3 class="text-xl font-semibold mb-4">Categories</h3>
                                <ul class="space-y-2">
                                    <?php
                                    $categories = get_categories(array(
                                        'orderby' => 'count',
                                        'order' => 'DESC',
                                        'number' => 10
                                    ));
                                    
                                    if ($categories):
                                        foreach ($categories as $category):
                                        ?>
                                        <li>
                                            <a href="<?php echo get_category_link($category->term_id); ?>" 
                                               class="flex items-center justify-between hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">
                                                <span><?php echo $category->name; ?></span>
                                                <span class="text-xs bg-neutral-100 dark:bg-neutral-800 px-2 py-1 rounded">
                                                    <?php echo $category->count; ?>
                                                </span>
                                            </a>
                                        </li>
                                        <?php 
                                        endforeach;
                                    else:
                                    ?>
                                        <li class="text-neutral-600 dark:text-neutral-400 text-sm">No categories available.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            
                            <!-- Tags Widget -->
                            <?php
                            $tags = get_tags(array(
                                'orderby' => 'count',
                                'order' => 'DESC',
                                'number' => 20
                            ));
                            
                            if ($tags):
                            ?>
                            <div class="card p-6 animate-on-scroll">
                                <h3 class="text-xl font-semibold mb-4">Popular Tags</h3>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($tags as $tag): ?>
                                        <a href="<?php echo get_tag_link($tag->term_id); ?>" 
                                           class="px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded-full text-sm hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">
                                            <?php echo $tag->name; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
