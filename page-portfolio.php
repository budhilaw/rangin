<?php
/**
 * Template Name: Portfolio Page
 * 
 * Custom template for Portfolio page displaying portfolio items
 * 
 * @package PersonalWebsite
 */

get_header(); ?>

<main id="main" class="main-content">
    <!-- Portfolio Hero Section -->
    <section class="py-20 bg-gradient-to-r from-primary-50 to-secondary-50 dark:from-primary-900 dark:to-secondary-900">
        <div class="container mx-auto px-4">
            <div class="text-center animate-on-scroll">
                <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo esc_html(get_portfolio_section_title()); ?></h1>
                <?php if (get_portfolio_section_description()): ?>
                    <p class="text-xl text-neutral-600 dark:text-neutral-400 max-w-3xl mx-auto mb-8">
                        <?php echo esc_html(get_portfolio_section_description()); ?>
                    </p>
                <?php endif; ?>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto"></div>
            </div>
        </div>
    </section>

    <!-- Portfolio Filter & Grid Section -->
    <section class="py-20 bg-white dark:bg-neutral-900">
        <div class="container mx-auto px-4">
            <?php
            // Get all portfolio items
            $portfolio_query = new WP_Query(array(
                'post_type' => 'portfolio',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ));

            if ($portfolio_query->have_posts()):
                // Get taxonomy terms for filter buttons
                $portfolio_terms = get_terms(array(
                    'taxonomy' => 'portfolio_category',
                    'hide_empty' => true,
                ));
            ?>
                
                <!-- Filter Buttons -->
                <?php if (!is_wp_error($portfolio_terms) && !empty($portfolio_terms)): ?>
                <div class="text-center mb-12">
                    <div class="inline-flex flex-wrap justify-center gap-2 p-2 bg-neutral-100 dark:bg-neutral-800 rounded-lg">
                        <button class="portfolio-filter-btn active px-6 py-2 rounded-md font-medium transition-all duration-300 bg-primary-600 text-white" data-filter="all">
                            All Projects
                        </button>
                        <?php foreach ($portfolio_terms as $term): ?>
                        <button class="portfolio-filter-btn px-6 py-2 rounded-md font-medium transition-all duration-300 text-neutral-700 dark:text-neutral-300 hover:bg-primary-100 dark:hover:bg-primary-900" data-filter="<?php echo esc_attr($term->slug); ?>">
                            <?php echo esc_html($term->name); ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Portfolio Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="portfolio-grid">
                    <?php
                    $portfolio_query = new WP_Query(array(
                        'post_type' => 'portfolio',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));

                    while ($portfolio_query->have_posts()):
                        $portfolio_query->the_post();
                        
                        $category = get_portfolio_category(); // primary category slug (first term)
                        $category_color = get_portfolio_category_color($category);
                        $terms = get_the_terms(get_the_ID(), 'portfolio_category');
                        $term_slugs = array();
                        if ($terms && !is_wp_error($terms)) {
                            foreach ($terms as $t) { $term_slugs[] = $t->slug; }
                        }
                        $demo_link = get_portfolio_demo_link();
                        $github_link = get_portfolio_github_link();
                        $technologies = get_portfolio_technologies();
                        
                        // Convert technologies string to array
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
                    
                    <article class="portfolio-item card overflow-hidden animate-on-scroll" data-category="<?php echo esc_attr($category); ?>" data-categories="<?php echo esc_attr(implode(',', $term_slugs)); ?>">
                        <?php if (has_post_thumbnail()): ?>
                        <div class="relative overflow-hidden group">
                            <?php the_post_thumbnail('large', array('class' => 'w-full h-64 object-cover transition-transform duration-300 group-hover:scale-110')); ?>
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="flex space-x-4">
                                    <?php if ($demo_link): ?>
                                    <a href="<?php echo esc_url($demo_link); ?>" target="_blank" rel="noopener noreferrer" 
                                       class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Demo
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($github_link): ?>
                                    <a href="<?php echo esc_url($github_link); ?>" target="_blank" rel="noopener noreferrer" 
                                       class="bg-neutral-800 hover:bg-neutral-900 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center">
                                        <i class="fab fa-github mr-2"></i>
                                        Code
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- Fallback gradient background if no featured image -->
                        <div class="h-64 bg-gradient-to-br from-primary-400 to-secondary-500 flex items-center justify-center">
                            <i class="fas fa-code text-4xl text-white opacity-50"></i>
                        </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <!-- Category Badge -->
                            <?php if ($category): ?>
                            <div class="mb-3">
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full <?php echo esc_attr($category_bg_class . ' ' . $category_text_class); ?>">
                                    <?php echo esc_html(get_portfolio_category_name($category)); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Title -->
                            <h3 class="text-xl font-bold mb-3 text-neutral-900 dark:text-neutral-100">
                                <?php the_title(); ?>
                            </h3>
                            
                            <!-- Description -->
                            <p class="text-neutral-600 dark:text-neutral-400 mb-4 line-clamp-3">
                                <?php echo get_the_excerpt() ?: wp_trim_words(get_the_content(), 20, '...'); ?>
                            </p>
                            
                            <!-- Technologies -->
                            <?php if (!empty($tech_array)): ?>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <?php foreach ($tech_array as $tech): ?>
                                <span class="px-2 py-1 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-xs rounded-md">
                                    <?php echo esc_html($tech); ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                                <?php if ($demo_link): ?>
                                <a href="<?php echo esc_url($demo_link); ?>" target="_blank" rel="noopener noreferrer" 
                                   class="flex-1 min-w-0 text-center bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Live Demo
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($github_link): ?>
                                <a href="<?php echo esc_url($github_link); ?>" target="_blank" rel="noopener noreferrer" 
                                   class="flex-1 min-w-0 text-center border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                                    <i class="fab fa-github mr-2"></i>
                                    View Code
                                </a>
                                <?php endif; ?>
                                
                                <?php if (!$demo_link && !$github_link): ?>
                                <div class="flex-1 text-center py-2">
                                    <span class="text-sm text-neutral-500 dark:text-neutral-400">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Links coming soon
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                    
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>

            <?php else: ?>
                <!-- No Portfolio Items -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-folder-open text-6xl text-neutral-400 mb-6"></i>
                        <h3 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100 mb-4">No Portfolio Items Yet</h3>
                        <p class="text-neutral-600 dark:text-neutral-400 mb-6">
                            Portfolio items will appear here once they are added from the WordPress admin.
                        </p>
                        <?php if (current_user_can('edit_posts')): ?>
                        <a href="<?php echo admin_url('post-new.php?post_type=portfolio'); ?>" 
                           class="btn btn-primary px-6 py-3 font-semibold inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add Portfolio Item
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact CTA Section -->
    <section class="py-20 bg-neutral-900 dark:bg-neutral-950 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-4xl font-bold mb-6 text-white">Like What You See?</h2>
                <p class="text-xl text-neutral-300 mb-8">
                    Let's work together to bring your next project to life
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="mailto:<?php echo esc_attr(get_contact_email()); ?>?subject=Project%20Inquiry" 
                       class="bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-flex items-center justify-center text-lg">
                        <i class="fas fa-envelope mr-2"></i>
                        Start a Project
                    </a>
                    <a href="<?php echo esc_url(home_url('/about')); ?>" 
                       class="border-2 border-white text-white hover:bg-white hover:text-neutral-900 px-8 py-4 rounded-lg font-semibold transition-all duration-300 inline-flex items-center justify-center text-lg">
                        <i class="fas fa-user mr-2"></i>
                        Learn More About Me
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Portfolio filtering functionality
    const filterButtons = document.querySelectorAll('.portfolio-filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-primary-600', 'text-white');
                btn.classList.add('text-neutral-700', 'dark:text-neutral-300');
            });
            
            this.classList.add('active', 'bg-primary-600', 'text-white');
            this.classList.remove('text-neutral-700', 'dark:text-neutral-300');
            
            // Filter portfolio items
            portfolioItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                const itemCategories = (item.getAttribute('data-categories') || '').split(',').filter(Boolean);
                const matches = filter === 'all' || itemCategory === filter || itemCategories.includes(filter);

                if (matches) {
                    item.style.display = 'block';
                    // Add fade-in animation
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.opacity = '1';
                    }, 100);
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php get_footer(); ?>
