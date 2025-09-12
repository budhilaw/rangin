<?php
/**
 * Template Name: About Page
 * 
 * Custom template for About page with photo from front-page and customizable content
 * 
 * @package PersonalWebsite
 */

get_header(); ?>

<main id="main" class="main-content">
    <!-- About Hero Section - Header -->
    <section class="py-20 bg-gradient-to-r from-primary-50 to-secondary-50 dark:from-primary-900 dark:to-secondary-900">
        <div class="container mx-auto px-4">
            <div class="text-center animate-on-scroll">
                <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo esc_html(get_about_page_title()); ?></h1>
                <?php if (has_about_page_subtitle()): ?>
                    <p class="text-xl text-neutral-700 dark:text-neutral-300 max-w-3xl mx-auto mb-8">
                        <?php echo esc_html(get_about_page_subtitle()); ?>
                    </p>
                <?php endif; ?>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto"></div>
            </div>
        </div>
    </section>

    <!-- About Hero Section - Content -->
    <section class="py-20 bg-neutral-50 dark:bg-neutral-850">
        <div class="container mx-auto px-4">
            <?php while (have_posts()): the_post(); ?>
                <div class="grid grid-cols-1 lg:grid-cols-1 gap-12 items-center max-w-6xl mx-auto">
                    <div class="animate-on-scroll">
                        <?php $about_photo = get_about_me_photo(); ?>
                        <?php if ($about_photo): ?>
                            <div class="flex flex-col lg:flex-row gap-8 items-center">
                                <!-- Photo -->
                                <div class="lg:w-1/3 flex-shrink-0">
                                    <div class="relative">
                                        <?php 
                                        if (function_exists('image_opt_picture_for_url')) {
                                            echo image_opt_picture_for_url(
                                                $about_photo,
                                                array(
                                                    'alt'   => get_personal_name(),
                                                    'class' => 'w-full max-w-sm mx-auto lg:mx-0 rounded-2xl shadow-2xl object-cover aspect-[3/4]'
                                                )
                                            );
                                        } else {
                                        ?>
                                        <img src="<?php echo esc_url($about_photo); ?>" 
                                             alt="<?php echo esc_attr(get_personal_name()); ?>" 
                                             class="w-full max-w-sm mx-auto lg:mx-0 rounded-2xl shadow-2xl object-cover aspect-[3/4]">
                                        <?php } ?>
                                        <!-- Decorative elements -->
                                        <div class="absolute -inset-4 bg-gradient-to-r from-primary-500/20 to-accent-500/20 rounded-2xl -z-10 blur-xl"></div>
                                    </div>
                                </div>
                                
                                <!-- Main Content -->
                                <div class="lg:w-2/3">
                                    <div class="prose prose-lg prose-neutral dark:prose-invert max-w-none">
                                        <?php the_content(); ?>
                                    </div>
                                    
                                    <!-- CTA Buttons -->
                                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                                        <a href="<?php echo esc_url(home_url('/portfolio')); ?>" 
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
                                <div class="prose prose-lg prose-neutral dark:prose-invert max-w-4xl mx-auto mb-8">
                                    <?php the_content(); ?>
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
                                
                                <!-- CTA Buttons -->
                                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                                    <a href="<?php echo esc_url(home_url('/portfolio')); ?>" 
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
                                
                                <div class="text-center">
                                    <p class="text-neutral-500 dark:text-neutral-400 text-sm">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Add your photo from Appearance → Customize → Front Page - About Me
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    

    <!-- Contact CTA Section -->
    <section class="py-20 bg-neutral-900 dark:bg-neutral-950 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-4xl font-bold mb-6 text-white">Ready to Work Together?</h2>
                <p class="text-xl text-neutral-300 mb-8">
                    Let's discuss your project and see how I can help bring your ideas to life
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="mailto:<?php echo esc_attr(get_about_section_email()); ?>?subject=Project%20Inquiry" 
                       class="bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-flex items-center justify-center text-lg">
                        <i class="fas fa-envelope mr-2"></i>
                        Send Message
                    </a>
                    <a href="<?php echo esc_url(home_url('/portfolio')); ?>" 
                       class="border-2 border-white text-white hover:bg-white hover:text-neutral-900 px-8 py-4 rounded-lg font-semibold transition-all duration-300 inline-flex items-center justify-center text-lg">
                        <i class="fas fa-briefcase mr-2"></i>
                        View Work
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
