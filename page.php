<?php get_header(); ?>

<main id="main" class="main-content">
    <div class="container mx-auto px-4 py-20">
        <?php while (have_posts()): the_post(); ?>
            <article class="max-w-4xl mx-auto">
                <!-- Page Header -->
                <header class="text-center mb-12 animate-on-scroll">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6"><?php the_title(); ?></h1>
                    <?php if (get_the_excerpt()): ?>
                        <p class="text-xl text-neutral-700 dark:text-neutral-300 max-w-3xl mx-auto">
                            <?php echo get_the_excerpt(); ?>
                        </p>
                    <?php endif; ?>
                </header>

                <!-- Featured Image -->
                <?php if (has_post_thumbnail()): ?>
                    <div class="mb-12 animate-on-scroll">
                        <?php the_post_thumbnail('large', array('class' => 'w-full rounded-xl shadow-lg')); ?>
                    </div>
                <?php endif; ?>

                <!-- Page Content -->
                <div class="prose prose-lg prose-neutral dark:prose-invert max-w-none animate-on-scroll">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
