/**
 * Main JavaScript file for Personal Website Theme
 * 
 * @package PersonalWebsite
 */

(function($) {
    'use strict';
    
    // DOM Ready
    $(document).ready(function() {
        // Add JS class to html element for styling
        $('html').removeClass('no-js').addClass('js');
        
        // Initialize all components
        initNavigation();
        initScrollEffects();
        initAnimations();
        initSkillBars();
        initThemeToggle();
        initCommentsToggle();
        
    });
    
    // Window Load
    $(window).on('load', function() {
        // Hide loading screen if exists - use opacity transition to avoid layout calculations
        const $loadingScreen = $('.loading-screen');
        if ($loadingScreen.length) {
            $loadingScreen.addClass('opacity-0');
            setTimeout(function() {
                $loadingScreen.remove();
            }, 300);
        }
    });
    
    /**
     * Navigation functionality
     */
    function initNavigation() {
        const $nav = $('#main-nav');
        const $mobileMenuButton = $('.mobile-menu-button');
        const $mobileMenu = $('.mobile-menu');
        const $hamburgerIcon = $('.hamburger-icon');
        const $closeIcon = $('.close-icon');
        
        // Mobile menu toggle
        $mobileMenuButton.on('click', function() {
            const isOpen = $mobileMenu.hasClass('show');
            
            if (isOpen) {
                $mobileMenu.removeClass('show').addClass('hidden');
                $hamburgerIcon.removeClass('hidden');
                $closeIcon.addClass('hidden');
                $(this).attr('aria-expanded', 'false');
            } else {
                $mobileMenu.addClass('show').removeClass('hidden');
                $hamburgerIcon.addClass('hidden');
                $closeIcon.removeClass('hidden');
                $(this).attr('aria-expanded', 'true');
            }
            
            // Update cached navigation height after mobile menu state change
            // Delay to allow CSS transitions to complete
            setTimeout(updateNavHeight, 100);
        });
        
        // Close mobile menu when clicking on links
        $('.mobile-nav-link').on('click', function() {
            $mobileMenu.removeClass('show').addClass('hidden');
            $hamburgerIcon.removeClass('hidden');
            $closeIcon.addClass('hidden');
            $mobileMenuButton.attr('aria-expanded', 'false');
            
            // Update cached navigation height after mobile menu closure
            setTimeout(updateNavHeight, 100);
        });
        
        // Cache navigation height to avoid reading offsetHeight repeatedly
        let cachedNavHeight = 0;
        let navHeightTimeout = null;
        
        function updateNavHeight() {
            if (navHeightTimeout) return; // throttle updates
            navHeightTimeout = setTimeout(() => {
                // Ensure no other DOM operations in this frame by using double rAF
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        cachedNavHeight = $nav[0] ? $nav[0].offsetHeight : 0;
                        navHeightTimeout = null;
                    });
                });
            }, 100);
        }
        
        // Update nav height on resize
        window.addEventListener('resize', updateNavHeight, { passive: true });
        // Initial nav height calculation after potential mobile menu changes
        updateNavHeight();
        
        // Smooth scrolling for anchor links (native smooth scroll).
        // Use cached nav height and defer all DOM reads to avoid forced reflow.
        $('a[href^="#"]').on('click', function(e) {
            const href = this.getAttribute('href');
            if (!href || href === '#') { return; }
            const $tgt = $(href);
            if (!$tgt.length) { return; }
            e.preventDefault();
            
            // Ensure nav height is current before scroll calculation
            // Use double rAF to ensure DOM operations are fully settled
            requestAnimationFrame(function(){
                requestAnimationFrame(function(){
                    // Batch all DOM reads together after DOM is settled
                    const rect = $tgt[0].getBoundingClientRect();
                    const currentScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
                    const targetY = currentScrollY + rect.top - cachedNavHeight;
                    
                    // Execute scroll in next frame to avoid conflicts
                    requestAnimationFrame(function(){
                        window.scrollTo({ top: Math.max(0, targetY), behavior: 'smooth' });
                    });
                });
            });
        });
        
        // Navigation scroll effects (passive + rAF to avoid layout thrash)
        let lastKnownScrollY = window.scrollY || window.pageYOffset;
        let ticking = false;
        let navIsSolid = null; // unknown
        function updateNavOnScroll() {
            ticking = false;
            const isDarkMode = document.documentElement.classList.contains('dark');
            const makeSolid = lastKnownScrollY > 50;
            if (navIsSolid === makeSolid) return; // no-op if state unchanged
            navIsSolid = makeSolid;
            if (makeSolid) {
                $nav.removeClass('bg-transparent backdrop-blur-none bg-white bg-neutral-900');
                $nav.addClass(isDarkMode ? 'bg-neutral-900 shadow-lg' : 'bg-white shadow-lg');
            } else {
                $nav.removeClass('bg-white bg-neutral-900 shadow-lg');
                $nav.addClass('bg-transparent backdrop-blur-none');
            }
        }
        window.addEventListener('scroll', function() {
            lastKnownScrollY = window.scrollY || window.pageYOffset;
            if (!ticking) {
                window.requestAnimationFrame(updateNavOnScroll);
                ticking = true;
            }
        }, { passive: true });
        // Initial state
        updateNavOnScroll();
    }
    
    /**
     * Scroll effects and progress bar
     */
    function initScrollEffects() {
        const $progressBar = $('.scroll-progress');
        
        // Update scroll progress (cache doc height; update on resize)
        let winH = window.innerHeight;
        let docH = document.documentElement.scrollHeight;
        function refreshDims() {
            winH = window.innerHeight;
            docH = document.documentElement.scrollHeight;
        }
        window.addEventListener('resize', debounce(refreshDims, 150), { passive: true });
        refreshDims();
        window.addEventListener('scroll', throttle(function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;
            const ratio = Math.min((scrollTop / Math.max(1, (docH - winH))), 1);
            // Use transform for GPU compositing and set style directly (avoid jQuery .css reads)
            const el = $progressBar[0];
            if (el) { el.style.transform = 'scaleX(' + ratio + ')'; }
        }, 50), { passive: true });
        
        // Initialize animation elements
        const $animateElements = $('.animate-on-scroll');
        const $heroElements = $('.hero-section .animate-on-scroll, .animate-fade-in-up');
        
        // Hero section elements should be visible immediately with staggered animation
        $heroElements.each(function(index) {
            const $element = $(this);
            const delay = $element.hasClass('animation-delay-200') ? 200 : 
                         $element.hasClass('animation-delay-400') ? 400 : 
                         $element.hasClass('animation-delay-600') ? 600 : 0;
            
            // Show hero elements immediately with their respective delays
            setTimeout(function() {
                $element.addClass('visible');
            }, delay);
        });
        
        // Handle non-hero scroll animations
        const $scrollAnimateElements = $animateElements.not($heroElements);
        
        if ('IntersectionObserver' in window && $scrollAnimateElements.length) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const $element = $(entry.target);
                        // Add a small delay to ensure smooth animation
                        setTimeout(function() {
                            $element.addClass('visible');
                        }, 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            // Observe all; IO will immediately fire for in-view elements without manual sync reads
            $scrollAnimateElements.each(function() {
                observer.observe(this);
            });
        } else {
            // Fallback for older browsers - show all elements with staggered animation
            $scrollAnimateElements.each(function(index) {
                const $element = $(this);
                setTimeout(function() {
                    $element.addClass('visible');
                }, index * 100);
            });
        }
    }
    
    /**
     * Initialize animations
     */
    function initAnimations() {
        // Typing animation for hero text (if needed)
        const $typingText = $('.typing-text');
        if ($typingText.length) {
            const text = $typingText.text();
            $typingText.text('');
            
            let i = 0;
            const typeWriter = function() {
                if (i < text.length) {
                    // Use textContent instead of jQuery .text() to avoid potential reflows
                    const currentText = $typingText[0].textContent || '';
                    $typingText[0].textContent = currentText + text.charAt(i);
                    i++;
                    setTimeout(typeWriter, 100);
                }
            };
            
            setTimeout(typeWriter, 1000);
        }
        
        // Parallax effect for hero section (passive + rAF batch updates)
        const parallaxElements = $('.parallax');
        if (parallaxElements.length) {
            let lastY = window.scrollY || window.pageYOffset;
            let ticking = false;
            function applyParallax() {
                ticking = false;
                // Read once, write many (batch)
                const y = lastY;
                parallaxElements.each(function() {
                    const $el = $(this);
                    const speed = $el.data('speed') || 0.5;
                    const translateY = -(y * speed);
                    this.style.transform = 'translateY(' + translateY + 'px)';
                });
            }
            window.addEventListener('scroll', function() {
                lastY = window.scrollY || window.pageYOffset;
                if (!ticking) {
                    window.requestAnimationFrame(applyParallax);
                    ticking = true;
                }
            }, { passive: true });
            // Initial position
            applyParallax();
        }
    }
    
    /**
     * Animate skill bars
     */
    function initSkillBars() {
        const $skillBars = $('.skill-bar');
        
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const $skillBar = $(entry.target);
                        const $progressBar = $skillBar.find('.bg-blue-600, .bg-green-600, .bg-purple-600, .bg-yellow-600');
                        // Read width from inline style (no layout) or data attribute
                        const el = $progressBar.get(0);
                        const targetWidth = (el && el.style && el.style.width) ? el.style.width : ($progressBar.attr('data-target-width') || '0%');
                        // Reset and animate in next frame to avoid forced reflow
                        $progressBar.css('width', '0%');
                        requestAnimationFrame(function(){
                            setTimeout(function() {
                                $progressBar.css('width', targetWidth);
                            }, 120);
                        });
                        
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });
            
            $skillBars.each(function() {
                observer.observe(this);
            });
        }
    }
    
    /**
     * Utility functions
     */
    
    // Debounce function for performance
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    
    // Throttle function for performance
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(function() {
                    inThrottle = false;
                }, limit);
            }
        };
    }
    
    // No jQuery easing needed since we use native smooth scroll
    
    /**
     * Theme toggle functionality
     */
    function initThemeToggle() {
        const $html = $('html');
        const $themeToggle = $('#theme-toggle, #mobile-theme-toggle');
        
        // Update Cloudflare Turnstile widget theme to match site
        function updateTurnstileTheme() {
            const isDark = $html.hasClass('dark');
            const theme = isDark ? 'dark' : 'light';
            const $widgets = $('.cf-turnstile');
            if (!$widgets.length) return;

            $widgets.each(function() {
                const $old = $(this);
                const sitekey = $old.attr('data-sitekey') || $old.data('sitekey');
                const action = $old.attr('data-action') || $old.data('action');
                const callback = $old.attr('data-callback') || $old.data('callback');

                // Create a fresh placeholder with the desired theme
                const $placeholder = $('<div class="cf-turnstile" />');
                if (sitekey) $placeholder.attr('data-sitekey', sitekey);
                if (action) $placeholder.attr('data-action', action);
                if (callback) $placeholder.attr('data-callback', callback);
                $placeholder.attr('data-theme', theme);

                $old.replaceWith($placeholder);

                // If the Turnstile API is ready, explicitly render; otherwise
                // the auto-render will pick it up using data-* attributes.
                if (window.turnstile && typeof window.turnstile.render === 'function') {
                    try {
                        window.turnstile.render($placeholder[0], {
                            sitekey: sitekey,
                            theme: theme,
                            action: action || undefined
                        });
                    } catch (e) {
                        // Fail silently; auto-render will still apply.
                    }
                }
            });
        }
        // Expose globally so other modules (e.g., comments toggle) can call it
        window.updateTurnstileTheme = updateTurnstileTheme;
        
        // Check for saved theme preference or default to system preference
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Set initial theme
        if (savedTheme) {
            $html.toggleClass('dark', savedTheme === 'dark');
        } else if (systemPrefersDark) {
            $html.addClass('dark');
        }
        // Ensure Turnstile matches the initial theme
        setTimeout(updateTurnstileTheme, 0);
        
        // Function to update navigation background based on current theme
        function updateNavBackground() {
            const $nav = $('#main-nav');
            const isDarkMode = $html.hasClass('dark');
            
            // Use the same cached scroll position from the scroll handler to avoid extra reads
            requestAnimationFrame(function(){
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;
                if (scrollTop > 50) {
                    // Batch DOM writes together to minimize layout impact
                    requestAnimationFrame(function(){
                        $nav.removeClass('bg-white bg-neutral-900');
                        if (isDarkMode) {
                            $nav.addClass('bg-neutral-900');
                        } else {
                            $nav.addClass('bg-white');
                        }
                    });
                }
            });
        }
        
        // Theme toggle handlers
        $themeToggle.on('click', function() {
            const isDark = $html.hasClass('dark');
            
            $html.toggleClass('dark', !isDark);
            localStorage.setItem('theme', !isDark ? 'dark' : 'light');
            
            // Update navigation background for new theme
            setTimeout(updateNavBackground, 50);
            // Re-render Turnstile to match new theme
            setTimeout(updateTurnstileTheme, 10);
            
            // Add a subtle animation effect
            $('body').addClass('theme-transitioning');
            setTimeout(function() {
                $('body').removeClass('theme-transitioning');
            }, 300);
        });
        
        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (!localStorage.getItem('theme')) {
                $html.toggleClass('dark', e.matches);
                // Update navigation background for system theme change
                setTimeout(updateNavBackground, 50);
                setTimeout(updateTurnstileTheme, 10);
            }
        });
        // If CF Turnstile script signals it's ready, render with current theme
        window.cfTurnstileReady = function() {
            setTimeout(updateTurnstileTheme, 0);
        };
    }
    
    // Handle form submissions (if contact form is added later)
    $(document).on('submit', '.contact-form', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const originalText = $submitBtn.text();
        
        // Show loading state
        $submitBtn.text('Sending...').prop('disabled', true);
        
        // Simulate form submission (replace with actual AJAX call)
        setTimeout(function() {
            $submitBtn.text('Message Sent!').removeClass('bg-primary-600').addClass('bg-green-600');
            
            setTimeout(function() {
                $submitBtn.text(originalText).prop('disabled', false)
                         .removeClass('bg-green-600').addClass('bg-primary-600');
                $form[0].reset();
            }, 3000);
        }, 2000);
    });
    
    /**
     * Initialize comments toggle functionality
     */
    function initCommentsToggle() {
        const $commentsToggle = $('#comments-toggle');
        const $commentsContent = $('#comments-content');
        const $toggleText = $('#comments-toggle-text');
        const $toggleIcon = $('#comments-toggle-icon');
        
        if (!$commentsToggle.length || !$commentsContent.length) {
            return;
        }
        
        let commentsVisible = false;
        
        // Handle button click
        $commentsToggle.on('click', function() {
            if (commentsVisible) {
                // Hide comments
                $commentsContent.removeClass('visible').addClass('opacity-0');
                setTimeout(() => {
                    $commentsContent.addClass('hidden').attr('aria-hidden','true');
                }, 300);
                
                // Update button
                $toggleText.text('Show Comments');
                $toggleIcon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                commentsVisible = false;
            } else {
                // Show comments
                $commentsContent.removeClass('hidden').attr('aria-hidden','false');
                setTimeout(() => {
                    $commentsContent.addClass('visible').removeClass('opacity-0');
                    
                    // Initialize animations for comment elements
                    initCommentsAnimations();
                    // Ensure Turnstile theme is correct once comments appear
                    if (window.updateTurnstileTheme) {
                        setTimeout(window.updateTurnstileTheme, 10);
                    }
                }, 50);
                
                // Update button
                $toggleText.text('Hide Comments');
                $toggleIcon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                commentsVisible = true;
                
                // Scroll to comments if they're not in view; use delayed DOM read to avoid forced reflow
                // Add delay to ensure comment reveal animation has completed
                setTimeout(function(){
                    requestAnimationFrame(function(){
                        requestAnimationFrame(function(){
                            // All DOM changes should be settled by now
                            const rect = $commentsContent[0].getBoundingClientRect();
                            const currentScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
                            const targetY = currentScrollY + rect.top - 100;
                            
                            // Execute scroll in separate frame
                            requestAnimationFrame(function(){
                                window.scrollTo({ top: Math.max(0, targetY), behavior: 'smooth' });
                            });
                        });
                    });
                }, 100);
            }
        });
        
        // Handle direct comment links (e.g., from notifications)
        if (window.location.hash && (window.location.hash.indexOf('#comment') !== -1 || window.location.hash === '#comments')) {
            // Auto-open comments if user navigated directly to a comment
            $commentsToggle.trigger('click');
            
            // Focus on specific comment if hash is present  
            setTimeout(() => {
                const target = $(window.location.hash);
                if (target.length) {
                    // Ensure all comment animations and DOM changes are complete before measuring
                    requestAnimationFrame(function(){
                        requestAnimationFrame(function(){
                            // Double rAF to ensure DOM operations are settled
                            const rect = target[0].getBoundingClientRect();
                            const currentScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
                            const targetY = currentScrollY + rect.top - 100;
                            
                            // Execute scroll in separate frame
                            requestAnimationFrame(function(){
                                window.scrollTo({ top: Math.max(0, targetY), behavior: 'smooth' });
                            });
                        });
                    });
                }
            }, 600);
        }
    }
    
    /**
     * Initialize animations for comment elements
     */
    function initCommentsAnimations() {
        const commentElements = $('.comment-item, .comment-respond');
        
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('animate-fade-in-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            commentElements.each(function() {
                observer.observe(this);
            });
        } else {
            // Fallback for older browsers
            commentElements.each(function(index) {
                const $element = $(this);
                setTimeout(() => {
                    $element.addClass('animate-fade-in-up');
                }, index * 100);
            });
        }
    }
    
})(jQuery);
