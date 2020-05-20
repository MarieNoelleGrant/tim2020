<?php
/*Template name: Realisations*/

    get_header();

?>

<main class="realisations">
    <div class="realisations__banner conteneur">
        <h1 class="realisations__titre"><?php the_title(); ?></h1>
        <p class="realisations__texte"><?php the_field('realisations__intro'); ?></p>
    </div>
    <div class="conteneur">
        <div class="realisations__filtres filtres">
            <?php
            // **** Construction des arrays pour chaque filtre, si le $_POST n'est pas vide
            // **** Fait en même temps le calcul pour le nombre de filtres (Pourquoi je le fais si haut dans la page)

            $nbFiltresAppliques = 0;

            // a. Filtres par discipline
            $arrChampDiscipline = array();
            if (isset($_POST['discipline'])) {
                $champTriDiscipline = 'etiquettes__disciplines';
                $arrChampDiscipline = $_POST['discipline'];
                $nbFiltresAppliques = $nbFiltresAppliques + count($arrChampDiscipline);
            }
            // b. Filtres par session
            $arrChampSession = array();
            if (isset($_POST['session'])) {
                $champTriSession = 'session';
                $arrChampSession = $_POST['session'];
                $nbFiltresAppliques = $nbFiltresAppliques + count($arrChampSession);
            }
            // c. Filtres par mots clés
            $arrChampMotsCles = array();
            if (isset($_POST['motsCles'])) {
                $champTriMotsCles = 'etiquettes__motscles';
                $arrChampMotsCles = $_POST['motsCles'];
                $nbFiltresAppliques = $nbFiltresAppliques + count($arrChampMotsCles);
            }
            // d. Filtres par diplômés
            $arrChampEtudiants = array();
            if (isset($_POST['etudiants'])) {
                $champTriEtudiants = 'diplome_id';
                $arrChampEtudiants = $_POST['etudiants'];
                $nbFiltresAppliques = $nbFiltresAppliques + count($arrChampEtudiants);
            }

            // (((( SI ON ARRIVE DE LA PAGE FICHE D'UNE RÉALISATIONS EN CLIQUANT SUR UN DIPLOMÉ ))))
            // (((( Filtrera les réalisations selon le diplômé en question ))))
            if (isset($_GET['etudiant'])) {
                $champTriEtudiants = 'diplome_id';
                $arrChampEtudiants = $_GET['etudiant'];
                $nbFiltresAppliques = 1;
            }

            // e. Filtres par type de projet.
            $arrChampProjets = array();
            if (isset($_POST['projets'])) {
                $champTriProjets = 'etiquettes__projets';
                $arrChampProjets = $_POST['projets'];
                $nbFiltresAppliques = $nbFiltresAppliques + count($arrChampProjets);
            }
            ?>
            <header class="filtres__entete">
                <div class="filtres__titre filtres__btnControle focusable" tabindex="0">
                    <div class="filtres__titreIcone">
                        <span class="icone icone__filtre"></span>
                        <span class="filtres__titre_txt">Filtres</span>
                        <span class="filtres__nb <?php if($nbFiltresAppliques==0) { echo 'filtres__nb--cache'; }?>"><?php if($nbFiltresAppliques!=0) { echo $nbFiltresAppliques; }?></span>
                    </div>
                </div>
            </header>
            <form action="" method="post" class="filtres__formulaire filtres__formulaire--ferme">
                <ul class="filtres__liste tableOnly">
                    <li class="filtres__listeItem diplomes tableOnly" aria-expanded="false" aria-controls="diplomes">
                        <button type="button" class="filtres__onglet onglet onglet__diplomes">
                            <span class="onglet__texte">Diplômés</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </li>
                    <li class="filtres__listeItem sessions tableOnly" aria-expanded="false" aria-controls="sessions">
                        <button type="button" class="filtres__onglet onglet onglet__sessions">
                            <span class="onglet__texte">Sessions</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </li>
                    <li class="filtres__listeItem disciplines tableOnly" aria-expanded="false" aria-controls="disciplines">
                        <button type="button" class="filtres__onglet onglet onglet__disciplines">
                            <span class="onglet__texte">Disciplines</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </li>
                    <li class="filtres__listeItem motscles tableOnly" aria-expanded="false" aria-controls="motsCles">
                        <button type="button" class="filtres__onglet onglet onglet__motsCles">
                            <span class="onglet__texte">Mots clés</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </li>
                    <li class="filtres__listeItem projets tableOnly" aria-expanded="false" aria-controls="projets">
                        <button type="button" class="filtres__onglet onglet onglet__projets">
                            <span class="onglet__texte">Types projets</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </li>
                </ul>
        <!-- **************************************************************************************************
             Éléments de formulaire (champs)
             ************************************************************************************************** -->
                <div class="filtres__elementsFormulaires">

                    <div class="filtres__listeItem diplomes tabletteOnly">
                        <button type="button" class="filtres__onglet onglet onglet__diplomes">
                            <span class="onglet__texte">Diplômés</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </div>
                    <fieldset class="filtre filtre--cache diplomes__champs" id="diplomes">
                        <legend class="filtre__legend screen-reader-only">Sélectionnez un ou plusieurs diplômés</legend>
                            <?php
                                $posts = get_posts(array(
                                    'post_type' => 'finissants',
                                    'posts_per_page' => -1,
                                    'post_status' => 'published',
                                    'order' => 'ASC'
                                ));

                                if($posts):
                                    for ($intCpt3=1;$intCpt3<count($posts);$intCpt3++):?>
                                        <div class="form__conteneur">
                                            <input type="checkbox"
                                                   id="etudiant<?php echo get_field('id', $posts[$intCpt3]); ?>"
                                                   name="etudiants[]"
                                                   value="<?php echo get_field('id', $posts[$intCpt3]); ?>"
                                                <?php if (isset($_POST['etudiants']) && in_array(get_field('id', $posts[$intCpt3]), $_POST['etudiants']) || isset($_GET['etudiant']) && get_field('id', $posts[$intCpt3]) == $_GET['etudiant']){ echo "checked"; }?>>
                                            <label for="etudiant<?php echo get_field('id', $posts[$intCpt3]); ?>">
                                                <?php echo get_field('prenom', $posts[$intCpt3]); ?>
                                                <?php echo get_field('nom', $posts[$intCpt3]); ?>
                                            </label>
                                       </div>
                                    <?php endfor; ?>
                                <?php endif; ?>
                    </fieldset>

                    <div class="filtres__listeItem sessions tabletteOnly">
                        <button type="button" class="filtres__onglet onglet onglet__sessions">
                            <span class="onglet__texte">Sessions</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </div>
                    <fieldset class="filtre filtre--cache sessions__champs" id="sessions">
                        <legend class="filtre__legend screen-reader-only">Sélectionnez une ou plusieurs sessions</legend>
                            <?php for($intCpt=1; $intCpt<=5; $intCpt++):?>
                                <div class="form__conteneur">
                                    <input type="checkbox" id="sess<?php echo $intCpt;?>" name="session[]" value="<?php echo $intCpt;?>" class="form__checkbox" <?php if (isset($_POST['session']) && in_array($intCpt, $_POST['session'])){ echo "checked"; }?>/>
                                    <label for="sess<?php echo $intCpt;?>">Session <?php echo $intCpt;?></label>
                                </div>
                                <?php endfor;?>
                    </fieldset>

                    <div class="filtres__listeItem disciplines tabletteOnly">
                        <button type="button" class="filtres__onglet onglet onglet__disciplines">
                            <span class="onglet__texte">Disciplines</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </div>
                    <fieldset class="filtre filtre--cache disciplines__champs" id="disciplines">
                        <legend class="filtre__legend screen-reader-only">Sélectionnez une ou plusieurs disciplines</legend>

                        <div class="form__conteneur">
                            <input type="checkbox" id="programmation" name="discipline[]" value="programmation" class="form__checkbox" <?php if (isset($_POST['discipline']) && in_array('programmation', $_POST['discipline'])){ echo "checked"; }?>/>
                            <label for="programmation">Programmation</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="integration" name="discipline[]" value="integration" class="form__checkbox" <?php if (isset($_POST['discipline']) && in_array('integration', $_POST['discipline'])){ echo "checked"; }?>/>
                            <label for="integration">Intégration</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="design" name="discipline[]" value="design" class="form__checkbox" <?php if (isset($_POST['discipline']) && in_array('design', $_POST['discipline'])){ echo "checked"; }?>/>
                            <label for="design">Design</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="gestion" name="discipline[]" value="gestion" class="form__checkbox" <?php if (isset($_POST['discipline']) && in_array('gestion', $_POST['discipline'])){ echo "checked"; }?>/>
                            <label for="gestion">Gestion</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="creationMedias" name="discipline[]" value="creationMedias" class="form__checkbox" <?php if (isset($_POST['discipline']) && in_array('creationMedias', $_POST['discipline'])){ echo "checked"; }?>/>
                            <label for="creationMedias">Création de médias</label>
                        </div>
                    </fieldset>

                    <div class="filtres__listeItem motscles tabletteOnly">
                        <button type="button" class="filtres__onglet onglet onglet__motsCles">
                            <span class="onglet__texte">Mots clés</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </div>
                    <fieldset class="filtre filtre--cache motscles__champs" id="motsCles">
                        <legend class="filtre__legend screen-reader-only">Sélectionnez un ou plusieurs mots clés</legend>
                        <div class="form__conteneur">
                            <input type="checkbox" id="ecomm" name="motsCles[]" value="ecomm" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('ecomm', $_POST['motsCles'])){ echo "checked"; }?>/>
                            <label for="ecomm">E-commerce</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="webapp" name="motsCles[]" value="webapp" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('webapp', $_POST['motsCles'])){ echo "checked"; }?>/>
                            <label for="webapp">App web</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="patronsConception" name="motsCles[]" value="patronsConception" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('patronsConception', $_POST['motsCles'])){ echo "checked"; }?>/>
                            <label for="patronsConception">Patrons conception</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="ajax" name="motsCles[]" value="ajax" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('ajax', $_POST['motsCles'])){ echo "checked"; }?>/>
                            <label for="ajax">Ajax</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="php" name="motsCles[]" value="php" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('php', $_POST['motsCles'])){ echo "checked"; }?>/>
                            <label for="php">PHP</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="html" name="motsCles[]" value="html" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('html', $_POST['motsCles'])){ echo "checked"; }?>/>
                            <label for="html">HTML</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="animations" name="motsCles[]" value="animations" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('animations', $_POST['motsCles'])){ echo "checked"; }?>/>
                            <label for="animations">Animations</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="css" name="motsCles[]" value="css" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('css', $_POST['motsCles'])){ echo "checked"; }?>>
                            <label for="css">CSS</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="montage" name="motsCles[]" value="montage" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('montage', $_POST['motsCles'])){ echo "checked"; }?>/>
                            <label for="montage">Montage</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="checkbox" id="enEquipe" name="motsCles[]" value="enEquipe" class="form__tags" <?php if (isset($_POST['motsCles']) && in_array('enEquipe', $_POST['motsCles'])){ echo "checked"; }?>/>
                            <label for="enEquipe">En équipe</label>
                        </div>
                    </fieldset>

                    <div class="filtres__listeItem projets tabletteOnly">
                        <button type="button" class="filtres__onglet onglet onglet__projets">
                            <span class="onglet__texte">Types projets</span>
                            <span class="onglet__icone icone icone__chevron"></span>
                        </button>
                    </div>
                    <fieldset class="filtre filtre--cache projets__champs" id="projets">
                        <legend class="filtre__legend screen-reader-only">Sélectionnez un ou plusieurs types de projets</legend>
                        <div class="form__conteneur">
                            <input type="radio" id="traces" name="projets[]" value="traces" class="form__checkbox" <?php if (isset($_POST['projets']) && in_array('traces', $_POST['projets'])){ echo "checked"; }?>/>
                            <label for="traces">Traces/E-commerce</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="radio" id="jeu" name="projets[]" value="jeu" class="form__checkbox" <?php if (isset($_POST['projets']) && in_array('jeu', $_POST['projets'])){ echo "checked"; }?>/>
                            <label for="jeu">Jeu/CreateJS et orientée objet</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="radio" id="festivalOFF" name="projets[]" value="festivalOFF" class="form__checkbox" <?php if (isset($_POST['projets']) && in_array('festivalOFF', $_POST['projets'])){ echo "checked"; }?>/>
                            <label for="festivalOFF">Festival OFF/Introduction PHP</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="radio" id="desinationRoadtrip" name="projets[]" value="destinationRoadtrip" class="form__checkbox" <?php if (isset($_POST['projets']) && in_array('destinationRoadtrip', $_POST['projets'])){ echo "checked"; }?>/>
                            <label for="desinationRoadtrip">Destination Roadtrip/Soumission client</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="radio" id="quiz" name="projets[]" value="quiz" class="form__checkbox" <?php if (isset($_POST['projets']) && in_array('quiz', $_POST['projets'])){ echo "checked"; }?>/>
                            <label for="quiz">Quiz/App web progressive</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="radio" id="agenceWeb" name="projets[]" value="agenceWeb" class="form__checkbox" <?php if (isset($_POST['projets']) && in_array('agenceWeb', $_POST['projets'])){ echo "checked"; }?>/>
                            <label for="agenceWeb">Agence Web/Wordpress</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="radio" id="tofu" name="projets[]" value="tofu" class="form__checkbox" <?php if (isset($_POST['projets']) && in_array('tofu', $_POST['projets'])){ echo "checked"; }?>/>
                            <label for="tofu">Tofu</label>
                        </div>
                        <div class="form__conteneur">
                            <input type="radio" id="autres" name="projets[]" value="autres" class="form__checkbox" <?php if (isset($_POST['projets']) && in_array('autres', $_POST['projets'])){ echo "checked"; }?>/>
                            <label for="autres">Autres</label>
                        </div>
                    </fieldset>
                </div>
                <div class="form__boutons filtres__boutons">
                    <div class="form__reinitialiser conteneur__bouton">
                        <a type="button" href="<?php the_permalink(51); ?>" class="bouton bouton__secondaire bouton__form">
                            <span class="bouton__icone icone icone__rafraichir"></span>
                            <span class="bouton__txt">Réinitialiser</span>
                        </a>
                    </div>
                    <div class="form__submit conteneur__bouton">
                        <button type="submit" class="bouton bouton__primaire bouton__form">
                            <span class="bouton__txt">Appliquer</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="realisations__conteneur">

        <section class="realisations__gallerie">
            <?php
                // **** Affichage selon les filtres appliqués (query)

                // *** Vérification si les array sont différents de vide, pour pas passer dans le code innutilement
                if (count($arrChampDiscipline)!=0 || count($arrChampSession)!=0 || count($arrChampMotsCles)!=0 || count($arrChampEtudiants)!=0 || count($arrChampProjets)!=0) {
                    $meta_query = [];

                    // *** Construction de la meta_query, un filtre à la fois.
                    // a. Filtres par discipline
                    if (count($arrChampDiscipline)!=0) {
                        $arrToutDiscipline = [];
                        array_push($arrToutDiscipline, array('relation'=>'OR'));
                        foreach($arrChampDiscipline as $discipline){
                            array_push($arrToutDiscipline, array(
                                'key' => 'etiquettes_'.$champTriDiscipline,
                                'compare' => 'LIKE',
                                'value' => $discipline
                            ));
                        }
                        array_push($meta_query, $arrToutDiscipline);
                    }

                    // b. Filtres par session
                    if (count($arrChampSession)!=0) {
                        $arrToutSession = [];
                        array_push($arrToutSession, array('relation'=>'AND'));
                        array_push($arrToutSession, array(
                            'key' => $champTriSession,
                            'value' => $arrChampSession,
                            'compare' => 'IN'
                        ));
                        array_push($meta_query, $arrToutSession);
                    }

                    // c. Filtres par mots clés
                    if (count($arrChampMotsCles)!=0) {
                        $arrToutMotsCles = [];
                        array_push($arrToutMotsCles, array('relation'=>'OR'));
                        foreach($arrChampMotsCles as $leMotCle) {
                            array_push($arrToutMotsCles, array(
                                'key' => 'etiquettes_'.$champTriMotsCles,
                                'value' => $leMotCle,
                                'compare' => 'LIKE'
                            ));
                        }
                        array_push($meta_query, $arrToutMotsCles);
                    }

                    // d. Filtres par diplômés
                    if (count($arrChampEtudiants)!=0) {
                        $arrToutEtudiants = [];
                        array_push($arrToutEtudiants, array('relation'=>'OR'));
                        array_push($arrToutEtudiants, array(
                            'key' => 'diplome_id',
                            'value' => $arrChampEtudiants,
                            'compare' => 'IN'
                        ));

                        array_push($meta_query, $arrToutEtudiants);
                    }

                    // e. Filtres par type de projet.
                    if (count($arrChampProjets)!=0) {
                        $arrToutProjets = [];
                        array_push($arrToutProjets, array('relation'=>'AND'));
                        foreach($arrChampProjets as $leProjet) {
                            array_push($arrToutProjets, array(
                                'key' => 'etiquettes_'.$champTriProjets,
                                'value' => $leProjet,
                                'compare' => 'LIKE'
                            ));
                        }
                        array_push($meta_query, $arrToutProjets);
                    }

                    // *** Exécute la requête avec la meta_query construite
                    $posts = get_posts(array(
                        'post_type' => 'realisations',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'order' => 'ASC',
                        'meta_query' => $meta_query
                        ));
                }
                // *** Si tous les array des filtres sont vides, exécute la requête simplement, et affiche toutes les réalisations.
                else {
                    $posts = get_posts(array(
                        'post_type' => 'realisations',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'order' => 'ASC'
                    ));
                }

                if($posts):
                    foreach($posts as $post):?>
                    <article class="realisations__listeItem realisation">
                        <div class="realisation__fondCouleur" style="background-color:<?php echo get_field('couleur_secondaire');?>">
                            <a href="<?php the_permalink()?>" tabindex="-1">
                                <picture class="realisation__image">
                                    <source media="(min-width:841px)" srcset="<?php echo get_field('images_realisation')['image_realisation_1']['sizes']['gallerie-realisations'];?> 1x, <?php echo get_field('images_realisation')['image_realisation_1']['sizes']['gallerie-realisations-2X'];?> 2x"/>
                                    <source media="(max-width:840px)" srcset="<?php echo get_field('images_realisation')['image_realisation_1']['sizes']['gallerie-realisations-mobile'];?> 1x, <?php echo get_field('images_realisation')['image_realisation_1']['sizes']['gallerie-realisations-mobile-2X'];?> 2x"/>
                                    <img src="<?php echo get_field('images_realisation')['image_realisation_1']['sizes']['gallerie-realisations'];?>" alt="Image de la réalisation <?php the_field('titre')?>"/>
                                </picture>
                            </a>
                        </div>
                        <h2 class="h3 realisation__titre"><?php the_field('titre'); ?></h2>
                        <p class="realisation__finissant">par
                            <?php
                                $postsFinissant = get_posts(array(
                                    'post_type' => 'finissants',
                                    'posts_per_page' => 1,
                                    'post_status' => 'published',
                                    'meta_key' => 'id',
                                    'meta_value' => get_field('diplome_id', $post->ID),
                                ));
                                if($postsFinissant){
                                    echo get_field('prenom', $postsFinissant[0]->ID) . " " . get_field('nom', $postsFinissant[0]->ID);
                                }
                            ?>
                        </p>
                        <div class="realisation__etiquettes etiquettes">
                            <ul class="etiquettes__liste">
                            <?php
                                $arrEtiquettes = get_field('etiquettes');
                                $arrAAfficher = array();

                                array_push($arrAAfficher, $arrEtiquettes['etiquettes__disciplines'][0]['label']);

                                if (get_field('session')!=null) {
                                    $texteSession = "Session " . get_field('session');
                                    array_push($arrAAfficher, $texteSession);
                                }

                                if (count($arrEtiquettes['etiquettes__motscles'])!=0) {
                                    array_push($arrAAfficher, $arrEtiquettes['etiquettes__motscles'][0]['label']);
                                }
                                elseif($arrEtiquettes['etiquettes__disciplines'][1]!=null){
                                    array_push($arrAAfficher, $arrEtiquettes['etiquettes__disciplines'][1]['label']);
                                }
                                foreach($arrAAfficher as $etiquette):?>
                                    <li class="etiquettes__listeItem"><?php echo $etiquette; ?></li>
                            <?php
                                endforeach; ?>
                            </ul>
                        </div>
                        <p class="realisation__lien conteneur__lien">
                            <?php $hexToRgb = hex2rgb(get_field('couleur_principale'));?>
                            <a class="lien__realisation lien"
                               href="<?php the_permalink()?>"
                               style="border-bottom-color: rgba(<?php echo $hexToRgb[0] . ',' . $hexToRgb[1] . ',' . $hexToRgb[2];?>,0.8)"> En voir plus
                            </a>
                        </p>
                        <p class="realisation__lien conteneur__lien">
                            <a class="lien__realisation lien"
                               href="<?php the_permalink()?>"
                               style="border-bottom-color: <?php echo get_field('couleur_principale');?>">
                                En voir plus
                            </a>
                        </p>
                    </article>
                    <?php endforeach;
                else: ?>
                    <div class="realisations__aucunResultat aucunResultat">
                        <h2>Désolé...</h2>
                        <span class="aucunResultat__texte">Il n'y a aucun résultat selon les filtres que vous avez sélectionnés.</span>
                    </div>
                <?php
                endif; ?>
        </section>
    </div>
</main>

<?php get_footer(); ?>