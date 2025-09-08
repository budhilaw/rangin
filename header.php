<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <script>
        // Remove no-js class and add js class immediately to prevent FOUC
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
        
        // Set initial theme based on localStorage or system preference
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
        // Overscroll preferences handled by CSS (see inline styles)
    </script>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    
    <!-- Preconnect to external domains for better performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://challenges.cloudflare.com" crossorigin>
    
    <!-- Critical CSS will be inlined here for faster loading -->
    <style>
        /* Critical CSS + overscroll preferences */
        html, body { overscroll-behavior: none; overscroll-behavior-y: none; }
        body { touch-action: pan-x pan-y; }
        
        /* Critical CSS for above-the-fold content */
        .hero-section { min-height: 100vh; }
        .animate-fade-in-up { opacity: 0; transform: translateY(30px); animation: fadeInUp 1s ease forwards; }
        .animation-delay-200 { animation-delay: 0.2s; }
        .animation-delay-400 { animation-delay: 0.4s; }
        
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
        
        .floating-shapes { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
        .shape { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.1); animation: float 6s ease-in-out infinite; }
        .shape-1 { width: 80px; height: 80px; left: 10%; top: 20%; animation-delay: 0s; }
        .shape-2 { width: 120px; height: 120px; right: 10%; top: 60%; animation-delay: 2s; }
        .shape-3 { width: 60px; height: 60px; left: 80%; top: 80%; animation-delay: 4s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
    
    <?php wp_head(); ?>
    
    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "<?php bloginfo('name'); ?>",
        "url": "<?php echo home_url(); ?>",
        "description": "<?php bloginfo('description'); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo home_url(); ?>/?s={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    
    <!-- Skip to content link for accessibility -->
    <a class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-lg z-50" href="#main">
        Skip to main content
    </a>
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-transparent backdrop-blur-none transition-all duration-300" id="main-nav">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="<?php echo home_url(); ?>" class="text-2xl font-bold text-neutral-900 dark:text-neutral-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <?php if (has_custom_logo()): ?>
                            <?php the_custom_logo(); ?>
                        <?php else: ?>
                            <?php bloginfo('name'); ?>
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'flex items-baseline space-x-8',
                        'container' => false,
                        'fallback_cb' => 'personal_website_default_menu',
                        'link_before' => '',
                        'link_after' => '',
                        'walker' => new Personal_Website_Nav_Walker(),
                    ));
                    ?>
                    
                    <!-- Theme Toggle -->
                    <button id="theme-toggle" class="theme-toggle p-1 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2" aria-label="Toggle theme">
                        <div class="theme-toggle-slider flex items-center justify-center">
                            <i class="fas fa-sun text-yellow-500 dark:hidden text-sm"></i>
                            <i class="fas fa-moon text-indigo-400 hidden dark:block text-sm"></i>
                        </div>
                    </button>
                </div>
                
                <!-- Mobile Controls -->
                <div class="flex items-center space-x-3 md:hidden">
                    <!-- Mobile Theme Toggle -->
                    <button id="mobile-theme-toggle" class="theme-toggle p-1 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2" aria-label="Toggle theme">
                        <div class="theme-toggle-slider flex items-center justify-center">
                            <i class="fas fa-sun text-yellow-500 dark:hidden text-xs"></i>
                            <i class="fas fa-moon text-indigo-400 hidden dark:block text-xs"></i>
                        </div>
                    </button>
                    
                    <!-- Mobile menu button -->
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-neutral-700 hover:text-primary-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:text-primary-400 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!-- Hamburger icon -->
                        <i class="hamburger-icon fas fa-bars text-xl"></i>
                        <!-- Close icon -->
                        <i class="close-icon fas fa-times text-xl hidden"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="mobile-menu md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white dark:bg-neutral-800 shadow-lg border-t border-neutral-100 dark:border-neutral-700">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'mobile-nav-menu',
                    'container' => false,
                    'fallback_cb' => 'personal_website_mobile_default_menu',
                    'link_before' => '',
                    'link_after' => '',
                    'walker' => new Personal_Website_Mobile_Nav_Walker(),
                ));
                ?>
            </div>
        </div>
    </nav>
    
    <!-- Progress bar for scroll progress -->
    <div class="fixed top-16 left-0 right-0 z-40">
        <div class="scroll-progress h-1 bg-gradient-to-r from-primary-500 to-secondary-500 transition-all duration-300" style="width: 0%"></div>
    </div>
