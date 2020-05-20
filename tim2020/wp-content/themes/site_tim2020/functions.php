<?php
/* Ajout emplacement de menu */
if (function_exists('register_nav_menus')) {
    register_nav_menus(
        array(
            'principal' => 'Menu principal',
            'secondaire' => 'Menu secondaire'
        )
    );
}
?>

<?php
/* Ajout de l'utilisation de la sidebar */
if (function_exists('register_sidebar')) {
    register_sidebar(
        array(
            'id' => 'gauche',
            'name' => 'Emplacement gauche',
            'description' => 'Emplacement à gauche des widgets',
            'before_widget' => '',
            'after_widget' => '',
            'before_titre' => '<h3>',
            'after_title' => '</h3>'
        )
    );
    register_sidebar(
        array(
            'id' => 'droite',
            'name' => 'Emplacement droite',
            'description' => 'Emplacement à droite des widgets',
            'before_widget' => '',
            'after_widget' => '',
            'before_titre' => '<h3>',
            'after_title' => '</h3>'
        )
    );
}
?>

<?php
/* Ajout des formats d'article */
add_theme_support ('post-formats' ,  array(
    'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'));
?>

<?php
// Personnalisation du thème avec l'API Customiser
function theme_customize_register($wp_customize)
{
    // Ajout de la section
    $wp_customize->add_section('ma_section', array(
        'title' => 'Options de site_tim2020',
        'description' => 'Personnalisation du thème Mon thème complet',
        'priority' => 200,
    ));

    /******* Selecteur de couleur *******/
    // Ajout du réglage
    $wp_customize->add_setting('couleur_liens', array(
        'default' => '000',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability' => 'edit_theme_options',
        'type' => 'theme_mod',
        'transport' => 'refresh'
    ));
    // Ajout du contrôle
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
        'link_color', array(
            'label' => 'Couleur des liens',
            'section' => 'ma_section',
            'settings' => 'couleur_liens'
        )));

    /******* Chargement d'une image *******/
    // Ajout du réglage
    $wp_customize->add_setting('charge_image');
    // Ajout du contrôle
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,'charge_image', array(
            'label' => 'Image d\'arrière-plan : 1140x250 px',
            'section' => 'ma_section',
            'settings' => 'charge_image'
        )
    ));
}
add_action('customize_register', 'theme_customize_register');

/******* Création de la règle CSS *******/
function theme_customize_css() {
    ?>
    <style type="text/css">
        a {
            color: <?php echo get_theme_mod('couleur_liens', '#000000'); ?>;
        }
        header.unit-100 {
            background-image: url(<?php echo get_theme_mod('charge_image', 'none'); ?>);
            height: <?php if (get_theme_mod('charge_image')!=='') {
                    echo '250px';} else {echo'auto';} ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'theme_customize_css');
?>

<?php
/* Creation du réglage Image à la une */
if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
}
/* Création des emplacements des menus */
if (function_exists('register_nav_menus')) {
    register_nav_menus();
}
?>


<?php
/*************************************************************************************************
   GESTION DES IMAGES
 **************************************************************************************************/
add_theme_support ( 'post-thumbnails' );

function prefix_remove_default_images( $sizes ) {
    unset( $sizes['small']); // 150px
    unset( $sizes['medium']); // 300px
    unset( $sizes['large']); // 1024px
    unset( $sizes['medium_large']); // 768px
    unset( $sizes['1536×1536']);
    unset( $sizes['2048×2048']);
    return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'prefix_remove_default_images');

add_filter( 'jpeg_quality', function($args) { return 100; });

// Ajout de tailles personnalisées d’image de contenu
// 1. Pour la gallerie des réalisations
add_image_size ('gallerie-realisations-mobile-2X', 1200, 0, false);
add_image_size ('gallerie-realisations-mobile', 600, 0, false);
add_image_size ('gallerie-realisations-2X', 800, 0, false);
add_image_size ('gallerie-realisations', 400, 0, false);

// 2. Pour les photos des diplomes
add_image_size ( 'diplomes-carre-1xGros-2xPetit' , 240 , 240, false );
add_image_size ('diplomes-carre-mini-1x', 93, 93, false);

// Ajout des noms des tailles personnalisées des images de contenu dans l’admin
add_filter ( 'image_size_names_choose' , 'wpshout_custom_sizes' );

function wpshout_custom_sizes ( $sizes ) {
    return array_merge($sizes , array(
        'gallerie-realisations-mobile-2X' => __('Gallerie réalisations, mobile, résolution 2X'),
        'gallerie-realisations-mobile' => __('Gallerie réalisations, mobile'),
        'gallerie-realisations-2X' => __('Gallerie réalisations, résolution 2X'),
        'gallerie-realisations' => __('Gallerie réalisations'),
        'diplomes-carre-1xGros-2xPetit' => __('Carré petit, 1x grand format, 2x petit format'),
        'diplomes-carre-mini-1x' => __('Mini carré'),
    ));
}

