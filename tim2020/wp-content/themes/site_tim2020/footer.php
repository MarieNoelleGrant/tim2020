    </div>
    <footer id="pied-de-page" class="footer units-row <?php if (get_the_title()!='Programme' || is_home() || is_front_page()) { echo 'footer__court'; }?>" role="contentinfo">
        <?php if (get_the_title()=='Programme') :?>
            <div class="footer__feedSociaux feed conteneur">
                <p class="h2 h2--blanc">Suivez la TIM</p>
                <div class="feed__facebook">
                    <p class="feed__entete">
                        <span class="feed__entete_txt h3 h3--blanc">TIM @ facebook</span>
<!--                        <span class="icone icone__facebook--blanc"></span>-->
                    </p>
                    <?php echo do_shortcode("[custom-facebook-feed]");?>
                </div>
                <div class="feed__twitter">
                    <p class="feed__entete">
                        <span class="feed__entete_txt h3 h3--blanc">TIM @ twitter</span>
<!--                        <span class="icone icone__twitter"></span>-->
                    </p>
                    <?php echo do_shortcode("[custom-twitter-feed buttontext=\"En voir plus...\"]");?>
                </div>
            </div>
        <?php endif; ?>
        <div class="footer__conteneur">
            <div class="conteneur">
                <div class="footer__flex">
                    <div class="footer__logoSociaux">
                        <a href="<?php echo get_bloginfo('url');?>" class="footer__lienTIM">
                            <img src="<?php echo get_template_directory_uri();?>/images/logo_TIM--blanc.svg" alt="<?php bloginfo('name');?>" class="footer__logoTIM"/>
                        </a>
                        <div class="footer__sociaux sociaux">
                            <span class="screen-reader-only">Suivez la TIM sur ...</span>
                            <?php
                            $postsSociaux = get_posts(array(
                                'post_type' => 'sociauxTIM',
                                'posts_per_page' => -1,
                                'post_status' => 'publish',
                                'order' => 'ASC'
                            ));

                            if ($postsSociaux):
                                foreach($postsSociaux as $post):
                                    if(get_field('nom_sociaux')=="Twitter"):?>
                                    <div class="sociaux__twitter twitter conteneur__bouton">
                                        <a href="<?php the_field('url_sociaux');?>" class="bouton bouton__primaire bouton--blanc">
                                            <span class="screen-reader-only">Twitter</span>
                                            <span class="twitter__icone icone icone__twitter--fonce"></span>
                                            <span class="twitter__texte"><?php the_field('pseudo_sociaux'); ?></span>
                                        </a>
                                    </div>
                            <?php elseif(get_field('nom_sociaux')=="Facebook"):?>
                                    <div class="sociaux__facebook facebook conteneur__bouton">
                                        <a href="<?php the_field('url_sociaux');?>" class="bouton bouton__primaire bouton--blanc">
                                            <span class="screen-reader-only">Facebook</span>
                                            <span class="facebook__icone icone icone__facebook"></span>
                                            <span class="facebook__texte"><?php the_field('pseudo_social'); ?></span>
                                        </a>
                                    </div>
                            <?php
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                    <div class="footer__lienCegep">
                        <a href="https://www.cegep-ste-foy.qc.ca/accueil/?no_cache=1" class="">
                            <img src="<?php echo get_template_directory_uri();?>/images/logo_cegep.svg" alt="Visitez le site du Cégep de Sainte-Foy" class="footer__logoCegep"/>
                        </a>
                    </div>

                </div>
                <p class="footer__credits">
                    <small><span class="unit-100"> <?php bloginfo('name'); ?> - &copy; Tous droits réservés</span></small>
                </p>
            </div>
        </div>
    </footer>

    <!-- *** JQuery *** -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri();?>/node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>

    <!-- *** Glide.js *** -->
    <script src="<?php echo get_template_directory_uri();?>/node_modules/@glidejs/glide/dist/glide.min.js"></script>
    <script>
        if($('main').hasClass('ficheRealisation')) {
            new Glide('.glide.glideTable', {
                rewindDuration: 600
            }).mount();
            new Glide('.glide.glideMobile', {
                rewindDuration: 600
            }).mount();
        }
        else if($('main').hasClass('programme')) {
            new Glide('.glide', {

            }).mount();
        }
    </script>

    <!-- *** Fancybox *** -->
<!--    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>-->
    <link media="screen" rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script async defer src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <!-- *** Typescript/js *** -->
    <script async defer src="<?php echo get_template_directory_uri();?>/node_modules/requirejs/require.js" data-main="<?php echo get_template_directory_uri();?>/js/app.js"></script>
    <script>
        $('body').addClass('js');
    </script>

    <?php wp_footer(); ?>
</body>
</html>