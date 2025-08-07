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
        let lastScrollTop = 0;
        $(window).on('scroll', function() {
            const scrollTop = $(this).scrollTop();
            
            // Add/remove background on scroll
            if (scrollTop > 100) {
                $nav.addClass('bg-white/95 shadow-md').removeClass('bg-transparent');
            } else {
                $nav.removeClass('bg-white/95 shadow-md').addClass('bg-transparent');
            }
            
            // Hide/show navigation on scroll (optional)
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                // Scrolling down
                $nav.css('transform', 'translateY(-100%)');
            } else {
                // Scrolling up
                $nav.css('transform', 'translateY(0)');
            }
            
            lastScrollTop = scrollTop;
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
        
        // Check for saved theme preference or default to system preference
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Set initial theme
        if (savedTheme) {
            $html.toggleClass('dark', savedTheme === 'dark');
        } else if (systemPrefersDark) {
            $html.addClass('dark');
        }
        
        // Theme toggle handlers
        $themeToggle.on('click', function() {
            const isDark = $html.hasClass('dark');
            
            $html.toggleClass('dark', !isDark);
            localStorage.setItem('theme', !isDark ? 'dark' : 'light');
            
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
            }
        });
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
    
})(jQuery);
