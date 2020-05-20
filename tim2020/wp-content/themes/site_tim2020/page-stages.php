<?php
/*Template name: Stages*/
get_header();
?>

<?php
    /***************************************************************************
     * VALIDATION CÔTÉ SERVEUR
     ***************************************************************************/
    $nomComplet = null;
    $courriel = null;
    $nomEntreprise = null;
    $sujet = null;
    $message = null;
    $captcha = null;
    $ajoutBanqueStages = null;

    $arrMsgCaptcha = null;

    if (isset($_POST['nomComplet'])){
        $nomComplet = $_POST['nomComplet'];
    }

    if (isset($_POST['courriel'])){
        $courriel = $_POST['courriel'];
    }

    if (isset($_POST['nomEntreprise'])) {
        $nomEntreprise = $_POST['nomEntreprise'];
    }

    if (isset($_POST['message'])){
        $message = $_POST['message'];
    }

    if (isset($_POST["g-recaptcha-response"])) {
        $captcha=$_POST["g-recaptcha-response"];
    }

    if (isset($_POST["banqueFutursStages"])) {
        $ajoutBanqueStages = $_POST["banqueFutursStages"];
    }

    // Sortir le fichier json pour qu'il soit lisible
    $contenuBruteFichierJson = file_get_contents("wp-content/themes/site_tim2020/js/messages-erreur.json");
    $tMessagesJSON = json_decode($contenuBruteFichierJson, true); // Convertir en tableau associatif

    // Validation des champs
    $arrValidationNomComplet = validerFormulaire("nomComplet", $nomComplet,"#^[A-Za-zÀ-ÿ '-]+$#", $tMessagesJSON);
    $arrValidationCourriel = validerFormulaire("courriel", $courriel,"#^[A-z0-9][A-z0-9\-\.]+@[A-z0-9\-]+([.][A-z]{2,4})+$#", $tMessagesJSON);
    $arrValidationAjoutListeStages = validerFormulaire("banqueFutursStages", $ajoutBanqueStages, "#^on$#", $tMessagesJSON);
    $arrValidationNomEntreprise = validerFormulaire("nomEntreprise", $nomEntreprise,"#^[A-zÀ-ÿ0-9 \.'-]+$#", $tMessagesJSON);
    $arrValidationMessage = validerFormulaire("message", $message,"#^[^\<\>]*$#", $tMessagesJSON);

    // ((( INFORMATIONS DU RESPONSABLE - Courriel pour l'envoi )))
    $infosAccrocheStages = get_field('stages__responsable');
    $objAccroche = get_field('questions__responsable', $infosAccrocheStages->ID);
    $courrielResponsable = get_field('courriel', $objAccroche->ID);

    // Vérifier le captcha
    if ($captcha) {
        // S'il n'y a pas d'erreurs
        if ($arrValidationNomComplet['estValide']||$arrValidationCourriel['estValide']||$arrValidationNomEntreprise['estValide']||$arrValidationMessage['estValide']||$arrValidationAjoutListeStages['estValide']) {
            // 1. On va chercher le courriel du responsable via les champs du custom post associé

            echo 'Le courriel du responsable des stages est : '. $courrielResponsable;

            $secretKey = "6Ld2xZAUAAAAAKTP2SAEIyikTTN2uzxmgcNRaiLv";
            $ip = $_SERVER['REMOTE_ADDR'];
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
            $responseKeys = json_decode($response, true);

            if(intval($responseKeys["success"])===1) {
                // Envoyer le courriel avec paramètres php mailer
                $to = get_option('admin_email');
                $subject = "Stages - Message d'une entreprise (".get_bloginfo('name').")";
                $headers = array('Content-Type: text/html; charset=UTF-8', 'From: '.$courriel, 'Reply: '.$courriel);
                $messageComplet = "Nom : " . $nomComplet . "<br/>Entreprise : " . $nomEntreprise . "<br/>Message : " . $message;
                if ($ajoutBanqueStages!==null) {
                    $messageComplet = $messageComplet . "<br/><b>Souhaite être ajouté à la liste des milieux de stages!</b>";
                }
                $envoi = wp_mail($to, $subject, $messageComplet, $headers);

                if($envoi) {
                    // Si envoyé
                    $arrMsgCaptcha = ["message"=>$tMessagesJSON['retroactions']['courriel']['envoyer'], "estValide"=>true, "emailEnvoye"=>true];
                }
                else {
                    // Sinon, message d'erreur d'envoi du message
                    $arrMsgCaptcha = ["message"=>$tMessagesJSON['retroactions']['courriel']['avorte'], "estValide"=>true, "emailEnvoye"=>false];
                }

            }
            // Message si erreur dans le motif du captcha
            else {
                $arrMsgCaptcha = ["message"=>$tMessagesJSON['humain']['erreurs']['motif'], "estValide"=>false, "emailEnvoye"=>false];
            }
        }
    }
    // Message si non coché
    else {
        $arrMsgCaptcha = ["message"=>$tMessagesJSON['humain']['erreurs']['vide'], "estValide"=>false, "emailEnvoye"=>false];
    }
