<?php get_header (); ?> <!-- Appel au template de l'en-tête -->
<main class="unit-100">
    <h1> <?php echo get_the_author(); ?> </h1>
    <h2> A propos de l'auteur </h2>
    <p class="auteur">
        <?php the_author_meta ('description'); ?>
    </p>
    <h2> Son profil </h2>
    <p>
        <span class="libelle"> Son courriel </span>  :
        <?php echo the_author_meta('user_email' ); ?>. </p>
    <p>
        <span class="libelle"> Son site web </span>  :
        <?php echo the_author_meta('user_url' ); ?>. </p>
    <p>
        <?php echo get_the_author (); ?> a écrit <?php echo get_the_author_posts (); ?> articles dans ce site.
    </p>
</main>
<?php get_footer ();   ?>   <!-- Appel au template du pied de page -->