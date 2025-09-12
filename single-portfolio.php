<?php
/**
 * Single Portfolio Item Template
 * 
 * @package PersonalWebsite
 */

get_header(); ?>

<main id="main" class="main-content">
    <?php while (have_posts()): the_post(); 
        $category = get_portfolio_category();
        $category_color = get_portfolio_category_color($category);
        $demo_link = get_portfolio_demo_link();
        $github_link = get_portfolio_github_link();
        $technologies = get_portfolio_technologies();
        $tech_array = !empty($technologies) ? array_map('trim', explode(',', $technologies)) : array();
        
        // Color mapping for consistent styling
        $color_classes = array(
            'green' => array('bg' => 'bg-green-100 dark:bg-green-900', 'text' => 'text-green-800 dark:text-green-300'),
            'blue' => array('bg' => 'bg-blue-100 dark:bg-blue-900', 'text' => 'text-blue-800 dark:text-blue-300'),
            'purple' => array('bg' => 'bg-purple-100 dark:bg-purple-900', 'text' => 'text-purple-800 dark:text-purple-300'),
            'orange' => array('bg' => 'bg-orange-100 dark:bg-orange-900', 'text' => 'text-orange-800 dark:text-orange-300'),
            'red' => array('bg' => 'bg-red-100 dark:bg-red-900', 'text' => 'text-red-800 dark:text-red-300'),
            'teal' => array('bg' => 'bg-teal-100 dark:bg-teal-900', 'text' => 'text-teal-800 dark:text-teal-300'),
            'indigo' => array('bg' => 'bg-indigo-100 dark:bg-indigo-900', 'text' => 'text-indigo-800 dark:text-indigo-300'),
            'pink' => array('bg' => 'bg-pink-100 dark:bg-pink-900', 'text' => 'text-pink-800 dark:text-pink-300'),
            'gray' => array('bg' => 'bg-gray-100 dark:bg-gray-900', 'text' => 'text-gray-800 dark:text-gray-300'),
        );
        
        $category_bg_class = $color_classes[$category_color]['bg'] ?? $color_classes['blue']['bg'];
        $category_text_class = $color_classes[$category_color]['text'] ?? $color_classes['blue']['text'];
    ?>
    
    <!-- Portfolio Item Hero -->
    <section class="py-20 bg-neutral-50 dark:bg-neutral-850">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Back to Portfolio Link -->
                <div class="mb-8">
                    <a href="<?php echo esc_url(home_url('/portfolio')); ?>" 
                       class="inline-flex items-center text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Portfolio
                    </a>
                </div>
                
                <div class="text-center mb-12">
                    <!-- Category Badge -->
                    <?php if ($category): ?>
                    <div class="mb-4">
                        <span class="inline-block px-4 py-2 text-sm font-medium rounded-full <?php echo esc_attr($category_bg_class . ' ' . $category_text_class); ?>">
                            <?php echo esc_html(get_portfolio_category_name($category)); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Title -->
                    <h1 class="text-4xl md:text-5xl font-bold mb-6"><?php the_title(); ?></h1>
                    
                    <!-- Excerpt -->
                    <?php if (get_the_excerpt()): ?>
                        <p class="text-xl text-neutral-700 dark:text-neutral-300 max-w-3xl mx-auto mb-8">
                            <?php echo get_the_excerpt(); ?>
                        </p>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                        <?php if ($demo_link): ?>
                        <a href="<?php echo esc_url($demo_link); ?>" target="_blank" rel="noopener noreferrer" 
                           class="btn btn-primary px-8 py-3 font-semibold inline-flex items-center justify-center">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            View Live Demo
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($github_link): ?>
                        <a href="<?php echo esc_url($github_link); ?>" target="_blank" rel="noopener noreferrer" 
                           class="btn btn-outline px-8 py-3 font-semibold inline-flex items-center justify-center">
                            <i class="fab fa-github mr-2"></i>
                            View Source Code
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()): ?>
    <section class="py-12 bg-white dark:bg-neutral-900">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="relative overflow-hidden rounded-xl shadow-2xl">
                    <?php the_post_thumbnail('full', array('class' => 'w-full h-auto')); ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Content & Technologies -->
    <section class="py-20 bg-neutral-50 dark:bg-neutral-850">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <div class="prose prose-lg prose-neutral dark:prose-invert max-w-none">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    
                    <!-- Sidebar Info -->
                    <div class="space-y-8">
                        <!-- Technologies Used -->
                        <?php if (!empty($tech_array)): ?>
                        <div class="card p-6">
                            <h3 class="text-xl font-bold mb-4 text-neutral-900 dark:text-neutral-100">
                                <i class="fas fa-tools mr-2 text-primary-600"></i>
                                Technologies
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($tech_array as $tech): ?>
                                <span class="px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-300 text-sm rounded-full font-medium">
                                    <?php echo esc_html($tech); ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Project Links -->
                        <?php if ($demo_link || $github_link): ?>
                        <div class="card p-6">
                            <h3 class="text-xl font-bold mb-4 text-neutral-900 dark:text-neutral-100">
                                <i class="fas fa-link mr-2 text-primary-600"></i>
                                Links
                            </h3>
                            <div class="space-y-3">
                                <?php if ($demo_link): ?>
                                <a href="<?php echo esc_url($demo_link); ?>" target="_blank" rel="noopener noreferrer" 
                                   class="flex items-center p-3 bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/30 rounded-lg transition-colors group">
                                    <i class="fas fa-external-link-alt text-primary-600 mr-3"></i>
                                    <div>
                                        <div class="font-medium text-neutral-900 dark:text-neutral-100">Live Demo</div>
                                        <div class="text-sm text-neutral-700 dark:text-neutral-300 group-hover:text-primary-600 dark:group-hover:text-primary-400">View the live application</div>
                                    </div>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($github_link): ?>
                                <a href="<?php echo esc_url($github_link); ?>" target="_blank" rel="noopener noreferrer" 
                                   class="flex items-center p-3 bg-neutral-50 dark:bg-neutral-800 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-colors group">
                                    <i class="fab fa-github text-neutral-700 dark:text-neutral-300 mr-3"></i>
                                    <div>
                                        <div class="font-medium text-neutral-900 dark:text-neutral-100">Source Code</div>
                                        <div class="text-sm text-neutral-700 dark:text-neutral-300 group-hover:text-neutral-800 dark:group-hover:text-neutral-200">View on GitHub</div>
                                    </div>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Project Details -->
                        <div class="card p-6">
                            <h3 class="text-xl font-bold mb-4 text-neutral-900 dark:text-neutral-100">
                                <i class="fas fa-info-circle mr-2 text-primary-600"></i>
                                Details
                            </h3>
                            <div class="space-y-3">
                                <?php if ($category): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-neutral-700 dark:text-neutral-300">Category:</span>
                                    <span class="font-medium text-neutral-900 dark:text-neutral-100">
                                        <?php echo esc_html(get_portfolio_category_name($category)); ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-neutral-700 dark:text-neutral-300">Published:</span>
                                    <span class="font-medium text-neutral-900 dark:text-neutral-100">
                                        <?php echo get_the_date(); ?>
                                    </span>
                                </div>
                                
                                <?php if ($demo_link): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-neutral-700 dark:text-neutral-300">Status:</span>
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-sm rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Live
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Projects -->
    <?php
    // Check total number of portfolio posts
    $total_portfolio_count = wp_count_posts('portfolio')->publish;
    
    // Only show "More Projects" section if there's more than 1 portfolio post
    if ($total_portfolio_count > 1):
    ?>
    <section class="py-20 bg-white dark:bg-neutral-900">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">More Projects</h2>
                    <p class="text-neutral-700 dark:text-neutral-300">
                        Check out some of my other work
                    </p>
                </div>
                
                <?php
                // Get related portfolio items (same category or random)
                $related_query = new WP_Query(array(
                    'post_type' => 'portfolio',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'orderby' => 'rand'
                ));
                
                if ($related_query->have_posts()): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($related_query->have_posts()): $related_query->the_post(); 
                        $rel_category = get_portfolio_category();
                        $rel_category_color = get_portfolio_category_color($rel_category);
                        $rel_demo_link = get_portfolio_demo_link();
                        $rel_github_link = get_portfolio_github_link();
                        
                        $rel_category_bg_class = $color_classes[$rel_category_color]['bg'] ?? $color_classes['blue']['bg'];
                        $rel_category_text_class = $color_classes[$rel_category_color]['text'] ?? $color_classes['blue']['text'];
                    ?>
                    <article class="card overflow-hidden animate-on-scroll">
                        <?php if (has_post_thumbnail()): ?>
                        <div class="relative overflow-hidden group">
                            <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110')); ?>
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <a href="<?php the_permalink(); ?>" 
                                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    View Project
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <?php if ($rel_category): ?>
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full mb-2 <?php echo esc_attr($rel_category_bg_class . ' ' . $rel_category_text_class); ?>">
                                <?php echo esc_html(get_portfolio_category_name($rel_category)); ?>
                            </span>
                            <?php endif; ?>
                            
                            <h3 class="text-lg font-bold mb-2">
                                <a href="<?php the_permalink(); ?>" class="text-neutral-900 dark:text-neutral-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <p class="text-neutral-700 dark:text-neutral-300 text-sm line-clamp-2 mb-4">
                                <?php echo get_the_excerpt() ?: wp_trim_words(get_the_content(), 15, '...'); ?>
                            </p>
                            
                            <div class="flex justify-between items-center">
                                <a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium text-sm">
                                    View Details →
                                </a>
                                
                                <div class="flex space-x-2">
                                    <?php if ($rel_demo_link): ?>
                                    <a href="<?php echo esc_url($rel_demo_link); ?>" target="_blank" 
                                       class="text-neutral-500 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-primary-400">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($rel_github_link): ?>
                                    <a href="<?php echo esc_url($rel_github_link); ?>" target="_blank" 
                                       class="text-neutral-500 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-primary-400">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
                
                <div class="text-center mt-12">
                    <a href="<?php echo esc_url(home_url('/portfolio')); ?>" 
                       class="btn btn-primary px-8 py-3 font-semibold inline-flex items-center">
                        View All Projects
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
