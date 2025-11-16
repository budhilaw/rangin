<?php
/**
 * Title: About Section
 * Slug: rangin/about-section
 * Categories: rangin, text, featured
 * Description: A two-column hero-style About layout with stats, feature list, and CTA buttons.
 */

?>
<!-- wp/group {"align":"full","className":"py-20 bg-neutral-50 dark:bg-neutral-850 rangin-about-section"} -->
<div class="wp-block-group py-20 bg-neutral-50 dark:bg-neutral-850 rangin-about-section"><div class="container mx-auto px-4">
<!-- wp/group {"className":"text-center mb-16"} -->
<div class="wp-block-group text-center mb-16"><!-- wp/heading {"level":2,"className":"text-4xl font-bold mb-4"} -->
<h2 class="text-4xl font-bold mb-4"><?php esc_html_e('About Me', 'rangin'); ?></h2>
<!-- /wp/heading -->

<!-- wp/group {"className":"w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto"} -->
<div class="wp-block-group w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto"></div>
<!-- /wp/group -->

<!-- wp/paragraph {"className":"text-neutral-700 dark:text-neutral-300 max-w-3xl mx-auto mt-6 text-lg"} -->
<p class="text-neutral-700 dark:text-neutral-300 max-w-3xl mx-auto mt-6 text-lg"><?php esc_html_e('Share your story, highlight your favorite technologies, and give visitors a reason to trust you with their next project.', 'rangin'); ?></p>
<!-- /wp/paragraph --></div>
<!-- /wp/group -->

<!-- wp/columns {"className":"gap-12 items-center max-w-6xl mx-auto"} -->
<div class="wp-block-columns gap-12 items-center max-w-6xl mx-auto"><!-- wp/column {"width":"35%","className":"flex-shrink-0"} -->
<div class="wp-block-column flex-shrink-0" style="flex-basis:35%"><!-- wp/group {"className":"relative"} -->
<div class="wp-block-group relative"><!-- wp/image {"sizeSlug":"full","linkDestination":"none","className":"rounded-2xl shadow-2xl aspect-[3/4] object-cover"} /-->

<!-- wp/group {"className":"absolute -inset-2 bg-gradient-to-r from-primary-500/20 to-accent-500/20 rounded-2xl -z-10 blur-xl pointer-events-none"} -->
<div class="wp-block-group absolute -inset-2 bg-gradient-to-r from-primary-500/20 to-accent-500/20 rounded-2xl -z-10 blur-xl pointer-events-none"></div>
<!-- /wp/group --></div>
<!-- /wp/group --></div>
<!-- /wp/column -->

<!-- wp/column {"width":"65%"} -->
<div class="wp-block-column" style="flex-basis:65%"><!-- wp/group {"className":"prose prose-lg prose-neutral dark:prose-invert max-w-none space-y-4"} -->
<div class="wp-block-group prose prose-lg prose-neutral dark:prose-invert max-w-none space-y-4"><!-- wp/paragraph -->
<p><?php esc_html_e('Write a concise introduction that explains who you are, what you do, and the kind of work that excites you.', 'rangin'); ?></p>
<!-- /wp/paragraph -->

<!-- wp/paragraph -->
<p><?php esc_html_e('Add a sentence or two about your approach, favorite stack, or the impact you aim to deliver for clients.', 'rangin'); ?></p>
<!-- /wp/paragraph --></div>
<!-- /wp/group -->

<!-- wp/group {"className":"grid grid-cols-2 gap-4 max-w-md my-10"} -->
<div class="wp-block-group grid grid-cols-2 gap-4 max-w-md my-10"><!-- wp/group {"className":"text-center p-4 card"} -->
<div class="wp-block-group text-center p-4 card"><!-- wp/heading {"level":3,"className":"text-3xl font-bold text-primary-600"} -->
<h3 class="text-3xl font-bold text-primary-600"><?php esc_html_e('8+', 'rangin'); ?></h3>
<!-- /wp/heading -->

<!-- wp/paragraph {"className":"text-neutral-700 dark:text-neutral-300"} -->
<p class="text-neutral-700 dark:text-neutral-300"><?php esc_html_e('Years Experience', 'rangin'); ?></p>
<!-- /wp/paragraph --></div>
<!-- /wp/group -->

<!-- wp/group {"className":"text-center p-4 card"} -->
<div class="wp-block-group text-center p-4 card"><!-- wp/heading {"level":3,"className":"text-3xl font-bold text-accent-600"} -->
<h3 class="text-3xl font-bold text-accent-600"><?php esc_html_e('120+', 'rangin'); ?></h3>
<!-- /wp/heading -->

<!-- wp/paragraph {"className":"text-neutral-700 dark:text-neutral-300"} -->
<p class="text-neutral-700 dark:text-neutral-300"><?php esc_html_e('Projects Delivered', 'rangin'); ?></p>
<!-- /wp/paragraph --></div>
<!-- /wp/group --></div>
<!-- /wp/group -->

<!-- wp/group {"className":"space-y-4"} -->
<div class="wp-block-group space-y-4"><!-- wp/heading {"level":3,"className":"text-2xl font-semibold"} -->
<h3 class="text-2xl font-semibold"><?php esc_html_e('How I can help', 'rangin'); ?></h3>
<!-- /wp/heading -->

<!-- wp/list {"className":"text-neutral-700 dark:text-neutral-300 space-y-1"} -->
<ul class="text-neutral-700 dark:text-neutral-300 space-y-1"><li><?php esc_html_e('Design delightful product experiences backed by data.', 'rangin'); ?></li><li><?php esc_html_e('Ship performant full-stack apps with clean, testable code.', 'rangin'); ?></li><li><?php esc_html_e('Mentor teams and set up pragmatic developer workflows.', 'rangin'); ?></li></ul>
<!-- /wp/list --></div>
<!-- /wp/group -->

<!-- wp/buttons {"className":"flex flex-wrap gap-4 mt-8"} -->
<div class="wp-block-buttons flex flex-wrap gap-4 mt-8"><!-- wp/button {"className":"btn btn-primary px-8 py-3 font-semibold transform hover:scale-105"} -->
<div class="wp-block-button btn btn-primary px-8 py-3 font-semibold transform hover:scale-105"><a class="wp-block-button__link wp-element-button"><?php esc_html_e('Download Resume', 'rangin'); ?></a></div>
<!-- /wp/button -->

<!-- wp/button {"className":"btn btn-outline px-8 py-3 font-semibold"} -->
<div class="wp-block-button btn btn-outline px-8 py-3 font-semibold"><a class="wp-block-button__link wp-element-button"><?php esc_html_e('Book a Call', 'rangin'); ?></a></div>
<!-- /wp/button --></div>
<!-- /wp/buttons --></div>
<!-- /wp/column --></div>
<!-- /wp/columns --></div></div>
<!-- /wp/group -->
