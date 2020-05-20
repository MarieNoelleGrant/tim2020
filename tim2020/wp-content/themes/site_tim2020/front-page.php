<?php
/*
  Front Page gabarit
*/
get_header(); ?> <!-- Appel au gabarit de l'en-tête -->

<?php
    if('posts' == get_option('show_on_front')) {
        include(get_home_template());
    }
    else {
        include(get_page_template('content','accueil'));
    } ?>

<?php get_footer(); ?> <!-- Appel au gabarit du pied de page -->