<?php get_header(); ?>

<main id="articles">
    <?php if(have_posts()) : ?>
        <?php while(have_posts()) : the_post(); ?>
            <article>
                <header class="titre-article">
                    <h2>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                </header>
                <?php the_content(); ?>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php if (is_active_sidebar ('gauche') && is_active_sidebar('droite')) : ?>
    <aside id="gauche" class="unit-25">
        <?php get_sidebar('gauche'); ?>
    </aside>
    <main id="articles" class="unit-50">
        <?php if(have_posts()) : ?>
            <?php while(have_posts()) : the_post(); ?>
                <article>
                    <header class="titre-article">
                        <h2>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                    </header>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>
    <aside id="droite" class="unit-25">
        <?php get_sidebar('droite'); ?>
    </aside>

<?php elseif (is_active_sidebar ('gauche') && is_active_sidebar ('droite')==false ) : ?>
    <aside id="gauche" class="unit-25">
        <?php get_sidebar('gauche'); ?>
    </aside>
    <main id="articles" class="unit-75">
        <?php if(have_posts()) : ?>
            <?php while(have_posts()) : the_post(); ?>
                <article>
                    <header class="titre-article">
                        <h2>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                    </header>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>

<?php elseif (is_active_sidebar ('droite') && is_active_sidebar ('gauche')==false ) : ?>
    <main id="articles" class="unit-75">
        <?php if(have_posts()) : ?>
            <?php while(have_posts()) : the_post(); ?>
                <article>
                    <header class="titre-article">
                        <h2>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                    </header>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>
    <aside id="droite" class="unit-25">
        <?php get_sidebar('droite'); ?>
    </aside>

<?php elseif (is_active_sidebar ('droite')==false && is_active_sidebar ('gauche')==false ) : ?>
    <main id="articles" class="unit-75">
        <?php if(have_posts()) : ?>
            <?php while(have_posts()) : the_post(); ?>
                <article>
                    <header class="titre-article">
                        <h2>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                    </header>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>

<?php endif; ?>

<?php get_footer(); ?>
