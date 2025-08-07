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
    <section class="hero-section min-h-screen flex items-center justify-center hero-gradient-light dark:hero-gradient-dark text-neutral-900 dark:text-neutral-50 relative overflow-hidden">
        <div class="container mx-auto px-4 z-10">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in-up">
                    Hi, I'm <span class="text-gradient"><?php echo esc_html(get_personal_name()); ?></span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-neutral-600 dark:text-neutral-300 max-w-3xl mx-auto animate-fade-in-up animation-delay-200">
                    <?php echo esc_html(get_job_title()); ?> - <?php echo esc_html(get_personal_bio()); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-400">
                    <a href="#portfolio" class="btn btn-primary px-8 py-3 font-semibold transform hover:scale-105">
                        View My Work
                    </a>
                    <a href="#contact" class="btn btn-outline px-8 py-3 font-semibold">
                        Hire Me
                    </a>
                </div>
            </div>
        </div>
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-neutral-50 dark:bg-neutral-850">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">About Me</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto"></div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <h3 class="text-2xl font-semibold mb-6">Passionate Software Engineer</h3>
                    <p class="mb-6 leading-relaxed">
                        With over 5 years of experience in software development, I specialize in creating 
                        scalable web applications and mobile solutions. I'm passionate about clean code, 
                        user experience, and staying up-to-date with the latest technologies.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 card">
                            <div class="text-3xl font-bold text-primary-600">50+</div>
                            <div class="text-neutral-600 dark:text-neutral-400">Projects Completed</div>
                        </div>
                        <div class="text-center p-4 card">
                            <div class="text-3xl font-bold text-accent-600">100%</div>
                            <div class="text-neutral-600 dark:text-neutral-400">Client Satisfaction</div>
                        </div>
                    </div>
                </div>
                <div class="animate-on-scroll">
                    <div class="card p-8">
                        <h4 class="text-xl font-semibold mb-6">My Expertise</h4>
                        <div class="space-y-4">
                            <div class="skill-bar">
                                <div class="flex justify-between mb-2">
                                    <span class="text-neutral-700 dark:text-neutral-300">JavaScript/TypeScript</span>
                                    <span class="text-neutral-500 dark:text-neutral-400">95%</span>
                                </div>
                                <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-3">
                                    <div class="skill-progress bg-primary-500 h-3 rounded-full" style="width: 95%"></div>
                                </div>
                            </div>
                            <div class="skill-bar">
                                <div class="flex justify-between mb-2">
                                    <span class="text-neutral-700 dark:text-neutral-300">React/Next.js</span>
                                    <span class="text-neutral-500 dark:text-neutral-400">90%</span>
                                </div>
                                <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-3">
                                    <div class="skill-progress bg-accent-500 h-3 rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                            <div class="skill-bar">
                                <div class="flex justify-between mb-2">
                                    <span class="text-neutral-700 dark:text-neutral-300">Node.js/Express</span>
                                    <span class="text-neutral-500 dark:text-neutral-400">85%</span>
                                </div>
                                <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-3">
                                    <div class="skill-progress bg-secondary-500 h-3 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                            <div class="skill-bar">
                                <div class="flex justify-between mb-2">
                                    <span class="text-neutral-700 dark:text-neutral-300">Python/Django</span>
                                    <span class="text-neutral-500 dark:text-neutral-400">80%</span>
                                </div>
                                <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-3">
                                    <div class="skill-progress bg-rose-500 h-3 rounded-full" style="width: 80%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-primary-25 dark:bg-primary-950">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Services</h2>
                <p class="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    I offer a range of software development services to help bring your ideas to life
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="service-card card p-8 animate-on-scroll">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-code text-2xl text-primary-600 dark:text-primary-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Web Development</h3>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-4">
                        Custom web applications using modern technologies like React, Next.js, and Node.js
                    </p>
                    <ul class="text-sm text-neutral-500 dark:text-neutral-400 space-y-1">
                        <li><i class="fas fa-check text-accent-500 mr-2"></i>Responsive Design</li>
                        <li><i class="fas fa-check text-accent-500 mr-2"></i>SEO Optimization</li>
                        <li><i class="fas fa-check text-accent-500 mr-2"></i>Performance Focused</li>
                    </ul>
                </div>
                <div class="service-card card p-8 animate-on-scroll">
                    <div class="w-16 h-16 bg-accent-100 dark:bg-accent-900 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-2xl text-accent-600 dark:text-accent-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Mobile Apps</h3>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-4">
                        Cross-platform mobile applications using React Native and Flutter
                    </p>
                    <ul class="text-sm text-neutral-500 dark:text-neutral-400 space-y-1">
                        <li><i class="fas fa-check text-accent-500 mr-2"></i>iOS & Android</li>
                        <li><i class="fas fa-check text-accent-500 mr-2"></i>Native Performance</li>
                        <li><i class="fas fa-check text-accent-500 mr-2"></i>App Store Ready</li>
                    </ul>
                </div>
                <div class="service-card card p-8 animate-on-scroll">
                    <div class="w-16 h-16 bg-secondary-100 dark:bg-secondary-900 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-server text-2xl text-secondary-600 dark:text-secondary-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Backend Development</h3>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-4">
                        Scalable backend solutions with APIs, databases, and cloud infrastructure
                    </p>
                    <ul class="text-sm text-neutral-500 dark:text-neutral-400 space-y-1">
                        <li><i class="fas fa-check text-accent-500 mr-2"></i>RESTful APIs</li>
                        <li><i class="fas fa-check text-accent-500 mr-2"></i>Database Design</li>
                        <li><i class="fas fa-check text-accent-500 mr-2"></i>Cloud Deployment</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

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
                <h2 class="text-4xl font-bold mb-4">Latest Blog Posts</h2>
                <p class="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    Insights and tutorials about software development and technology
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 3,
                    'post_status' => 'publish'
                ));
                
                if (!empty($recent_posts)):
                    foreach($recent_posts as $post):
                        $permalink = get_permalink($post['ID']);
                        $excerpt = wp_trim_words($post['post_content'], 30, '...');
                    ?>
                    <article class="blog-card card animate-on-scroll">
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
                    <?php 
                    endforeach;
                else:
                ?>
                    <div class="col-span-full text-center">
                        <p class="text-neutral-600 dark:text-neutral-400">No blog posts yet. Stay tuned for updates!</p>
                    </div>
                <?php endif; ?>
            </div>
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
                <h2 class="text-4xl font-bold mb-4 text-white">Let's Work Together</h2>
                <p class="text-neutral-400 max-w-2xl mx-auto">
                    Ready to start your next project? Let's discuss how I can help bring your ideas to life
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
                                    <a href="mailto:<?php echo esc_attr(get_contact_email()); ?>" class="hover:text-primary-400 transition-colors">
                                        <?php echo esc_html(get_contact_email()); ?>
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
                                    <a href="tel:<?php echo esc_attr(str_replace(' ', '', get_contact_phone())); ?>" class="hover:text-primary-400 transition-colors">
                                        <?php echo esc_html(get_contact_phone()); ?>
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
                                <div class="text-neutral-400"><?php echo esc_html(get_contact_location()); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold mb-4 text-white">Connect With Me</h4>
                        <div class="flex space-x-4">
                            <?php if (get_social_twitter()): ?>
                            <a href="<?php echo esc_url(get_social_twitter()); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-neutral-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors" aria-label="Twitter">
                                <i class="fab fa-twitter text-lg text-white"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_social_linkedin()): ?>
                            <a href="<?php echo esc_url(get_social_linkedin()); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-neutral-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in text-lg text-white"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_social_github()): ?>
                            <a href="<?php echo esc_url(get_social_github()); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-neutral-800 hover:bg-neutral-600 rounded-lg flex items-center justify-center transition-colors" aria-label="GitHub">
                                <i class="fab fa-github text-lg text-white"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="animate-on-scroll">
                    <div class="bg-neutral-800 rounded-lg p-8">
                        <h4 class="text-2xl font-semibold mb-6 text-white">Ready to Start a Project?</h4>
                        <p class="text-white mb-6 leading-relaxed">
                            I'm currently available for freelance work and new opportunities. 
                            Whether you need a complete web application, mobile app, or just want to discuss your ideas, 
                            I'd love to hear from you.
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
                            <a href="mailto:<?php echo esc_attr(get_contact_email()); ?>?subject=Project%20Inquiry" 
                               class="bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-flex items-center text-lg">
                                Start a Conversation
                                <i class="fas fa-comments ml-2"></i>
                            </a>
                        </div>
                        
                        <div class="mt-6 text-center text-neutral-400 text-sm">
                            Or call me directly at 
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', get_contact_phone())); ?>" 
                               class="text-primary-400 hover:text-primary-300 font-medium">
                                <?php echo esc_html(get_contact_phone()); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
