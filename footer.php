    <!-- Footer -->
    <footer class="bg-neutral-800 dark:bg-neutral-900 text-neutral-200 py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand Section (md:col-span-2) -->
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-bold text-white mb-4">
                        <?php echo esc_html(get_footer_brand_title()); ?>
                    </h3>
                    <p class="text-neutral-400 mb-6 max-w-md">
                        <?php echo esc_html(get_footer_brand_description()); ?>
                    </p>
                    <div class="flex space-x-4">
                        <?php if (get_footer_social_x()): ?>
                        <a href="<?php echo esc_url(get_footer_social_x()); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 bg-neutral-700 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110" 
                           aria-label="X (Twitter)">
                            <i class="fab fa-x-twitter text-lg"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_footer_social_instagram()): ?>
                        <a href="<?php echo esc_url(get_footer_social_instagram()); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 bg-neutral-700 hover:bg-pink-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110" 
                           aria-label="Instagram">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_footer_social_linkedin()): ?>
                        <a href="<?php echo esc_url(get_footer_social_linkedin()); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 bg-neutral-700 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110" 
                           aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in text-lg"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_footer_social_github()): ?>
                        <a href="<?php echo esc_url(get_footer_social_github()); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 bg-neutral-700 hover:bg-gray-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110" 
                           aria-label="GitHub">
                            <i class="fab fa-github text-lg"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_footer_social_facebook()): ?>
                        <a href="<?php echo esc_url(get_footer_social_facebook()); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 bg-neutral-700 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110" 
                           aria-label="Facebook">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Footer Widget Area - Maximum 2 widgets -->
                <?php if (is_active_sidebar('footer-1')): ?>
                    <div class="col-span-1 md:col-span-2 footer-widget-area">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php else: ?>
                    <!-- Fallback content when no widgets are added -->
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-4">Quick Links</h4>
                        <p class="text-neutral-400 text-sm">
                            <?php _e('Add EBTW - Quick Links widget in', 'personal-website'); ?> 
                            <a href="<?php echo admin_url('widgets.php'); ?>" class="text-primary-400 hover:text-primary-300">
                                <?php _e('Appearance > Widgets > Footer', 'personal-website'); ?>
                            </a>
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-4">Get in Touch</h4>
                        <p class="text-neutral-400 text-sm">
                            <?php _e('Add EBTW - Contact Info widget in', 'personal-website'); ?> 
                            <a href="<?php echo admin_url('widgets.php'); ?>" class="text-primary-400 hover:text-primary-300">
                                <?php _e('Appearance > Widgets > Footer', 'personal-website'); ?>
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Footer Bottom -->
            <div class="pt-8 border-t border-neutral-800">
                <div class="flex flex-col items-center text-center space-y-3">
                    <p class="text-neutral-400 text-sm">
                        <?php echo wp_kses_post(get_footer_copyright()); ?>
                    </p>
                    
                    <?php if (get_footer_link_1_url() && get_footer_link_1_text()): ?>
                        <a href="<?php echo esc_url(get_footer_link_1_url()); ?>" class="text-neutral-400 hover:text-primary-400 text-sm transition-colors">
                            <?php echo esc_html(get_footer_link_1_text()); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (get_footer_link_2_url() && get_footer_link_2_text()): ?>
                        <a href="<?php echo esc_url(get_footer_link_2_url()); ?>" class="text-neutral-400 hover:text-primary-400 text-sm transition-colors">
                            <?php echo esc_html(get_footer_link_2_text()); ?>
                        </a>
                    <?php endif; ?>
                    
                    <span class="text-neutral-400 text-sm">
                        <?php echo esc_html(get_footer_made_with_text()); ?>
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
