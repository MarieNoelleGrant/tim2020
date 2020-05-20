<?php get_header(); ?>

<?php if(have_posts()) : ?>
    <?php while(have_posts()) : the_post(); ?>
        <div id="article-seul article article-<?php echo get_the_ID(); ?>">
            <div class="image-une">
                <?php if(has_post_thumbnail()) {
                    the_post_thumbnail();
                } ?>
            </div>
            <?php get_template_part('content', get_post_format()); ?>
            <?php the_post_navigation(); ?>
            <?php
                if (comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
            ?>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>