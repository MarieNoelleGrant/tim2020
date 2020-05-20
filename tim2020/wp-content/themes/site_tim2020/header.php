<!doctype html>
<html <?php language_attributes();?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri();?>/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri();?>/images/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffc40d">
    <meta name="theme-color" content="#ffffff">
    <meta name="description"
          content="Le programme de Techniques d'intégration multimédia est une formation vivante et actuelle! Vous aimez relever des défis et les nouvelles technologies? Ce programme est fait pour vous!<?php switch(true) {
      case is_page('programme') : echo ' || Consultez les différents éléments qui composent notre programme.';
        break;
      case is_page('realisation') : echo ' || Voyez ce que nos diplômés ont réalisé au cours de leurs années dans la TIM.';
          break;
      case is_page('stages') : echo ' || Trouvez toutes les informations concernant les stages offerts à nos étudiants.';
          break;
      case is_page('contact') : echo ' || Contactez l\'un de nos responsables pour poser toutes vos questions.';
          break;
  }?>">
    <meta name="keywords" content="Techniques d'intégration multimédia, TIM, intégration multimédia, multimédia, Cégep Sainte-Foy, Cégep Ste-Foy, Web, CSS, HTML, Technologies, Design, Conception, Programmation, Intégration, Traitement des médias, médias">

    <title><?php bloginfo('name');?><?php if(is_home() || is_front_page()) : ?>&mdash;<?php bloginfo('description'); ?><?php else : ?>&mdash;<?php wp_title("", true);?><?php endif;?></title>

    <link rel="stylesheet" type='text/css' href="<?php bloginfo('stylesheet_url'); ?>" media="screen">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <noscript class="noScript">
        <span class="noScript__conteneur">
            <span class="icone icone__erreur"></span>Il semblerait que votre javascript soit désactivé. Pour visualiser ce site à son meilleur, veuillez l'activer.
        </span>
    </noscript>
    <header id="en-tete" class="header units-row <?php if(get_the_title()=='Programme' || get_the_title()=='Accueil'){ echo 'header__fixed';}?>">
        <div class="header__conteneur">
            <a class="header__logo" href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">
                <span class="screen-reader-only"><?php bloginfo('name'); ?></span>
                <img class="header__logoTIM" src="<?php echo get_template_directory_uri();?>/images/logo_TIM--blancPetit.svg" alt="Retour vers l'accueil du site Techniques d'intégration multimédia">
            </a>
        <?php if (has_nav_menu('principal')) : ?>
            <nav id="principal" class="header__nav nav">
                <?php wp_nav_menu( array('theme_location'=>'principal')); ?>
            </nav>
        <?php endif; ?>
        </div>
    </header>

    <div id="contenu" class="units-row">