?>
<main class="stages">
    <section class="conteneur stages__textesInfo">
        <div class="stages__banner">
            <h1 class="stages__titre"><?php the_title(); ?></h1>
            <p class="stages__texte">
                <?php the_field('stages__intro'); ?>
            </p>
        </div>
        <div class="stages__etudiants">
            <div class="stages__3e troisieme">
                <?php $infosStages3e = get_field('stages__3');?>
                <h2 class="troisieme__titre"><?php echo $infosStages3e['stages__3_nom'];?></h2>
                <div class="troisieme__texte"><?php echo $infosStages3e['stages__3_infos'];?></div>
                <div class="troisieme__france"><?php echo $infosStages3e['stages__3_france'];?></div>
            </div>
            <div class="stages__1e2e premiereDeuxieme">
                <?php $infosStages1e2e = get_field('stages__12');?>
                <h2 class="premiereDeuxieme__titre h3"><?php echo $infosStages1e2e['stages__12_nom'];?></h2>
                <div class="premiereDeuxieme__texte"><?php echo $infosStages1e2e['stages__12_infos'];?></div>
            </div>
        </div>
        <div class="stages__accroche accroche">
            <?php // J'utilise ici les variables $infosAccrocheStages et $objAccroche défini plus haut dans le code (où la requête pour l'envoi de courriel!)?>
            <h3 class="accroche__titre h3--deco"><?php echo get_field('questions__titre', $infosAccrocheStages->ID);?></h3>
            <?php
                $texteComplet = get_field('questions__texte', $infosAccrocheStages->ID);
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

    <section class="stages__employeurs employeurs">
        <div class="conteneur">
            <h2 class="h2--blanc employeurs__titre"><?php the_field('employeurs__titre'); ?></h2>
            <div class="employeurs__contenu">
                <aside class="employeurs__informations">
                    <div class="employeurs__dates infoStages">
                        <?php setlocale(LC_TIME, "fr_CA");?>
                        <ul class="infoStage__1e2e">
                            <?php $infosStages1e2eEmp = get_field('employeurs__infosStage12'); ?>
                            <?php
                                // Pour la conversion en timestamp
                                $dateAvecTraitsUnions = str_replace('/', '-', $infosStages1e2eEmp['infosStage12__dateDebut']);
                            ?>
                            <li class="infoStage__1e2e_dateDebut"><?php echo strftime('%d %B %Y', strtotime($dateAvecTraitsUnions)); ?></li>
                            <li class="infoStage__1e2e_definition"><?php echo $infosStages1e2eEmp['infosStage12__titre']; ?></li>
                            <li class="infoStage__1e2e_pointsForts">
                                <?php echo $infosStages1e2eEmp['infosStage12__important']; ?>
                            </li>
                        </ul>
                        <ul class="infoStage__3">
                            <?php $infosStages3eEmp = get_field('employeurs__infosStage3'); ?>
                            <?php
                            // Pour la conversion en timestamp
                            $dateAvecTraitsUnions = str_replace('/', '-', $infosStages3eEmp['infosStage3__dateDebut']);
                            ?>
                            <li class="infoStage__3_dateDebut"><?php echo strftime('%d %B %Y', strtotime($dateAvecTraitsUnions)); ?></li>
                            <li class="infoStage__3_definition"><?php echo $infosStages3eEmp['infosStage3__titre']; ?></li>
                            <li class="infoStage__3_pointsForts">
                                <?php echo $infosStages3eEmp['infosStage3__important']; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="employeurs__infosSup">
                        <p>Les stages rémunérés sont admissibles à des <strong>crédits d'impôt avantageux!</strong></p>
                        <span class="conteneur__lien">Pour déterminer le stage à offrir, lisez le <a class="lien" data-fancybox href="<?php echo get_template_directory_uri();?>/images/profilCompetences.pdf">profil des compétences des étudiants <span class="icone icone__lienExterne--noir"></span></a></span>
                    </div>
                </aside>
                <div class="employeurs__contact">
                    <h3 class="h3 h3--deco employeurs__contact_titre">Des questions? Des Commentaires? Intéressés à recevoir de nos stagiaires?</h3>
                    <p class="employeurs__contact_texte">Remplissez le formulaire de contact suivant, qui sera envoyé directement à la responsable des stages, <span class="emphase"><?php echo get_field('prenom', $objAccroche->ID) . " " . get_field('nom', $objAccroche->ID); ?></span>.</p>
                    <?php
                    if ($arrMsgCaptcha!==null) :
                        if ($arrMsgCaptcha['estValide']) : ?>
                            <p aria-live="assertive" class="msgEnvoiCourriel <?php if ($arrMsgCaptcha['emailEnvoye']) { echo 'msgEnvoiCourriel--ok';} ?>">
                                <?php
                                if ($arrMsgCaptcha['emailEnvoye']) {
                                    echo '<span class="icone icone__crochet icone__crochet--haut"></span>' . $arrMsgCaptcha['message'];
                                }
                                else {
                                    echo '<span class="icone icone__erreur"></span>' . $arrMsgCaptcha['message'];
                                }?>
                            </p>
                        <?php
                        endif;
                    endif;
                    ?>
                    <form class="employeurs__contact_formulaire form" action="" method="post">
                        <!-- ***** FORMULAIRE D'ENVOI POUR LES EMPLOYEURS À AJOUTER! ***** -->
                        <div class="conteneur__form">
                            <label for="nomComplet">Votre nom complet</label>
                            <input type="text"
                                   id="nomComplet"
                                   name="nomComplet"
                                   class="form__text--m"
                                   pattern="[A-ZÄ-Ÿ][A-Za-zÀ-ÿ' \.-]+"
                                <?php if ($nomComplet!==null) {?> value="<?php echo $arrValidationNomComplet['valeur']?>" <?php }?>/>
                            <p aria-live="assertive" class="msgErreur">
                                <?php
                                if ($nomComplet!==null) {
                                    if ($arrValidationNomComplet['estValide'] == false) {
                                        echo '<span class="icone icone__erreur"></span>' . $arrValidationNomComplet['message'];
                                    } else {
                                        echo '<span class="icone icone__crochet"></span>';
                                    }
                                }
                                ?>
                            </p>
                        </div>
                        <div class="conteneur__form">
                            <label for="courriel">Votre adresse courriel</label>
                            <input type="email"
                                   id="courriel"
                                   name="courriel"
                                   class="form__text--m"
                                   pattern="[a-zA-Z0-9_]+(.[a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+(.[a-zA-Z0-9_]+)*.[a-zA-Z]{2,4}"
                                <?php if ($courriel!==null) {?> value="<?php echo $arrValidationCourriel['valeur']?>" <?php }?>/>
                            <p aria-live="assertive" class="msgErreur">
                                <?php
                                if ($courriel!==null) {
                                    if ($arrValidationCourriel['estValide'] == false) {
                                        echo '<span class="icone icone__erreur"></span>' . $arrValidationCourriel['message'];
                                    } else {
                                        echo '<span class="icone icone__crochet"></span>';
                                    }
                                }
                                ?>
                            </p>
                        </div>
                        <div class="conteneur__form">
                            <label for="nomEntreprise">Votre entreprise</label>
                            <input type="text"
                                   id="nomEntreprise"
                                   name="nomEntreprise"
                                   class="form__text--m"
                                   pattern="[A-zÀ-ÿ0-9' \.-]+"
                                <?php if ($nomEntreprise!==null) {?> value="<?php echo $arrValidationNomEntreprise['valeur']?>" <?php }?>/>
                            <p aria-live="assertive" class="msgErreur">
                                <?php
                                if ($nomEntreprise!==null) {
                                    if ($arrValidationNomEntreprise['estValide'] == false) {
                                        echo '<span class="icone icone__erreur"></span>' . $arrValidationNomEntreprise['message'];
                                    } else {
                                        echo '<span class="icone icone__crochet"></span>';
                                    }
                                }
                                ?>
                            </p>
                        </div>
                        <div class="conteneur__form">
                            <label for="message">Votre message</label>
                            <textarea id="message" name="message" class="form__text--xl"><?php if ($message!=null) { echo $arrValidationMessage['valeur']; }?></textarea>
                            <p aria-live="assertive" class="msgErreur">
                                <?php
                                if ($message!==null) {
                                    if ($arrValidationMessage['estValide'] == false) {
                                        echo '<span class="icone icone__erreur"></span>' . $arrValidationMessage['message'];
                                    } else {
                                        echo '<span class="icone icone__crochet"></span>';
                                    }
                                }
                                ?>
                            </p>
                        </div>
                        <div class="conteneur__form conteneur__form--pasFlex">
                            <input type="checkbox" class="form__checkbox" id="banqueFutursStages" name="banqueFutursStages"/>
                            <label for="banqueFutursStages">Nous serions intéressés à être ajouté à la banque des milieux de stages pour l’année <?php echo date('Y');?>.</label>
                        </div>
                        <div class="conteneur__form">
                            <div class="captcha g-recaptcha" data-sitekey="6Ld2xZAUAAAAAJ2AKX2HBkO1X3vSb6vuQ4ireXAK"></div>
                            <p aria-live="assertive" class="msgErreur">
                                <?php
                                if ($captcha!==null && $arrMsgCaptcha!==null) {
                                    if ($arrMsgCaptcha['estValide']==false) {
                                        echo '<span class="icone icone__erreur"></span>' . $arrMsgCaptcha['message'];
                                    }
                                }?>
                            </p>
                        </div>
                        <div class="conteneur__bouton">
                            <button type="submit" class="bouton bouton__primaire bouton__form">Envoyer</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</main>

<script async defer src="https://www.google.com/recaptcha/api.js"></script>

<?php get_footer(); ?>
