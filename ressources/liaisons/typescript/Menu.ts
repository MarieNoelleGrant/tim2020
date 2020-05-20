/**
 * @author Marie-Noëlle Grant <m.noelle.grant@gmail.com>
 */

export class Menu {

    private refMenu:JQuery<HTMLElement>;
    private btnMenu;
    private refContenuMenu:JQuery<HTMLElement>;
    private refItemsMenu:JQuery<HTMLElement>;
    private refEntete:JQuery<HTMLElement>;
    private lastScrollTop;

    private monterDescendreMenuFixe_lier:any;
    private afficherCacher_lier:any;


    public constructor() {
        this.refMenu = $('.header__nav');
        this.refContenuMenu = $('.menu');
        this.refItemsMenu = $('.menu-item');
        this.refEntete = $('.header__conteneur');
        this.lastScrollTop = 0;

        this.afficherCacher_lier = this.afficherCacher.bind(this);
        this.monterDescendreMenuFixe_lier = this.monterDescendreMenuFixe.bind(this);

        this.initialiser();
    }

    // Méthode d'initialisation
    // ***********************************************************************************
    private initialiser(){
        /********************************************************************
         * MENU MOBILE
         ********************************************************************/
        // 1. Création du libelle et du span pour l'icone du menu
        let iconeMenu = $('<span>').addClass('icone icone__menu');
        let libelleMenu = $('<span>').addClass('screen-reader-only libelle');
        $(libelleMenu).html('Menu');

        // 2. Création du bouton et ajout des composantes
        this.btnMenu = $('<button>');
        this.btnMenu.addClass('menu__btnMenu');
        this.btnMenu.addClass('menu__btnMenu--ferme');
        this.btnMenu.append(libelleMenu);
        this.btnMenu.prepend(iconeMenu);
        this.refMenu.prepend(this.btnMenu);

        // 3. Ajoût de l'état caché du menu (ul)
        this.refContenuMenu.addClass('menu--ferme');
        // On ajoute aussi le tabindex à -1 aux éléments du menu mobile lorsqu'il est fermé
        if ($(window).width() < 841) {
            this.refItemsMenu.children('a').attr('tabindex', '-1');
        }

        // 4. Ajout de l'écouteur d'événement au clic
        this.btnMenu.on('click', this.afficherCacher_lier);

        /********************************************************************
         * MENU FIXE
         ********************************************************************/
        $(window).on('scroll', this.monterDescendreMenuFixe_lier);
    }

    /**
     * Pour afficher ou cacher le menu mobile au clic du bouton
     */
    private afficherCacher() {
        // Toggle des classes selon si menu ouvert ou fermé
        this.refContenuMenu.toggleClass('menu--ferme');
        $('.menu__btnMenu').children('.icone').toggleClass('icone__menu').toggleClass('icone__menu--fermer');
        $('.header__logo').toggleClass('header__logo--zoom');

        // Changement de l'attribut tabindex des items si le menu est ouvert ou fermé
        if (this.refContenuMenu.hasClass('menu--ferme')) {
            this.refItemsMenu.children('a').attr('tabindex', '-1');
        }
        else {
            this.refItemsMenu.children('a').attr('tabindex', '0');
        }
    }

    /**
     * Pour monter ou desendre l'entête complète du menu fixe
     */
    private monterDescendreMenuFixe() {
        // Le this.lastScrollTop > 0 empêche les navigateurs mobiles comme safari qui permettent de scroller plus haut que la page de causer problème
        if (this.lastScrollTop < $(window).scrollTop() && this.lastScrollTop > 0) {
            this.refEntete.parent('.header').addClass('header--cache');
        }
        else {
            this.refEntete.parent('.header').removeClass('header--cache');
        }

        this.lastScrollTop = $(window).scrollTop();
    }
};
