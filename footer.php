    <!-- Footer -->
    <footer class="bg-neutral-800 dark:bg-neutral-900 text-neutral-200 py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-bold text-white mb-4">
                        <?php echo esc_html(get_personal_name()); ?>
                    </h3>
                    <p class="text-neutral-400 mb-6 max-w-md">
                        <?php echo esc_html(get_job_title()); ?> specializing in creating innovative digital solutions. 
                        Let's build something amazing together.
                    </p>
                    <div class="flex space-x-4">
                        <?php if (get_social_linkedin()): ?>
                        <a href="<?php echo esc_url(get_social_linkedin()); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 bg-neutral-800 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110" 
                           aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in text-lg"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_social_github()): ?>
                        <a href="<?php echo esc_url(get_social_github()); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 bg-neutral-800 hover:bg-neutral-700 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110" 
                           aria-label="GitHub">
                            <i class="fab fa-github text-lg"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_social_twitter()): ?>
                        <a href="<?php echo esc_url(get_social_twitter()); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 bg-neutral-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110" 
                           aria-label="Twitter">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#about" class="text-neutral-400 hover:text-primary-400 transition-colors">About</a></li>
                        <li><a href="#services" class="text-neutral-400 hover:text-primary-400 transition-colors">Services</a></li>
                        <li><a href="#portfolio" class="text-neutral-400 hover:text-primary-400 transition-colors">Portfolio</a></li>
                        <li><a href="#blog" class="text-neutral-400 hover:text-primary-400 transition-colors">Blog</a></li>
                        <li><a href="#contact" class="text-neutral-400 hover:text-primary-400 transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Get in Touch</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-primary-400 mr-3"></i>
                            <a href="mailto:<?php echo esc_attr(get_contact_email()); ?>" 
                               class="text-neutral-400 hover:text-primary-400 transition-colors">
                                <?php echo esc_html(get_contact_email()); ?>
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-primary-400 mr-3"></i>
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', get_contact_phone())); ?>" 
                               class="text-neutral-400 hover:text-primary-400 transition-colors">
                                <?php echo esc_html(get_contact_phone()); ?>
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt text-primary-400 mr-3"></i>
                            <span class="text-neutral-400"><?php echo esc_html(get_contact_location()); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="pt-8 border-t border-neutral-800">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-neutral-400 text-sm">
                        &copy; <?php echo date('Y'); ?> <?php echo esc_html(get_personal_name()); ?>. All rights reserved.
                    </p>
                    <div class="flex items-center space-x-6 mt-4 md:mt-0">
                        <a href="<?php echo home_url('/privacy-policy'); ?>" class="text-neutral-400 hover:text-primary-400 text-sm transition-colors">Privacy Policy</a>
                        <a href="<?php echo home_url('/terms'); ?>" class="text-neutral-400 hover:text-primary-400 text-sm transition-colors">Terms of Service</a>
                        <span class="text-neutral-400 text-sm">
                            Made with <i class="fas fa-heart text-red-500"></i> by <?php echo esc_html(get_personal_name()); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
