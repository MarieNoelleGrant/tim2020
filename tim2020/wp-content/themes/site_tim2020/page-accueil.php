<!-- Appel à l'entête de page -->
<?php
    /*Template name: Accueil*/
    get_header();
?>

<main class="accueil">
    <?php
    $postsRealsAccueil = get_posts(array(
        'post_type' => 'realisations',
        'posts_per_page' => 1,
        'post_status' => 'published',
        'meta_key' => 'est_expose_accueil',
        'meta_value' => 1,
        'orderby' => 'rand'
    ));

    if($postsRealsAccueil):
    $post = $postsRealsAccueil[0];
    ?>

    <div class="deco__realisations_conteneur">
        <?php get_template_part('content', 'decoRealisations');?>
    </div>

    <div class="accueil__titreAccroche">
        <div class="accueil__titre">
            <h1 class="screen-reader-only">Techniques d'intégration multimédia</h1>
            <img class="accueil__logo" src="<?php echo get_template_directory_uri();?>/images/logo_long_accueil.svg" alt="Logo au long de la TIM"/>
        </div>
        <div class="accueil__conteneurAccroche">
            <span class="h2 h2--blanc accueil__accroche">C'est quoi la TIM?</span>
        </div>
    </div>
    <div class="accueil__realisation">
        <div class="accueil__realisation_fondCouleur" style="background-color:<?php echo get_field('couleur_secondaire');?>">
            <div class="accueil__realisation_picture <?php echo 'accueil__realisation_'. get_field('id');?>" style="background-image: url('<?php echo get_field('images_realisation')['image_realisation_1']['url'];?>')"></div>
        </div>
        <div class="accueil__realisation_btnAccueil">
            <div class="conteneur__bouton">
                <a href="<?php bloginfo('url');?>/" class="bouton bouton__primaire">
                    <span class="icone icone__random"></span>
                    <span class="bouton__txt">Une autre!</span>
                </a>
            </div>
        </div>
        <article class="accueil__realisation_infos realisation__infos">
            <p class="h4 realisation__titre">
                <?php the_field('titre'); ?>
            </p>
            <p class="realisation__finissant">
                <?php
                    $postsFinissant = get_posts(array(
                        'post_type' => 'finissants',
                        'posts_per_page' => 1,
                        'post_status' => 'published',
                        'meta_key' => 'id',
                        'meta_value' => get_field('diplome_id'),
                    ));
                    if($postsFinissant){
                        echo "par ".get_field('prenom', $postsFinissant[0]->ID) . " " . get_field('nom', $postsFinissant[0]->ID);
                    }
                ?>
            </p>
            <p class="realisation__lien conteneur__lien">
                <?php $hexToRgb = hex2rgb(get_field('couleur_principale'));?>
                <a class="lien__realisation lien"
                   href="<?php the_permalink()?>"
                   style="border-bottom-color: rgba(<?php echo $hexToRgb[0] . ',' . $hexToRgb[1] . ',' . $hexToRgb[2];?>,0.8)">
                    En voir plus
                </a>
            </p>
        </article>

        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>


