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
        // Hide loading screen if exists
        $('.loading-screen').fadeOut();
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
        });
        
        // Close mobile menu when clicking on links
        $('.mobile-nav-link').on('click', function() {
            $mobileMenu.removeClass('show').addClass('hidden');
            $hamburgerIcon.removeClass('hidden');
            $closeIcon.addClass('hidden');
            $mobileMenuButton.attr('aria-expanded', 'false');
        });
        
        // Smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            
            const target = $(this.getAttribute('href'));
            if (target.length) {
                const headerHeight = $nav.outerHeight();
                const targetPosition = target.offset().top - headerHeight;
                
                $('html, body').animate({
                    scrollTop: targetPosition
                }, 800, 'easeInOutCubic');
            }
        });
        
        // Navigation scroll effects
        $(window).on('scroll', function() {
            const scrollTop = $(this).scrollTop();
            const isDarkMode = $('html').hasClass('dark');
            
            // Add completely solid background and shadow when scrolling, remove when at top
            if (scrollTop > 50) {
                // Scrolled down - add SOLID background and shadow (no transparency, no blur)
                $nav.removeClass('bg-transparent backdrop-blur-none bg-white bg-neutral-900');
                
                if (isDarkMode) {
                    $nav.addClass('bg-neutral-900 shadow-lg');
                } else {
                    $nav.addClass('bg-white shadow-lg');
                }
            } else {
                // At top - transparent background, no shadow
                $nav.removeClass('bg-white bg-neutral-900 shadow-lg');
                $nav.addClass('bg-transparent backdrop-blur-none');
            }
        });
    }
    
    /**
     * Scroll effects and progress bar
     */
    function initScrollEffects() {
        const $progressBar = $('.scroll-progress');
        
        // Update scroll progress
        $(window).on('scroll', throttle(function() {
            const windowHeight = $(window).height();
            const documentHeight = $(document).height();
            const scrollTop = $(window).scrollTop();
            const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
            
            $progressBar.css('width', Math.min(scrollPercent, 100) + '%');
        }, 16));
        
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
            
            // Check which elements are already in view and show them immediately
            $scrollAnimateElements.each(function() {
                const element = this;
                const rect = element.getBoundingClientRect();
                const windowHeight = window.innerHeight;
                
                // If element is in view on page load, show it immediately
                if (rect.top < windowHeight - 100 && rect.bottom > 0) {
                    $(element).addClass('visible');
                } else {
                    // Otherwise, observe it for when it comes into view
                    observer.observe(element);
                }
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
                    $typingText.text($typingText.text() + text.charAt(i));
                    i++;
                    setTimeout(typeWriter, 100);
                }
            };
            
            setTimeout(typeWriter, 1000);
        }
        
        // Parallax effect for hero section
        $(window).on('scroll', function() {
            const scrolled = $(window).scrollTop();
            const parallaxElements = $('.parallax');
            
            parallaxElements.each(function() {
                const $element = $(this);
                const speed = $element.data('speed') || 0.5;
                const yPos = -(scrolled * speed);
                $element.css('transform', 'translateY(' + yPos + 'px)');
            });
        });
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
                        
                        // Get the width from the style attribute
                        const targetWidth = $progressBar.css('width');
                        
                        // Reset and animate
                        $progressBar.css('width', '0%');
                        setTimeout(function() {
                            $progressBar.css('width', targetWidth);
                        }, 200);
                        
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
    
    // Add custom easing
    $.extend($.easing, {
        easeInOutCubic: function(x) {
            return x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2;
        }
    });
    
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
            const scrollTop = $(window).scrollTop();
            const isDarkMode = $html.hasClass('dark');
            
            // Only update if navigation should have solid background (scrolled down)
            if (scrollTop > 50) {
                $nav.removeClass('bg-white bg-neutral-900');
                if (isDarkMode) {
                    $nav.addClass('bg-neutral-900');
                } else {
                    $nav.addClass('bg-white');
                }
            }
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
                    $commentsContent.hide();
                }, 300);
                
                // Update button
                $toggleText.text('Show Comments');
                $toggleIcon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                commentsVisible = false;
            } else {
                // Show comments
                $commentsContent.show();
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
                
                // Scroll to comments if they're not in view
                const commentsOffset = $commentsContent.offset().top - 100;
                $('html, body').animate({
                    scrollTop: commentsOffset
                }, 500);
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
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 500);
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
