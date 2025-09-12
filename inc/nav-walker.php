<?php
/**
 * Custom Navigation Walker Classes
 * 
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Nav Walker for Desktop Menu
 */
class Personal_Website_Nav_Walker extends Walker_Nav_Menu {
    
    // Start Level - output of the sub menu start
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"absolute left-0 mt-2 w-48 bg-white dark:bg-neutral-800 rounded-lg shadow-lg border border-neutral-100 dark:border-neutral-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 py-2\">\n";
    }

    // End Level
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    // Start Element
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        
        $has_children = in_array('menu-item-has-children', $classes);
        $li_classes = $depth === 0 ? 'relative'.($has_children?' group':'') : '';
        $output .= $indent . '<li class="' . trim($li_classes) . '">';
        // CTA detection
        $is_cta = (strtolower($item->title) === 'contact' || in_array('cta-button', $classes));
        if ($depth === 0) {
            $link_class = $is_cta
                ? 'btn btn-primary px-4 py-2 text-sm font-medium transform hover:scale-105'
                : 'nav-link px-3 py-2 text-sm font-medium text-neutral-800 dark:text-neutral-200 hover:text-primary-600 dark:hover:text-primary-400 scroll-smooth';
        } else {
            $link_class = 'block px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 hover:text-primary-600 dark:hover:text-primary-400 transition-colors';
        }
        $output .= '<a href="' . $item->url . '" class="' . $link_class . '">';
        $output .= $item->title;
        if ($depth === 0 && $has_children) {
            $output .= '<i class="fas fa-chevron-down ml-2 text-xs transition-transform group-hover:rotate-180"></i>';
        }
        $output .= '</a>';
    }

    // End Element
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

/**
 * Custom Nav Walker for Mobile Menu
 */
class Personal_Website_Mobile_Nav_Walker extends Walker_Nav_Menu {
    
    // Start Level
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"pl-4 space-y-1\">\n";
    }

    // End Level
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    // Start Element
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $output .= $indent . '<li>';
        if ($depth === 0) {
            $is_cta = (strtolower($item->title) === 'contact' || in_array('cta-button', $classes));
            $link_class = $is_cta ? 'mobile-nav-link btn btn-primary block px-3 py-2 text-base font-medium rounded-lg mx-3 mt-4 text-center' : 'mobile-nav-link nav-link block px-3 py-2 text-base font-medium text-neutral-800 dark:text-neutral-200 hover:text-primary-600 dark:hover:text-primary-400';
        } else {
            $link_class = 'mobile-nav-link block px-6 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400';
        }
        $output .= '<a href="' . $item->url . '" class="' . $link_class . '">';
        $output .= $item->title;
        $output .= '</a>';
    }

    // End Element
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

/**
 * Fallback function for desktop menu when no menu is assigned
 */
function personal_website_default_menu() {
    echo '<ul class="flex items-baseline space-x-8">';
    echo '<li><a href="#about" class="nav-link px-3 py-2 text-sm font-medium text-neutral-800 dark:text-neutral-200 hover:text-primary-600 dark:hover:text-primary-400 scroll-smooth">About</a></li>';
    echo '<li><a href="#services" class="nav-link px-3 py-2 text-sm font-medium text-neutral-800 dark:text-neutral-200 hover:text-primary-600 dark:hover:text-primary-400 scroll-smooth">Services</a></li>';
    echo '<li><a href="#portfolio" class="nav-link px-3 py-2 text-sm font-medium text-neutral-800 dark:text-neutral-200 hover:text-primary-600 dark:hover:text-primary-400 scroll-smooth">Portfolio</a></li>';
    echo '<li><a href="' . home_url('/blog') . '" class="nav-link px-3 py-2 text-sm font-medium text-neutral-800 dark:text-neutral-200 hover:text-primary-600 dark:hover:text-primary-400">Blog</a></li>';
    echo '<li><a href="#contact" class="btn btn-primary px-4 py-2 text-sm font-medium transform hover:scale-105">Contact</a></li>';
    echo '</ul>';
}

/**
 * Fallback function for mobile menu when no menu is assigned
 */
function personal_website_mobile_default_menu() {
    echo '<ul class="space-y-1">';
    echo '<li><a href="#about" class="mobile-nav-link nav-link block px-3 py-2 text-base font-medium text-neutral-800 dark:text-neutral-200">About</a></li>';
    echo '<li><a href="#services" class="mobile-nav-link nav-link block px-3 py-2 text-base font-medium text-neutral-800 dark:text-neutral-200">Services</a></li>';
    echo '<li><a href="#portfolio" class="mobile-nav-link nav-link block px-3 py-2 text-base font-medium text-neutral-800 dark:text-neutral-200">Portfolio</a></li>';
    echo '<li><a href="' . home_url('/blog') . '" class="mobile-nav-link nav-link block px-3 py-2 text-base font-medium text-neutral-800 dark:text-neutral-200">Blog</a></li>';
    echo '<li><a href="#contact" class="mobile-nav-link btn btn-primary block px-3 py-2 text-base font-medium rounded-lg mx-3 mt-4 text-center">Contact</a></li>';
    echo '</ul>';
}
