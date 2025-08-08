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
    <section id="portfolio" class="py-20 bg-neutral-50 dark:bg-neutral-850">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Featured Projects</h2>
                <p class="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    Here are some of the projects I've worked on recently
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Project cards will be loaded here -->
                <div class="project-card card animate-on-scroll">
                    <div class="h-48 bg-gradient-to-r from-primary-400 to-secondary-500"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">E-Commerce Platform</h3>
                        <p class="text-neutral-600 dark:text-neutral-400 mb-4">A full-stack e-commerce solution with React, Node.js, and MongoDB</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-300 text-sm rounded-full">React</span>
                            <span class="px-3 py-1 bg-accent-100 dark:bg-accent-900 text-accent-800 dark:text-accent-300 text-sm rounded-full">Node.js</span>
                            <span class="px-3 py-1 bg-secondary-100 dark:bg-secondary-900 text-secondary-800 dark:text-secondary-300 text-sm rounded-full">MongoDB</span>
                        </div>
                        <div class="flex justify-between">
                            <a href="#" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">Live Demo</a>
                            <a href="#" class="text-neutral-600 dark:text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-300 font-medium">GitHub</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
