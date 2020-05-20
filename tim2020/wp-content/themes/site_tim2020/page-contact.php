<?php
    /*Template name: Contact*/
    get_header();
?>

<?php
/***************************************************************************
 * VALIDATION CÔTÉ SERVEUR
 ***************************************************************************/

    $nomComplet = null;
    $courriel = null;
    $destinataire = null;
    $sujet = null;
    $message = null;
    $captcha = null;

    $arrMsgCaptcha = null;

    // Enregistrement des éléments dans la query
    if (isset($_POST['nomComplet'])){
        $nomComplet = $_POST['nomComplet'];
    }

    if (isset($_POST['courriel'])){
        $courriel = $_POST['courriel'];
    }

    if (isset($_POST['destinataire'])) {
        $destinataire = $_POST['destinataire'];
    }

    if (isset($_POST['sujet'])){
        $sujet = $_POST['sujet'];
    }

    if (isset($_POST['message'])){
        $message = $_POST['message'];
    }

    if (isset($_POST["g-recaptcha-response"])) {
        $captcha=$_POST["g-recaptcha-response"];
    }

    // Sortir le fichier json pour qu'il soit lisible
    $contenuBruteFichierJson = file_get_contents("wp-content/themes/site_tim2020/js/messages-erreur.json");
    $tMessagesJSON = json_decode($contenuBruteFichierJson, true); // Convertir en tableau associatif

    // Validation des champs
    $arrValidationNomComplet = validerFormulaire("nomComplet", $nomComplet,"#^[A-Za-zÀ-ÿ '-]+$#", $tMessagesJSON);
    $arrValidationCourriel = validerFormulaire("courriel", $courriel,"#^[A-z0-9][A-z0-9\-\.]+@[A-z0-9\-]+([.][A-z]{2,4})+$#", $tMessagesJSON);
    $arrValidationDestinataire = validerFormulaire("destinataire", $destinataire, '#^[1-4]$#', $tMessagesJSON);
    $arrValidationSujet = validerFormulaire("sujet", $sujet,"#^[A-Za-zÀ-ÿ '-]+$#", $tMessagesJSON);
    $arrValidationMessage = validerFormulaire("message", $message,"#^[^\<\>]*$#", $tMessagesJSON);

    // Vérifier le captcha
    if ($captcha) {
        // S'il n'y a pas d'erreurs
        if ($arrValidationNomComplet['estValide']||$arrValidationCourriel['estValide']||$arrValidationDestinataire['estValide']||$arrValidationSujet['estValide']||$arrValidationMessage['estValide']) {
            // 1. On va chercher le courriel du responsable sélectionné dans la base de données
            $postDestinataire = get_posts(array(
                'post_type' => 'responsables',
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'order' => 'ASC',
                'meta_key' => 'id',
                'meta_value' => $destinataire
            ));

            // 2. On vérifie l'authenticité du captcha
            $secretKey = "6Ld2xZAUAAAAAKTP2SAEIyikTTN2uzxmgcNRaiLv";
            $ip = $_SERVER['REMOTE_ADDR'];
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
            $responseKeys = json_decode($response, true);

            if(intval($responseKeys["success"])===1) {
                // 3. On envoie le courriel avec paramètres php mailer
                // $to = get_option('admin_email');
                $to = get_field('courriel', $postDestinataire[0]->ID);
                $subject = "Message d'un utilisateur - Site TIM 2020";
                $headers = array('Content-Type: text/html; charset=UTF-8', 'From: '. $courriel, 'Reply: ' . $courriel);
                $messageEnvoye = '<p><b>'.$sujet.'</b></p><p>'.$message.'</p>';
                $envoi = wp_mail($to, $subject, $messageEnvoye, $headers);

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

<main class="contact">
    <?php
        if (isset($_GET['ID'])){
            $idResponsable = get_field('id', $_GET['ID']);
        }
        else {
            $idResponsable = "";
        }
    ?>
    <div class="conteneur">
        <div class="contact__banner">
            <h1 class="contact__titre"><?php the_title();?></h1>
            <span class="contact__intro"><?php the_field('contact__intro');?></span>
        </div>
        <section class="contact__contenu">
            <?php
            $posts = get_posts(array(
                'post_type' => 'responsables',
                'posts_per_page' => 4,
                'post_status' => 'published',
                'order_by' => 'post_date',
                'order' => 'DESC'
            ));
            ?>
            <aside class="responsables">
                <h2 class="screen-reader-only">Nos responsables : </h2>
                <?php
                foreach ($posts as $post): ?>
                    <div class="responsable">
                        <img class="responsable__image" src="<?php echo get_field('photo')['url']?>" alt="photo de <?php echo get_field('prenom') . " " . get_field('nom')?>" />
                        <div class="responsable__infos">
                            <h3 class="h4 responsable__nom"><?php echo get_field('prenom') . " " . get_field('nom')?></h3>
                            <span class="responsable__tache"><?php echo get_field('responsabilite')?></span>
                            <span class="responsable__tel"><?php echo get_field('telephone')?></span>
                        </div>

                    </div>
                <?php
                endforeach;
                ?>
            </aside>
            <div class="contact__conteneurForm">
                <?php
                if ($arrMsgCaptcha!=null) :
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

                <form class="contact__formulaire form" action="" method="post">
                    <div class="conteneur__form">
                        <label for="nomComplet">Ton nom complet</label>
                        <input type="text"
                               id="nomComplet"
                               name="nomComplet"
                               class="form__text--m"
                               pattern="[A-ZÄ-Ÿ][A-Za-zÀ-ÿ' \.-]+"
                               <?php if ($nomComplet!==null) {?> value="<?php echo $arrValidationNomComplet['valeur']?>" <?php }?>
                        />
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
                        <label for="courriel">Ton adresse courriel</label>
                        <input type="email"
                               id="courriel"
                               name="courriel"
                               class="form__text--m"
                               pattern="[a-zA-Z0-9_]+(.[a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+(.[a-zA-Z0-9_]+)*.[a-zA-Z]{2,4}"
                            <?php if ($courriel!==null) {?> value="<?php echo $arrValidationCourriel['valeur']?>" <?php }?>
                        />
                        <p aria-live="assertive" class="msgErreur">
                            <?php
                            if ($courriel!==null) {
                                if ($arrValidationCourriel['estValide']==false) {
                                    echo '<span class="icone icone__erreur"></span>' . $arrValidationCourriel['message'];
                                }
                                else {
                                    echo '<span class="icone icone__crochet"></span>';
                                }
                            }
                            ?>
                        </p>
                    </div>
                    <div class="conteneur__form conteneur__select">
                        <label for="destinataire">Choisir le destinataire</label>
                        <select id="destinataire" name="destinataire" class="form__select">
                            <option value="0" <?php if($idResponsable==""){ echo 'selected="selected"';} ?>>Choisir un destinataire</option>
                            <?php
                            if($posts):
                                foreach ($posts as $post): ?>
                                    <option value="<?php echo get_field('id');?>" <?php if(get_field('id') == $idResponsable || ($destinataire!==null && $arrValidationDestinataire['valeur']==get_field('id'))){ echo 'selected="selected"';}?>>
                                        <?php echo get_field('prenom') . " " . get_field('nom')?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <p aria-live="assertive" class="msgErreur">
                            <?php
                                if ($destinataire!==null) {
                                    if ($arrValidationDestinataire['estValide']==false) {
                                        echo '<span class="icone icone__erreur"></span>' . $arrValidationDestinataire['message'];
                                    }
                                    else {
                                        echo '<span class="icone icone__crochet"></span>';
                                    }
                                }
                            ?>
                        </p>
                    </div>
                    <div class="conteneur__form">
                        <label for="sujet">Sujet</label>
                        <input type="text"
                               id="sujet"
                               name="sujet"
                               class="form__text--l"
                               pattern="[A-Za-zÀ-ÿ' \.-]+"
                            <?php if ($sujet!==null) {?> value="<?php echo $arrValidationSujet['valeur']?>" <?php }?>
                        />
                        <p aria-live="assertive" class="msgErreur">
                            <?php
                                if ($sujet!==null) {
                                    if ($arrValidationSujet['estValide']==false) {
                                        echo '<span class="icone icone__erreur"></span>' . $arrValidationSujet['message'];
                                    }
                                    else {
                                        echo '<span class="icone icone__crochet"></span>';
                                    }
                                }
                            ?>
                        </p>
                    </div>
                    <div class="conteneur__form">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="form__text--xl"><?php if ($message!==null) { echo $arrValidationMessage['valeur']; }?></textarea>
                        <p aria-live="assertive" class="msgErreur">
                            <?php
                            if ($message!==null) {
                                if ($arrValidationMessage['estValide']==false) {
                                    echo '<span class="icone icone__erreur"></span>' . $arrValidationMessage['message'];
                                }
                                else {
                                    echo '<span class="icone icone__crochet"></span>';
                                }
                            }
                            ?>
                        </p>
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
        </section>
    </div>
</main>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php get_footer(); ?>
