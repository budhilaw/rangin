<?php
/**
 * Front Page Template
 * 
 * This template is used for the site's front page, whether it displays the blog index or a static page.
 * 
 * @package PersonalWebsite
 */

get_header(); ?>

<main id="main" class="main-content">
    <!-- Hero Section -->
    <section class="hero-section min-h-screen flex items-center justify-center hero-gradient-light dark:hero-gradient-dark text-neutral-900 dark:text-neutral-50 relative overflow-hidden"
        <?php 
        $hero_bg_image = get_hero_background_image();
        if ($hero_bg_image): 
        ?>
            style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('<?php echo esc_url($hero_bg_image); ?>'); background-size: cover; background-position: center; background-attachment: fixed;"
        <?php endif; ?>>
        
        <div class="container mx-auto px-4 z-10">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in-up">
                    <?php echo esc_html(get_hero_greeting()); ?> <span class="text-gradient"><?php echo esc_html(get_personal_name()); ?></span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-neutral-600 dark:text-neutral-300 max-w-3xl mx-auto animate-fade-in-up animation-delay-200">
                    <?php echo esc_html(get_hero_description()); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-400">
                    <a href="<?php echo esc_url(get_hero_primary_cta_link()); ?>" 
                       class="btn btn-primary px-8 py-3 font-semibold transform hover:scale-105">
                        <?php echo esc_html(get_hero_primary_cta_text()); ?>
                    </a>
                    <a href="<?php echo esc_url(get_hero_secondary_cta_link()); ?>" 
                       class="btn btn-outline px-8 py-3 font-semibold">
                        <?php echo esc_html(get_hero_secondary_cta_text()); ?>
                    </a>
                </div>
            </div>
        </div>
        
        <?php if (get_hero_show_animated_bg()): ?>
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-neutral-50 dark:bg-neutral-850">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">About Me</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto"></div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-12 items-center max-w-6xl mx-auto">
                <div class="animate-on-scroll">
                    <?php $about_photo = get_about_me_photo(); ?>
                    <?php if ($about_photo): ?>
                        <div class="flex flex-col lg:flex-row gap-8 items-center">
                            <!-- Photo -->
                            <div class="lg:w-1/3 flex-shrink-0">
                                <div class="relative">
                                    <img src="<?php echo esc_url($about_photo); ?>" 
                                         alt="<?php echo esc_attr(get_personal_name()); ?>" 
                                         class="w-full max-w-sm mx-auto lg:mx-0 rounded-2xl shadow-2xl object-cover aspect-[3/4]">
                                    <!-- Decorative elements -->
                                    <div class="absolute -inset-4 bg-gradient-to-r from-primary-500/20 to-accent-500/20 rounded-2xl -z-10 blur-xl"></div>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div class="lg:w-2/3">
                                <div class="prose prose-lg prose-neutral dark:prose-invert max-w-none">
                                    <?php 
                                    $about_description = get_about_me_description();
                                    if ($about_description) {
                                        echo nl2br(esc_html($about_description));
                                    }
                                    ?>
                                </div>
                                
                                <!-- Professional Stats -->
                                <div class="grid grid-cols-2 gap-4 mt-8">
                                    <div class="text-center p-4 card">
                                        <div class="text-3xl font-bold text-primary-600"><?php echo esc_html(get_years_experience()); ?>+</div>
                                        <div class="text-neutral-600 dark:text-neutral-400">Years Experience</div>
                                    </div>
                                    <div class="text-center p-4 card">
                                        <div class="text-3xl font-bold text-accent-600"><?php echo esc_html(get_projects_completed()); ?>+</div>
                                        <div class="text-neutral-600 dark:text-neutral-400">Projects Completed</div>
                                    </div>
                                </div>
                                
                                <!-- CTA Buttons -->
                                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                                    <a href="<?php echo esc_url(get_about_me_photo() ? home_url('/portfolio') : '#portfolio'); ?>" 
                                       class="btn btn-primary px-6 py-3 font-semibold inline-flex items-center justify-center">
                                        <i class="fas fa-briefcase mr-2"></i>
                                        View Portfolio
                                    </a>
                                    <a href="mailto:<?php echo esc_attr(get_about_section_email()); ?>" 
                                       class="btn btn-outline px-6 py-3 font-semibold inline-flex items-center justify-center">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Get In Touch
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Fallback when no photo is uploaded -->
                        <div class="text-center">
                            <div class="prose prose-lg prose-neutral dark:prose-invert max-w-3xl mx-auto mb-8">
                                <?php 
                                $about_description = get_about_me_description();
                                if ($about_description) {
                                    echo nl2br(esc_html($about_description));
                                }
                                ?>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 max-w-md mx-auto mb-8">
                                <div class="text-center p-4 card">
                                    <div class="text-3xl font-bold text-primary-600"><?php echo esc_html(get_years_experience()); ?>+</div>
                                    <div class="text-neutral-600 dark:text-neutral-400">Years Experience</div>
                                </div>
                                <div class="text-center p-4 card">
                                    <div class="text-3xl font-bold text-accent-600"><?php echo esc_html(get_projects_completed()); ?>+</div>
                                    <div class="text-neutral-600 dark:text-neutral-400">Projects Completed</div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <p class="text-neutral-500 dark:text-neutral-400 text-sm mb-4">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Add your photo from Appearance → Customize → Front Page - About Me
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <?php if (has_services()): ?>
    <section id="services" class="py-20 bg-accent-25 dark:bg-primary-950">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4"><?php echo esc_html(get_services_section_title()); ?></h2>
                <p class="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    <?php echo esc_html(get_services_section_subtitle()); ?>
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-4"></div>
            </div>
            <?php 
            $services = get_services_list();
            $service_count = count($services);
            $grid_class = '';
            
            if ($service_count == 1) {
                $grid_class = 'grid grid-cols-1 gap-8 max-w-lg mx-auto';
            } elseif ($service_count == 2) {
                $grid_class = 'grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto';
            } else {
                $grid_class = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8';
            }
            ?>
            <div class="<?php echo esc_attr($grid_class); ?>">
                <?php foreach ($services as $service): ?>
                <div class="service-card card p-8 animate-on-scroll">
                    <div class="w-16 h-16 rounded-lg flex items-center justify-center mb-6" style="background-color: <?php echo esc_attr($service['background'] ?? '#7c3aed'); ?>20;">
                        <i class="<?php echo esc_attr($service['icon'] ?? 'fas fa-cog'); ?> text-2xl" style="color: <?php echo esc_attr($service['background'] ?? '#7c3aed'); ?>;"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4"><?php echo esc_html($service['title'] ?? ''); ?></h3>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-4">
                        <?php echo esc_html($service['description'] ?? ''); ?>
                    </p>
                    <?php if (!empty($service['features']) && is_array($service['features'])): ?>
                    <ul class="text-sm text-neutral-500 dark:text-neutral-400 space-y-1">
                        <?php foreach ($service['features'] as $feature): ?>
                        <li><i class="fas fa-check text-accent-500 mr-2"></i><?php echo esc_html($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Portfolio Section -->
    <?php if (get_portfolio_section_show() && has_portfolio_posts()): ?>
    <section id="portfolio" class="py-20 bg-neutral-50 dark:bg-neutral-850">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4"><?php echo esc_html(get_portfolio_section_title()); ?></h2>
                <p class="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    <?php echo esc_html(get_portfolio_section_description()); ?>
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-4"></div>
            </div>
            
            <?php
            $portfolio_posts = get_front_page_portfolio_posts();
            $posts_count = count($portfolio_posts);
            
            // Dynamic grid classes based on post count
            $grid_classes = '';
            if ($posts_count == 1) {
                $grid_classes = 'flex justify-center';
            } elseif ($posts_count == 2) {
                $grid_classes = 'grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto';
            } elseif ($posts_count <= 3) {
                $grid_classes = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto';
            } elseif ($posts_count <= 4) {
                $grid_classes = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto';
            } elseif ($posts_count <= 6) {
                $grid_classes = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8';
            } else {
                $grid_classes = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
            }
            ?>
            
            <div class="<?php echo esc_attr($grid_classes); ?>">
                <?php foreach ($portfolio_posts as $portfolio_post):
                    $post_id = $portfolio_post->ID;
                    $permalink = get_permalink($post_id);
                    $excerpt = $portfolio_post->post_excerpt ?: wp_trim_words($portfolio_post->post_content, 20, '...');
                    
                    // Get portfolio meta data
                    $category_slug = get_portfolio_category($post_id);
                    $demo_link = get_portfolio_demo_link($post_id);
                    $github_link = get_portfolio_github_link($post_id);
                    $technologies = get_portfolio_technologies($post_id);
                    
                    // Get category info for styling
                    $category_name = '';
                    $category_color = 'blue';
                    if ($category_slug) {
                        $category_data = get_portfolio_category_by_slug($category_slug);
                        $category_name = $category_data ? $category_data['name'] : ucfirst($category_slug);
                        $category_color = $category_data ? $category_data['color'] : 'blue';
                    }
                    
                    // Color mapping for category badges
                    $color_classes = array(
                        'blue' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300',
                        'green' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300',
                        'purple' => 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300',
                        'orange' => 'bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-300',
                        'red' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300',
                        'teal' => 'bg-teal-100 dark:bg-teal-900 text-teal-800 dark:text-teal-300',
                        'indigo' => 'bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-300',
                        'pink' => 'bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-300',
                        'gray' => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-300',
                    );
                    
                    $category_badge_class = $color_classes[$category_color] ?? $color_classes['blue'];
                ?>
                <article class="portfolio-card card animate-on-scroll<?php echo ($posts_count == 1) ? ' max-w-lg' : ''; ?>">
                    <?php if (has_post_thumbnail($post_id)): ?>
                    <div class="relative h-48 overflow-hidden">
                        <a href="<?php echo esc_url($permalink); ?>" class="block h-full">
                            <?php echo get_the_post_thumbnail($post_id, 'portfolio-thumb', array(
                                'class' => 'w-full h-full object-cover hover:scale-110 transition-transform duration-300',
                                'alt' => esc_attr($portfolio_post->post_title)
                            )); ?>
                        </a>
                        <?php if ($category_name): ?>
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 <?php echo esc_attr($category_badge_class); ?> text-xs font-medium rounded-full">
                                <?php echo esc_html($category_name); ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <!-- Fallback gradient when no featured image -->
                    <div class="relative h-48 bg-gradient-to-r from-primary-400 to-secondary-500 flex items-center justify-center">
                        <a href="<?php echo esc_url($permalink); ?>" class="block w-full h-full flex items-center justify-center text-white text-lg font-semibold hover:bg-black/10 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            View Project
                        </a>
                        <?php if ($category_name): ?>
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-white/20 text-white text-xs font-medium rounded-full backdrop-blur-sm">
                                <?php echo esc_html($category_name); ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 line-clamp-2">
                            <a href="<?php echo esc_url($permalink); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                <?php echo esc_html($portfolio_post->post_title); ?>
                            </a>
                        </h3>
                        
                        <p class="text-neutral-600 dark:text-neutral-400 mb-4 line-clamp-3">
                            <?php echo esc_html($excerpt); ?>
                        </p>
                        
                        <?php if ($technologies): 
                            $tech_array = array_map('trim', explode(',', $technologies));
                            $tech_array = array_slice($tech_array, 0, 4); // Limit to 4 technologies
                        ?>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php foreach ($tech_array as $tech): ?>
                            <span class="px-2 py-1 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-xs rounded-full">
                                <?php echo esc_html($tech); ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-4">
                                <?php if ($demo_link): ?>
                                <a href="<?php echo esc_url($demo_link); ?>" target="_blank" rel="noopener noreferrer" 
                                   class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium text-sm inline-flex items-center">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    Live Demo
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($github_link): ?>
                                <a href="<?php echo esc_url($github_link); ?>" target="_blank" rel="noopener noreferrer" 
                                   class="text-neutral-600 dark:text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-300 font-medium text-sm inline-flex items-center">
                                    <i class="fab fa-github mr-1"></i>
                                    Code
                                </a>
                                <?php endif; ?>
                            </div>
                            
                            <a href="<?php echo esc_url($permalink); ?>" 
                               class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium text-sm inline-flex items-center">
                                Details
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="<?php echo home_url('/portfolio'); ?>" class="btn btn-primary px-8 py-3 font-semibold inline-flex items-center">
                    View All Projects
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
    <?php elseif (get_portfolio_section_show() && !has_portfolio_posts()): ?>
    <!-- Portfolio Section - Empty State -->
    <section id="portfolio" class="py-20 bg-neutral-50 dark:bg-neutral-850">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4"><?php echo esc_html(get_portfolio_section_title()); ?></h2>
                <p class="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    <?php echo esc_html(get_portfolio_section_description()); ?>
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-4"></div>
            </div>
            
            <div class="text-center">
                <div class="mb-8">
                    <i class="fas fa-folder-open text-6xl text-neutral-400 dark:text-neutral-500 mb-4"></i>
                    <p class="text-xl text-neutral-600 dark:text-neutral-400 mb-2">No portfolio items yet</p>
                    <p class="text-neutral-500 dark:text-neutral-400">
                        Add your first portfolio project to showcase your work!
                    </p>
                </div>
                
                <?php if (current_user_can('edit_posts')): ?>
                <a href="<?php echo admin_url('post-new.php?post_type=portfolio'); ?>" 
                   class="btn btn-primary px-6 py-3 font-semibold inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add Portfolio Item
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Blog Section -->
    <section id="blog" class="py-20 bg-accent-25 dark:bg-primary-950">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4"><?php echo esc_html(get_blog_section_title()); ?></h2>
                <p class="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    <?php echo esc_html(get_blog_section_description()); ?>
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-4"></div>
            </div>
            <?php
            $featured_posts = get_featured_posts();
            
            if (!empty($featured_posts)):
                $post_count = count($featured_posts);
                
                // Dynamic grid classes based on post count
                $grid_classes = '';
                if ($post_count == 1) {
                    $grid_classes = 'flex justify-center';
                } elseif ($post_count == 2) {
                    $grid_classes = 'grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto';
                } else {
                    $grid_classes = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8';
                }
                ?>
                <div class="<?php echo esc_attr($grid_classes); ?>">
                    <?php foreach($featured_posts as $post):
                        $permalink = get_permalink($post['ID']);
                        $excerpt = wp_trim_words($post['post_content'], 30, '...');
                    ?>
                    <article class="blog-card card animate-on-scroll<?php echo ($post_count == 1) ? ' max-w-lg' : ''; ?>">
                        <?php if(has_post_thumbnail($post['ID'])): ?>
                        <div class="h-48 overflow-hidden">
                            <?php echo get_the_post_thumbnail($post['ID'], 'blog-thumb', array('class' => 'w-full h-full object-cover hover:scale-110 transition-transform duration-300')); ?>
                        </div>
                        <?php endif; ?>
                        <div class="p-6">
                            <div class="text-sm text-neutral-500 dark:text-neutral-400 mb-2"><?php echo date('F j, Y', strtotime($post['post_date'])); ?></div>
                            <h3 class="text-xl font-semibold mb-3 line-clamp-2">
                                <a href="<?php echo $permalink; ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                    <?php echo $post['post_title']; ?>
                                </a>
                            </h3>
                            <p class="text-neutral-600 dark:text-neutral-400 mb-4 line-clamp-3"><?php echo $excerpt; ?></p>
                            <a href="<?php echo $permalink; ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium inline-flex items-center">
                                Read More
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center">
                    <p class="text-neutral-600 dark:text-neutral-400">No blog posts yet. Stay tuned for updates!</p>
                </div>
            <?php endif; ?>
            <div class="text-center mt-12">
                <a href="<?php echo home_url('/blog'); ?>" class="btn btn-primary px-8 py-3 font-semibold inline-flex items-center">
                    View All Posts
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-neutral-900 dark:bg-neutral-950 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-white"><?php echo esc_html(get_contact_section_title()); ?></h2>
                <p class="text-neutral-400 max-w-2xl mx-auto">
                    <?php echo esc_html(get_contact_section_description()); ?>
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="animate-on-scroll">
                    <h3 class="text-2xl font-semibold mb-6 text-white">Get In Touch</h3>
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-xl text-white"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-white">Email</div>
                                <div class="text-neutral-400">
                                    <a href="mailto:<?php echo esc_attr(get_front_contact_email()); ?>" class="hover:text-primary-400 transition-colors">
                                        <?php echo esc_html(get_front_contact_email()); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-accent-600 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-xl text-white"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-white">Phone</div>
                                <div class="text-neutral-400">
                                    <a href="tel:<?php echo esc_attr(str_replace(' ', '', get_front_contact_phone())); ?>" class="hover:text-primary-400 transition-colors">
                                        <?php echo esc_html(get_front_contact_phone()); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-secondary-600 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-xl text-white"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-white">Location</div>
                                <div class="text-neutral-400"><?php echo esc_html(get_front_contact_location()); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold mb-4 text-white">Connect With Me</h4>
                        <div class="flex space-x-4">
                            <?php if (get_front_social_twitter()): ?>
                            <a href="<?php echo esc_url(get_front_social_twitter()); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-neutral-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors" aria-label="X (Twitter)">
                                <i class="fab fa-x-twitter text-lg text-white"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_front_social_linkedin()): ?>
                            <a href="<?php echo esc_url(get_front_social_linkedin()); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-neutral-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in text-lg text-white"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_front_social_github()): ?>
                            <a href="<?php echo esc_url(get_front_social_github()); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-neutral-800 hover:bg-neutral-600 rounded-lg flex items-center justify-center transition-colors" aria-label="GitHub">
                                <i class="fab fa-github text-lg text-white"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_front_social_facebook()): ?>
                            <a href="<?php echo esc_url(get_front_social_facebook()); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-neutral-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors" aria-label="Facebook">
                                <i class="fab fa-facebook-f text-lg text-white"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_front_social_instagram()): ?>
                            <a href="<?php echo esc_url(get_front_social_instagram()); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-neutral-800 hover:bg-pink-600 rounded-lg flex items-center justify-center transition-colors" aria-label="Instagram">
                                <i class="fab fa-instagram text-lg text-white"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="animate-on-scroll">
                    <div class="bg-neutral-800 rounded-lg p-8">
                        <h4 class="text-2xl font-semibold mb-6 text-white">Ready to Start a Project?</h4>
                        <p class="text-white mb-6 leading-relaxed">
                            <?php echo esc_html(get_contact_cta_message()); ?>
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-neutral-700 p-4 rounded-lg">
                                <h5 class="font-semibold text-white mb-2">Quick Response</h5>
                                <p class="text-white text-sm">I typically respond to emails within 24 hours</p>
                            </div>
                            <div class="bg-neutral-700 p-4 rounded-lg">
                                <h5 class="font-semibold text-white mb-2">Free Consultation</h5>
                                <p class="text-white text-sm">30-minute initial consultation at no charge</p>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <a href="mailto:<?php echo esc_attr(get_front_contact_email()); ?>?subject=Project%20Inquiry" 
                               class="bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-flex items-center text-lg">
                                Start a Conversation
                                <i class="fas fa-comments ml-2"></i>
                            </a>
                        </div>
                        
                        <div class="mt-6 text-center text-neutral-400 text-sm">
                            Or call me directly at 
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', get_front_contact_phone())); ?>" 
                               class="text-primary-400 hover:text-primary-300 font-medium">
                                <?php echo esc_html(get_front_contact_phone()); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
