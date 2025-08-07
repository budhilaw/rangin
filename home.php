<?php
/**
 * Blog Home Page Template
 * 
 * This template is used when WordPress is set to show posts on the front page
 * or when displaying the blog posts page.
 * 
 * @package PersonalWebsite
 */

// If this is being used as the front page and we have a front-page.php, redirect there
if (is_front_page() && file_exists(get_template_directory() . '/front-page.php')) {
    get_template_part('front-page');
    return;
}

// Otherwise, load the blog layout from index.php
get_template_part('index');
?>
