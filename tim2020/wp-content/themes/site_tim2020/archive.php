<?php get_header (); ?> <!-- Appel au template de l'en-tête -->

<main class="unit-100">
    <?php if ( have_posts() ) : ?>
        <h3><?php the_archive_title(); ?></h3>
        <p><?php the_archive_description(); ?></p>
        <?php
            while ( have_posts() ) : the_post();
            get_template_part ('content', get_post_format());
            endwhile;
    // Affichage du template si il n'y a pas de contenu à afficher
    else :
        get_template_part ('content' ,'none' );
    endif;
    ?>
</main>

<?php get_footer();   ?>   <!-- Appel au template du pied de page -->