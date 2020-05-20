<?php get_header();
?>

<main class="ficheRealisation">
    <div class="ficheRealisation__entete">
        <?php get_template_part('content', 'decoRealisations');?>
        <div class="conteneur clearfix">
            <div class="ficheRealisation__filAriane conteneur__lien">
                <a class="lien" href="<?php echo get_the_permalink(51)?>">
                    <span class="icone icone__chevron icone__chevron--gauche"></span>
                    <span class="ficheRealisation__filAriane_txt">Toutes les réalisations</span>
                </a>
            </div>
            <?php
            // ******************************************************************************
            // Visionneuse au format mobile (positionnement différent)
            // Avec Glide.js et Fancybox
            // ******************************************************************************
            ?>
            <div class="ficheRealisation__visionneuse--mobile visionneuse">
                <div class="glide glideMobile">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <?php for($intCpt=1; $intCpt<=count(get_field('images_realisation'));$intCpt++):
                                if (get_field('images_realisation')['image_realisation_'.$intCpt]!=false):?>
                                    <li class="glide__slide" style="background-color:<?php echo get_field('couleur_secondaire');?>">
                                        <a data-fancybox="gallery" href="<?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['sizes']['gallerie-realisations-mobile-2X'];?>">
                                            <picture>
                                                <source media="(min-width:601px)" srcset="<?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['sizes']['gallerie-realisations-mobile-2X'];?> 1x, <?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['url'];?> 2x"/>
                                                <source media="(max-width:600px)" srcset="<?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['sizes']['gallerie-realisations-mobile'];?> 1x, <?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['sizes']['gallerie-realisations-mobile-2X'];?> 2x"/>
                                                <img class="visionneuse__image" src="<?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['sizes']['gallerie-realisations-mobile-2X'];?>" alt="Image de la réalisation <?php the_field('titre'); ?>"/>
                                            </picture>
                                        </a>
                                    </li>
                            <?php endif; ?>
                            <?php endfor; ?>
                        </ul>
                    </div>
                    <div data-glide-el="controls" class="visionneuse__controls">
                        <button class="glide__arrow glide__arrow--left" data-glide-dir="<"><span class="icone icone__chevron icone__chevron--gauche"></span><span class="screen-reader-only">Précédente</span></button>
                        <div class="glide__bullets" data-glide-el="controls[nav]">
                            <?php for($intCpt=0; $intCpt<count(get_field('images_realisation'));$intCpt++):
                                if (get_field('images_realisation')['image_realisation_'. ($intCpt + 1)]!=false):?>
                                    <button class="glide__bullet" data-glide-dir="=<?php echo $intCpt; ?>"></button>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <button class="glide__arrow glide__arrow--right" data-glide-dir=">"><span class="icone icone__chevron icone__chevron--droite"></span><span class="screen-reader-only">Suivante</span></button>
                    </div>
                </div>
            </div>

            <h1 class="ficheRealisation__titre"><?php the_field('titre'); ?></h1>
        </div>
    </div>
    <div class="conteneur clearfix">
        <?php
        // ******************************************************************************
        // Section infos de la réalisation
        // ******************************************************************************
        ?>
        <section class="conteneurFlex ficheRealisation__infos realisationInfos">
            <?php
            $postsFinissant = get_posts(array(
                'post_type' => 'finissants',
                'posts_per_page' => 1,
                'post_status' => 'published',
                'meta_key' => 'id',
                'meta_value' => get_field('diplome_id', $post->ID),
            ));
            ?>
            <div class="realisationInfos__textes">
                <div class="realisationInfos__finissant">
                    <picture class="realisationInfos__finissant_photo finissant__photo">
                        <img srcset="<?php echo get_field('photo', $postsFinissant[0]->ID)['sizes']['diplomes-carre-mini-1x'];?> 1x, <?php echo get_field('photo', $postsFinissant[0]->ID)['sizes']['diplomes-carre-1xGros-2xPetit']?> 2x" src="<?php echo get_field('photo', $postsFinissant[0]->ID)['sizes']['diplomes-carre-1xGros-2xPetit']?>" alt="Photo de <?php echo get_field('prenom', $postsFinissant[0]->ID) . " " . get_field('nom', $postsFinissant[0]->ID);?>" class="noLazyLoad"/>
                    </picture>
                    <p class="realisationInfos__finissant_nom">
                        <span>par</span>
                        <span class="conteneur__lien">
                            <?php $hexToRgb = hex2rgb(get_field('couleur_principale'));?>
                            <a class="lien__realisation lien"
                               href="#diplome"
                               style="border-bottom-color: rgba(<?php echo $hexToRgb[0] . ',' . $hexToRgb[1] . ',' . $hexToRgb[2];?>,0.8)">
                                <?php if($postsFinissant){
                                    echo get_field('prenom', $postsFinissant[0]->ID) . " " . get_field('nom', $postsFinissant[0]->ID);
                                }?>
                            </a>
                        </span>
                    </p>
                </div>
                <div class="realisationInfos__description">
                    <h2 class="h3">Description :</h2>
                    <?php
                        // *** 1. Aller chercher les noms des coéquipiers dans la description (seront entourés de balises <a>
                        // *** Appel à la fonction utilitaire getContents(...) mise dans le functions.php
                        $descriptionOriginale = get_field('description');
                        $descriptionAvecBalises = htmlspecialchars($descriptionOriginale);
                        $arrNomsCooequipiers = getContents($descriptionAvecBalises, htmlspecialchars('<a href="#">'), htmlspecialchars('</a>'));

                        // *** 2. Aller chercher les id des cooéquipiers dans la BD, puis les mettre dams un tableau
                        $arrChamps = array('prenom', 'nom');
                        $arrIdCooequipiers = array();
                        if (count($arrNomsCooequipiers)!=0) {
                            for ($intTousCooequipiers=0; $intTousCooequipiers<count($arrNomsCooequipiers); $intTousCooequipiers++) {
                                $arrPrenomNomSepares = explode(" ",$arrNomsCooequipiers[$intTousCooequipiers]);
                                $meta_query = array('relation'=>'AND');
                                for($intCooequipier=0; $intCooequipier<count($arrPrenomNomSepares); $intCooequipier++) {
                                        array_push($meta_query, array(
                                            'key' => $arrChamps[$intCooequipier],
                                            'compare' => '=',
                                            'value' => $arrPrenomNomSepares[$intCooequipier]
                                        ));
                                }
                                $postsCooequipier = get_posts(array(
                                    'post_type' => 'finissants',
                                    'posts_per_page' => 1,
                                    'post_status' => 'published',
                                    'meta_query' => $meta_query,
                                ));
                                array_push($arrIdCooequipiers, get_field('id', $postsCooequipier[0]->ID));
                            }
                        }

                        // *** 3. Remplacer les href des balises a par un lien vers la page des réalisations, filtrée selon l'étudiant
                        // *** Ajouter aussi une classe pour les liens.
                        $intIdCooequipier = 0;
                        while(($positionBaliseOuvrante = strpos($descriptionAvecBalises, htmlspecialchars('<a href="#">')))!=false) {
                            $hrefAvecID = '<a href="'.get_the_permalink(51) .'?etudiant='.$arrIdCooequipiers[$intIdCooequipier].'" class="lien">';
                            $descriptionAvecBalises = substr_replace($descriptionAvecBalises, htmlspecialchars($hrefAvecID), $positionBaliseOuvrante, strlen(htmlspecialchars('<a href="#">')));
                            $intIdCooequipier++;
                        }
                    ?>
                    <?php
                    // *** 4. Ajouter le texte modifié à la page
                        echo htmlspecialchars_decode($descriptionAvecBalises);
                    ?>
                </div>
                <div class="realisationInfos__techno">
                    <h2 class="h3">Technologies/Logiciels</h2>
                    <?php the_field('technologies'); ?>
                </div>
                <?php if (get_field('url')!=""):?>
                    <div class="conteneur__bouton">
                        <a class="bouton bouton__primaire" href="<?php echo get_field('url');?>">Voir ce projet en action <span class="icone icone__lienExterne"></span></a>
                    </div>
                <?php endif;?>
            </div>
            <?php
            // ******************************************************************************
            // Visionneuse au format table (positionnement différent)
            // Avec Glide.js et Fancybox
            // ******************************************************************************
            ?>
            <div class="realisationInfos__visionneuse ficheRealisation__visionneuse--table visionneuse">
                <div class="glide glideTable">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <?php for($intCpt=1; $intCpt<=count(get_field('images_realisation'));$intCpt++):
                                if (get_field('images_realisation')['image_realisation_'.$intCpt]!=false):?>
                                    <li class="glide__slide" style="background-color:<?php echo get_field('couleur_secondaire');?>">
                                        <a data-fancybox="gallery" href="<?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['url'];?>">
                                            <img class="visionneuse__image" srcset="<?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['sizes']['gallerie-realisations-mobile'];?> 1x, <?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['sizes']['gallerie-realisations-mobile-2X'];?> 2x" src="<?php echo get_field('images_realisation')['image_realisation_'.$intCpt]['sizes']['gallerie-realisations-mobile-2X'];?>" alt="Image de la réalisation <?php the_field('titre'); ?>"/>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </ul>
                    </div>
                    <div data-glide-el="controls" class="visionneuse__controls">
                        <button class="glide__arrow glide__arrow--left" data-glide-dir="<"><span class="icone icone__chevron icone__chevron--gauche"></span><span class="screen-reader-only">Précédente</span></button>
                        <div class="glide__bullets" data-glide-el="controls[nav]">
                            <?php for($intCpt=0; $intCpt<count(get_field('images_realisation'));$intCpt++):
                                  if (get_field('images_realisation')['image_realisation_'. ($intCpt + 1)]!=false):?>
                                    <button class="glide__bullet" data-glide-dir="=<?php echo $intCpt; ?>"></button>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <button class="glide__arrow glide__arrow--right" data-glide-dir=">"><span class="icone icone__chevron icone__chevron--droite"></span><span class="screen-reader-only">Suivante</span></button>
                    </div>
                </div>
            </div>
        </section>

        <?php
        // ******************************************************************************
        // Section des informations du finissant/diplômé
        // ******************************************************************************
        ?>
        <section class="ficheRealisation__finissant finissant">
            <?php if ($postsFinissant): ?>
            <div class="finissant__conteneur">
                <div id="diplome" class="finissant__photoSociaux">
                    <div class="finissant__photo"
                             style="color: rgb(<?php echo $hexToRgb[0] . ',' . $hexToRgb[1] . ',' . $hexToRgb[2];?>);">
                        <img srcset="<?php echo get_field('photo', $postsFinissant[0]->ID)['sizes']['diplomes-carre-1xGros-2xPetit'];?> 1x, <?php echo get_field('photo', $postsFinissant[0]->ID)['url']?> 2x" src="<?php echo get_field('photo', $postsFinissant[0]->ID)['url']?>" alt="Photo de <?php echo get_field('prenom', $postsFinissant[0]->ID) . " " . get_field('nom', $postsFinissant[0]->ID);?>" class="noLazyLoad"/>
                    </div>
                    <h3 class="screen-reader-only">Pour communiquer avec <?php echo get_field('prenom', $postsFinissant[0]->ID);?> : </h3>
                    <ul class="finissant__sociaux">
                        <li class="finissant__courriel finissant__sociaux_btn">
                            <a href="mailto:<?php echo get_field('courriel', $postsFinissant[0]->ID)?>">
                                <span class="icone icone__courriel"></span>
                                <span><span class="tableOnly">Envoyer un </span>courriel</span>
                            </a>
                        </li>
                        <?php if (get_field('site_web', $postsFinissant[0]->ID)!=null): ?>
                            <li class="finissant__portfolio finissant__sociaux_btn">
                                <a href="<?php the_field('site_web', $postsFinissant[0]->ID); ?>">
                                    <span class="icone icone__portfolio"></span>
                                    <span>Portfolio<span class="tableOnly">/page web</span></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (get_field('pseudo_twitter', $postsFinissant[0]->ID)!=null): ?>
                            <li class="finissant__twitter finissant__sociaux_btn">
                                <a href="<?php the_field('pseudo_twitter', $postsFinissant[0]->ID); ?>">
                                    <span class="icone icone__twitter"></span>
                                    <span><span class="tableOnly">Compte </span>twitter</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (get_field('linkedin', $postsFinissant[0]->ID)!=null): ?>
                            <li class="finissant__linkedin finissant__sociaux_btn">
                                <a href="<?php the_field('linkedin', $postsFinissant[0]->ID) ?>">
                                    <span class="icone icone__linkedin"></span>
                                    <span><span class="tableOnly">Profil </span>LinkedIn</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="conteneurFlex">
                    <article class="finissant__informations">
                        <header class="finissant__intro">
                            <h2 class="finissant__titre"><?php echo get_field('prenom', $postsFinissant[0]->ID) . " " . get_field('nom', $postsFinissant[0]->ID);?></h2>
                            <div class="finissant__preferences preferences">
                                <?php
                                $arrPreferences = array("Gestion de projet" => get_field('interet_gestion_projet', $postsFinissant[0]->ID), "Programmation" => get_field('interet_programmation', $postsFinissant[0]->ID), "Intégration" => get_field('interet_integration', $postsFinissant[0]->ID), "Design" => get_field('interet_design_interface', $postsFinissant[0]->ID), "Médias" => get_field('interet_traitement_media', $postsFinissant[0]->ID));
                                arsort($arrPreferences);
                                $arrChampsTxt = array_keys($arrPreferences);
                                ?>
                                <div class="preferences__icone">
                                    <span class="screen-reader-only">Préférences :</span>
                                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg" class="preferences__coeur">
                                        <path d="M17.6891 4.32611C17.2738 3.90569 16.7806 3.57219 16.2377 3.34465C15.6949 3.11711 15.1131 3 14.5255 3C13.9379 3 13.3561 3.11711 12.8133 3.34465C12.2704 3.57219 11.7772 3.90569 11.3619 4.32611L10.4998 5.19821L9.63771 4.32611C8.79866 3.4773 7.66066 3.00044 6.47407 3.00044C5.28747 3.00044 4.14947 3.4773 3.31042 4.32611C2.47137 5.17492 2 6.32616 2 7.52656C2 8.72696 2.47137 9.87819 3.31042 10.727L4.1725 11.5991L10.4998 18L16.8271 11.5991L17.6891 10.727C18.1047 10.3068 18.4344 9.80785 18.6593 9.25871C18.8842 8.70957 19 8.12097 19 7.52656C19 6.93214 18.8842 6.34355 18.6593 5.7944C18.4344 5.24526 18.1047 4.74633 17.6891 4.32611V4.32611Z" stroke="rgb(<?php echo $hexToRgb[0] . ',' . $hexToRgb[1] . ',' . $hexToRgb[2];?>)" stroke-opacity="1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="preferences__no1"><?php echo $arrChampsTxt[0]; ?></span>
                                <div class="preferences__icone">
                                    <span class="screen-reader-only"> et </span>
                                    <svg class="preferences__plus" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 1.67273V18.4001" stroke="rgb(<?php echo $hexToRgb[0] . ',' . $hexToRgb[1] . ',' . $hexToRgb[2];?>)" stroke-opacity="1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M1.6665 10.0364H18.3332" stroke="rgb(<?php echo $hexToRgb[0] . ',' . $hexToRgb[1] . ',' . $hexToRgb[2];?>)" stroke-opacity="1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="preferences__no2"><?php echo $arrChampsTxt[1]; ?></span>
                            </div>
                        </header>
                        <div class="finissant__contenu">
                            <div class="finissant__texte"><?php echo get_field('presentation', $postsFinissant[0]->ID); ?></div>
                            <div class="finissant__autresRealisations">
                                <h3>Autres réalisations</h3>
                                <div class="finissant__autresRealisations_liste">
                                    <?php
                                    $postsRealisations = get_posts(array(
                                        'post_type' => 'realisations',
                                        'posts_per_page' => 4,
                                        'post_status' => 'published',
                                        'meta_key' => 'diplome_id',
                                        'meta_value' => get_field('id', $postsFinissant[0]->ID),
                                    ));
                                    if($postsRealisations):
                                        foreach($postsRealisations as $uneRealisation):
                                            if(get_field('id', $uneRealisation->ID) != get_field('id')):?>
                                                <article class="finissant__autresRealisations_listeItem autreRealisation">
                                                    <div class="autreRealisation__image">
                                                        <a href="<?php the_permalink($uneRealisation->ID)?>">
                                                            <img srcset="<?php echo get_field('images_realisation', $uneRealisation->ID)['image_realisation_1']['sizes']['gallerie-realisations'];?> 1x, <?php echo get_field('images_realisation', $uneRealisation->ID)['image_realisation_1']['sizes']['gallerie-realisations-2X'];?> 2x" src="<?php echo get_field('images_realisation', $uneRealisation->ID)['image_realisation_1']['sizes']['gallerie-realisations-2X'];?>" alt="Image de la réalisation <?php the_field('titre', $uneRealisation->ID);?>"/>
                                                        </a>
                                                    </div>
<!--                                                    <p class="autreRealisation__titre">--><?php //the_field('titre', $uneRealisation->ID); ?><!--</p>-->
                                                    <span class="conteneur__lien">
                                                <?php $hexToRgb = hex2rgb(get_field('couleur_principale', $uneRealisation->ID));?>
                                                <a class="lien__realisation lien"
                                                   href="<?php the_permalink($uneRealisation->ID)?>"
                                                   style="border-bottom-color: rgba(<?php echo $hexToRgb[0] . ',' . $hexToRgb[1] . ',' . $hexToRgb[2];?>,0.8)">
                                                    En voir plus
                                                </a>
                                            </span>
                                                </article>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php get_footer(); ?>