?>

<?php
/*************************************************************************************************
   CRÉATION DES ARTICLES PERSONNALISÉS
**************************************************************************************************/

/* Déclaration du type d'article personnalisé des RESPONSABLES */
function tim_responsable_custom_post() {
    // Dénominations qui afficheront dans l'interface administrateur
    $labels = array(
        'name'                  => _X( 'Responsables de la TIM', 'Post Type General Name'),
        'singular_name'         => _X( 'Responsables', 'Post Type Singular Name'),
        'menu_name'             => __( 'Responsables 2020'),
        'all_items'             => __( 'Tous nos responsables'),
        'view_item'             => __( 'Voir nos responsables'),
        'add_new_item'          => __( 'Ajouter un nouveau responsable'),
        'add_new'               => __( 'Ajouter'),
        'edit_item'             => __( 'Éditer un responsable'),
        'update_item'           => __( 'Modifier un responsable'),
        'search_items'          => __( 'Rechercher un responsable'),
        'not_found'             => __( 'Non trouvé'),
        'not_found_in_trash'    => __( 'Non trouvé dans la corbeille'),
    );

    // Autres options pour l'article personnalisé
    $args = array (
        'label'         => __( 'Nos responsables'),
        'description'   => __( 'Tous sur nos responsables'),
        'labels'        => $labels,
        // Définir ici les options disponibles dans l'éditeur (enlever ceux qui pourrait être inutiles éventuellement)
        'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical'  => false,
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => array( 'slug' => 'responsables'),
    );
    // Pour enregistrer le type d'article personnalisé et ses arguments
    register_post_type( 'responsables', $args);
}
// Écouteur d'événement natif à wordpress qui fait appel à la fonction dès l'initialisation du thème
add_action( 'init', 'tim_responsable_custom_post', 0);
?>

<?php
/* Déclaration du type d'article personnalisé des RÉALISATIONS */
function tim_realisation_custom_post() {
    // Dénominations qui afficheront dans l'interface administrateur
    $labels = array(
        'name'                  => _X( 'Réalisations de la TIM', 'Post Type General Name'),
        'singular_name'         => _X( 'Réalisations', 'Post Type Singular Name'),
        'menu_name'             => __( 'Réalisations finissants 2020'),
        'all_items'             => __( 'Toutes les réalisations'),
        'view_item'             => __( 'Voir les réalisations'),
        'add_new_item'          => __( 'Ajouter une nouvelle réalisation'),
        'add_new'               => __( 'Ajouter'),
        'edit_item'             => __( 'Éditer une réalisation'),
        'update_item'           => __( 'Modifier une réalisation'),
        'search_items'          => __( 'Rechercher une réalisation'),
        'not_found'             => __( 'Non trouvée'),
        'not_found_in_trash'    => __( 'Non trouvée dans la corbeille'),
    );

    // Autres options pour l'article personnalisé
    $args = array (
        'label'         => __( 'Les réalisations'),
        'description'   => __( 'Toutes les réalisations de nos finissants 2020'),
        'labels'        => $labels,
        // Définir ici les options disponibles dans l'éditeur (enlever ceux qui pourrait être inutiles éventuellement)
        'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical'  => false,
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => array( 'slug' => 'realisations'),
    );
    // Pour enregistrer le type d'article personnalisé et ses arguments
    register_post_type( 'realisations', $args);
}
// Écouteur d'événement natif à wordpress qui fait appel à la fonction dès l'initialisation du thème
add_action( 'init', 'tim_realisation_custom_post', 0);
?>

