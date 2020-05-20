<?php
/* Template name: Programme */
get_header();
?>

<main class="contenu programme">
    <!-- *** SECTION BANNER ***************************************************************************** -->
    <div class="programme__banner banner">
        <video autoplay muted loop class="banner__video" width="100" height="534">
            <source src="<?php echo get_template_directory_uri();?>/videos/FillerTim_greyscale_optim50.mp4" type="video/mp4"/>
            "Désolé, votre navigateur ne supporte pas ce format de vidéo."
        </video>
        <div class="conteneur">
            <h1 class="programme__titre h1--blanc">
                <?php the_title();?>
            </h1>
        </div>
    </div>

    <!-- *** SECTION DISCIPLINES ***************************************************************************** -->
    <section class="programme__disciplines disciplines">
        <h2 class="screen-reader-only">La TIM c'est ...</h2>
        <div class="visionneuse disciplines__visionneuse conteneur">
            <div class="visionneuse__image">
                <?php
                    /* Insertion du <svg> (élément graphique) de la charte des pourcentages */
                    get_template_part('content', 'decoProgramme');
                ?>
            </div>
            <div class="visionneuse__textes">
                <?php
                    $arrDisciplines = ["programmation", "integration", "design", "gestionConception", "creationMedias"];
                ?>
                <div class="glide">
                    <div data-glide-el="controls" class="visionneuse__controls">
                        <button data-glide-dir="<" class="glide__btnPrecedent">Design</button>
                        <button data-glide-dir=">" class="glide__btnSuivant">Intégration</button>
                    </div>
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides visionneuse__textes_liste">
                            <?php foreach ($arrDisciplines as $discipline):?>
                                <li class="glide__slide visionneuse__textes_listeItem <?php echo $discipline; ?>">
                                    <?php $laDiscipline = get_field($discipline); ?>
                                    <h3 class="disciplines__titre h2">
                                        <span class="disciplines__pourcentage"><?php echo $laDiscipline[$discipline.'__pourcentage'];?>% </span>
                                        <span><?php echo $laDiscipline[$discipline.'__titre'];?></span>
                                    </h3>
                                    <h4 class="screen-reader-only">Description :</h4>
                                    <?php echo $laDiscipline[$discipline.'__description'];?>
                                    <?php
                                    // Pour prendre les valeurs textuelles dans wordpress et les convertir en array
                                    $strEtiquettes = $laDiscipline[$discipline.'__etiquettes'];
                                    $arrConvertiEtiquettes = explode(",",$strEtiquettes);
                                    ?>
                                    <h4 class="screen-reader-only">Étiquettes :</h4>
                                    <ul class="disciplines__etiquettes etiquettes">
                                        <?php foreach ($arrConvertiEtiquettes as $etiquette):?>
                                            <li class="disciplines__etiquette">
                                                <?php echo $etiquette;?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- *** SECTION VIDÉO TIM ************************************************************************ -->
    <section class="videoTIM programme__videoTIM">
        <div class="conteneur conteneurFlex">
            <h2 class="videoTIM__titre h2--blanc"><?php the_field('video__titre');?></h2>
            <div>
                <iframe src="https://player.vimeo.com/video/392697655" style="position:absolute; top:0; left:0; width:100%; height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
            </div>
            <script src="https://player.vimeo.com/api/player.js"></script>
        </div>
    </section>

    <!-- *** SECTION ÉTUDIANT D'UN JOUR *************************************************************** -->
    <section class="conteneur etuJour programme__etuJour">
        <h2 class="etuJour__titre"><?php the_field('etudiantUnJour__titre');?></h2>
        <div class="etuJour__texte"><?php the_field('etudiantUnJour__texte');?></div>
        <div class="etuJour__accroche accroche">
            <?php $infosAccrocheEUJ = get_field('etudiantUnJour__lienResponsable');?>
            <h3 class="accroche__titre h3--deco"><?php echo get_field('questions__titre', $infosAccrocheEUJ->ID);?></h3>
            <?php
                $texteComplet = get_field('questions__texte', $infosAccrocheEUJ->ID);
                $objAccroche = get_field('questions__responsable', $infosAccrocheEUJ->ID);
                $nomResponsable = get_field('prenom', $objAccroche->ID) . " " . get_field('nom', $objAccroche->ID);

                $strAChercher = "Contacte " . $nomResponsable;
                $str1eMorceauChaine = '<a href="' . add_query_arg("ID", $objAccroche->ID, get_the_permalink(55)) . '" class="lien">' . $strAChercher . '</a>';
                $str2eMorceauChaine = str_replace($strAChercher, "", $texteComplet);
            ?>
            <p class="accroche__texte">
                <span class="conteneur__lien"><?php echo $str1eMorceauChaine; ?></span><?php echo $str2eMorceauChaine; ?>
            </p>
        </div>
    </section>

    <!-- *** SECTION INSCRIPTION ********************************************************************** -->
    <section class="conteneur inscription programme__inscription">
        <div class="conteneurFlex">
            <h2 class="inscription__titre"><?php the_field('inscription__titre');?></h2>
            <div class="inscription__texte"><?php the_field('inscription__texte');?></div>
        </div>
        <div class="conteneurFlex--bas">
            <div class="inscription__accroche accroche">
                <?php
                    // Pour afficher dynamiquement la prochaine date butoir pour l'inscription
                    $moisAujourdhui = date('m');
                    $arrMoisTours = ['03', '05', '06', '08'];
                    $arrMoisMots = ['03'=>'Mars', '05'=>'Avril', '06'=>'Juin', '08'=>'Août'];
                    $prochaineDate = "";

                    foreach($arrMoisTours as $moisTour) {
                        if ($moisAujourdhui < $moisTour) {
                            $prochaineDate = $arrMoisMots[$moisTour];
                            break;
                        }
                    }
                ?>
                <h3 class="accroche__titre h3--deco"><?php the_field('inscription__sousTitre');?>1er <?php echo $prochaineDate;?>!</h3>
            </div>
            <?php $inscriptionLien = get_field('inscription__lien');?>
            <div class="conteneur__bouton">
                <a class="bouton bouton__primaire" href="<?php echo $inscriptionLien['inscription__lienURL'];?>"><span class="bouton__txt"><?php echo $inscriptionLien['inscription__texteLien'];?></span><span class="icone icone__lienExterne"></span></a>
            </div>
        </div>
    </section>

    <!-- *** SECTION TEST TON PROFIL ********************************************************************** -->
    <section class="conteneur profil programme__profil">
        <h2 class="profil__titre"><?php the_field('testProfil__titre');?></h2>
        <div class="profil__accroche accroche">
            <h3 class="accroche__titre h3--deco"><?php the_field('testProfil__sousTitre');?></h3>
        </div>
        <?php $testProfilLien = get_field('testProfil__lien');?>
        <div class="conteneur__bouton">
            <a class="bouton bouton__primaire" href="<?php echo $testProfilLien['testProfil__urlLien'];?>"><span class="bouton__txt"><?php echo $testProfilLien['testProfil__texteLien2'];?></span><span class="icone icone__lienExterne"></span></a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