<?php
/* Déclaration du type d'article personnalisé des FINISSANTS */
function tim_finissant_custom_post() {
    // Dénominations qui afficheront dans l'interface administrateur
    $labels = array(
        'name'                  => _X( 'Finissants de la TIM', 'Post Type General Name'),
        'singular_name'         => _X( 'Finissants', 'Post Type Singular Name'),
        'menu_name'             => __( 'Finissants 2020'),
        'all_items'             => __( 'Tous les finissants'),
        'view_item'             => __( 'Voir les finissants'),
        'add_new_item'          => __( 'Ajouter un nouveau finissant'),
        'add_new'               => __( 'Ajouter'),
        'edit_item'             => __( 'Éditer un finissant'),
        'update_item'           => __( 'Modifier un finissant'),
        'search_items'          => __( 'Rechercher un finissant'),
        'not_found'             => __( 'Non trouvée'),
        'not_found_in_trash'    => __( 'Non trouvée dans la corbeille'),
    );

    // Autres options pour l'article personnalisé
    $args = array (
        'label'         => __( 'Les finissants 2020'),
        'description'   => __( 'Tous les finissants 2020'),
        'labels'        => $labels,
        // Définir ici les options disponibles dans l'éditeur (enlever ceux qui pourrait être inutiles éventuellement)
        'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical'  => false,
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => array( 'slug' => 'finissants'),
    );
    // Pour enregistrer le type d'article personnalisé et ses arguments
    register_post_type( 'finissants', $args);
}
// Écouteur d'événement natif à wordpress qui fait appel à la fonction dès l'initialisation du thème
add_action( 'init', 'tim_finissant_custom_post', 0);
?>

<?php
/* Déclaration du type d'article personnalisé des TÉMOIGNAGES */
function tim_temoignage_custom_post() {
    // Dénominations qui afficheront dans l'interface administrateur
    $labels = array(
        'name'                  => _X( 'Témoignages d\'anciens de la TIM', 'Post Type General Name'),
        'singular_name'         => _X( 'Témoignages', 'Post Type Singular Name'),
        'menu_name'             => __( 'Témoignages d\'anciens'),
        'all_items'             => __( 'Tous les témoignages'),
        'view_item'             => __( 'Voir les témoignages'),
        'add_new_item'          => __( 'Ajouter un nouveau témoignage'),
        'add_new'               => __( 'Ajouter'),
        'edit_item'             => __( 'Éditer un témoignage'),
        'update_item'           => __( 'Modifier un témoignage'),
        'search_items'          => __( 'Rechercher un témoignage'),
        'not_found'             => __( 'Non trouvée'),
        'not_found_in_trash'    => __( 'Non trouvée dans la corbeille'),
    );

    // Autres options pour l'article personnalisé
    $args = array (
        'label'         => __( 'Les témoignages des anciens de la TIM'),
        'description'   => __( 'Tous les témoignages'),
        'labels'        => $labels,
        // Définir ici les options disponibles dans l'éditeur (enlever ceux qui pourrait être inutiles éventuellement)
        'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical'  => false,
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => array( 'slug' => 'temoignages'),
    );
    // Pour enregistrer le type d'article personnalisé et ses arguments
    register_post_type( 'temoignages', $args);
}
// Écouteur d'événement natif à wordpress qui fait appel à la fonction dès l'initialisation du thème
add_action( 'init', 'tim_temoignage_custom_post', 0);
?>

<?php
/* Déclaration du type d'article personnalisé des FINISSANTS */
function tim_accrocheQuestions_custom_post() {
    // Dénominations qui afficheront dans l'interface administrateur
    $labels = array(
        'name'                  => _X( 'Accroche pour questions et rediriger vers le responsable', 'Post Type General Name'),
        'singular_name'         => _X( 'AccrocheQuestions', 'Post Type Singular Name'),
        'menu_name'             => __( 'Accroche questions'),
        'all_items'             => __( 'Toutes les accroches'),
        'view_item'             => __( 'Voir les accroches'),
        'add_new_item'          => __( 'Ajouter un nouvel accroche'),
        'add_new'               => __( 'Ajouter'),
        'edit_item'             => __( 'Éditer un accroche'),
        'update_item'           => __( 'Modifier un accroche'),
        'search_items'          => __( 'Rechercher un accroche'),
        'not_found'             => __( 'Non trouvée'),
        'not_found_in_trash'    => __( 'Non trouvée dans la corbeille'),
    );

    // Autres options pour l'article personnalisé
    $args = array (
        'label'         => __( 'Accroche pour questions et rediriger vers le responsable'),
        'description'   => __( 'Tous les accroches'),
        'labels'        => $labels,
        // Définir ici les options disponibles dans l'éditeur (enlever ceux qui pourrait être inutiles éventuellement)
        'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical'  => false,
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => array( 'slug' => 'accroches'),
    );
    // Pour enregistrer le type d'article personnalisé et ses arguments
    register_post_type( 'accroches', $args);
}
// Écouteur d'événement natif à wordpress qui fait appel à la fonction dès l'initialisation du thème
add_action( 'init', 'tim_accrocheQuestions_custom_post', 0);
?>

<?php
/* Déclaration du type d'article personnalisé des SOCIAUX DE LA TIM CÉGEP */
function tim_sociauxTIM_custom_post() {
    // Dénominations qui afficheront dans l'interface administrateur
    $labels = array(
        'name'                  => _X( 'Liens vers la TIM sur les réseaux sociaux', 'Post Type General Name'),
        'singular_name'         => _X( 'SociauxTIM', 'Post Type Singular Name'),
        'menu_name'             => __( 'Réseaux sociaux TIM'),
        'all_items'             => __( 'Tous les liens vers les réseaux sociaux'),
        'view_item'             => __( 'Voir les reseaux sociaux'),
        'add_new_item'          => __( 'Ajouter un nouveau lien vers un réseau social'),
        'add_new'               => __( 'Ajouter'),
        'edit_item'             => __( 'Éditer le lien vers un réseau social'),
        'update_item'           => __( 'Modifier le lien vers un réseau social'),
        'search_items'          => __( 'Rechercher un lien vers un réseau social'),
        'not_found'             => __( 'Non trouvée'),
        'not_found_in_trash'    => __( 'Non trouvée dans la corbeille'),
    );

    // Autres options pour l'article personnalisé
    $args = array (
        'label'         => __( 'Réseaux sociaux TIM'),
        'description'   => __( 'Tous les liens vers les réseaux sociaux de la TIM'),
        'labels'        => $labels,
        // Définir ici les options disponibles dans l'éditeur (enlever ceux qui pourrait être inutiles éventuellement)
        'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical'  => false,
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => array( 'slug' => 'sociauxTIM'),
    );
    // Pour enregistrer le type d'article personnalisé et ses arguments
    register_post_type( 'sociauxTIM', $args);
}
// Écouteur d'événement natif à wordpress qui fait appel à la fonction dès l'initialisation du thème
add_action( 'init', 'tim_sociauxTIM_custom_post', 0);
?>

<?php
add_filter ( 'stylesheet_directory_uri' , 'gkp_stylesheet_directory_uri' , 10 , 2 );
function gkp_stylesheet_directory_uri ( $stylesheet_dir_uri , $stylesheet ) {
    // On ajoute le dossier css à l’adresse par défaut
    return $stylesheet_dir_uri . '/css' ;
}

add_filter ( 'stylesheet_uri' , 'gkp_stylesheet_uri' , 10 , 2 );
function gkp_stylesheet_uri ( $stylesheet_uri , $stylesheet_dir_uri ) {
    // On change le nom de la feuille de style de base
    return $stylesheet_dir_uri . '/styles.css' ;
}
?>

<?php
// ****** POUR REMETTRE ACTIF L'OPTION DES CHAMPS PERSONALISÉS NATIFS À WORDPRESS ******
// Étaient bloqués par ACF... sont utiles lorsqu'il y a un petit champ à ajouter dans une page,
// et qu'on veut le placer à un endroit spécifique du site avec le the_field(), sans créer un ACF juste pour ça!

add_filter ( 'acf/settings/remove_wp_meta_box', '__return_false');
?>


<?php
// ****** POUR TRANSFORMER LES CODES HEX EN RGB POUR LES STYLES EN LIGNE ******
// Code trouvé sur internet, appartient à Bandicoot Marketing au https://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);

    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    $rgb = array($r, $g, $b);
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}
?>

<?php remove_action('template_redirect', 'redirect_canonical'); ?>

<?php
/************************************
 * Validation côté serveur
 ************************************/

function validerFormulaire($nomChamp, $valChamp, $regex, $tMessagesJSON) {
    $message = '';
    $estValide = false;

    if ($valChamp == '') {
        $message = $tMessagesJSON[$nomChamp]['erreurs']['vide'];
    } else {
        $motifValide = preg_match($regex, $valChamp);
        if ($motifValide == false) {
            $message = $tMessagesJSON[$nomChamp]['erreurs']['motif'];
        }
        else {
            $estValide = true;
        }
    }

    $arrValidation = ["valeur"=>$valChamp, "message"=>$message, "estValide"=>$estValide];
    return $arrValidation;
}
?>

<?php
/****************************************************************************************
 * Fonction pour trouver les noms des finissants dans les descriptions des réalisations
 * (Trouvée sur internet, adaptée au besoin)
 ****************************************************************************************/
function getContents($str, $startDelimiter, $endDelimiter) {
    $contents = array();
    $startDelimiterLength = strlen($startDelimiter);
    $endDelimiterLength = strlen($endDelimiter);
    $startFrom = $contentStart = $contentEnd = 0;
    while (($contentStart = strpos($str, $startDelimiter, $startFrom))!== false) {
        $contentStart += $startDelimiterLength;
        $contentEnd = strpos($str, $endDelimiter, $contentStart);
        if ($contentEnd===false) {
            break;
        }
        $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
        $startFrom = $contentEnd + $endDelimiterLength;
    }

    return $contents;
}
?>
