/**
 * @author Marie-Noëlle Grant <m.noelle.grant@gmail.com>
 */

export class Filtres {
    private refFiltres;
    private refBtn;
    private refForm;
    private refElementsFormulaire;
    private refGroupeBtnOnglets;
    private refBtnOnglets;
    private refBtnForm;
    private refChampsCibles;

    private ouvrirFermerFiltres_lier:any;
    private montrerCacherChamps_lier:any;

    public constructor() {
        this.refFiltres = $('.filtres');
        this.refBtn = $('.filtres__btnControle');
        this.refForm = $('.filtres__formulaire');
        this.refElementsFormulaire = $('.filtres__elementsFormulaires');
        this.refGroupeBtnOnglets = $('.filtres__liste');
        this.refBtnOnglets = $('.filtres__listeItem');
        this.refBtnForm = $('.form__boutons');

        this.ouvrirFermerFiltres_lier = this.ouvrirFermerFiltres.bind(this);
        this.montrerCacherChamps_lier = this.montrerCacherChamps.bind(this);
        this.initialiser();
    }

    // Méthode d'initialisation
    // ***********************************************************************************
    private initialiser():void {
        // Ajoût de l'icone dans le bouton des filtres
        this.refBtn.append('<div class="filtres__icone"><span class="icone icone__chevron--blanc"></span><span class="libelle screen-reader-only">Ouvrir</span></div>');
        this.refForm.addClass('filtres__formulaire--ferme');

        // Retrait de la possibilité d'accès au tab sur les éléments cachés dans les filtres
        this.modifierTabIndex(0, false);

        // On enlève la classe displayNone des onglets pour les filtres
        $('.filtres__liste').removeClass('displayNone');

        // On met la classe cache sur tous les contenus du form pour l'instant
        this.refElementsFormulaire.find('.filtre').addClass('filtre--cache');

        // Ajout écouteur d'événement sur le bouton principal des filtres
        this.refBtn.on('keypress click', this.ouvrirFermerFiltres_lier);

        // Ajout écouteur d'événement sur les onglets pour afficher les champs des filtres
        this.refBtnOnglets.on('click', this.montrerCacherChamps_lier);
    }


    /**
     * Toggle l'état ouvert ou fermé de la fenêtre principale des filtres
     */
    private ouvrirFermerFiltres(evenement):void {
        let refIconeChevron = this.refBtn.find('.icone__chevron--blanc');
        let refLibelleIcone = this.refBtn.find('.libelle');

        // On change les classes pour l'animation d'ouverture/fermeture des filtres
        this.refForm.toggleClass('filtres__formulaire--ferme');
        refIconeChevron.toggleClass('icone__chevron--haut');
        if (refIconeChevron.hasClass('icone__chevron--haut')) {
            refLibelleIcone.text('Fermer');
            this.modifierTabIndex(1, false);
        }
        else {
            refLibelleIcone.text('Ouvrir');
            this.modifierTabIndex(1, true);
            $(evenement.currentTarget).blur();

        }
    }

    /**
     * Toggle l'état caché ou montré des éléments de formulaires associés aux différents filtres
     * @param evenement - reference de l'onglet qui a été cliqué
     */
    private montrerCacherChamps(evenement):void {
        let refOngletClique = $(evenement.currentTarget);
        let refChampsCibles = null;
        let intFiltre = 0;

        switch(true) {
            case refOngletClique.hasClass('diplomes') :
                this.refChampsCibles = refChampsCibles = $('.diplomes__champs');
                intFiltre = 1;
                break;

            case refOngletClique.hasClass('sessions') :
                this.refChampsCibles = refChampsCibles = $('.sessions__champs');
                intFiltre = 2;
                break;

            case refOngletClique.hasClass('disciplines') :
                this.refChampsCibles = refChampsCibles = $('.disciplines__champs');
                intFiltre = 3;
                break;

            case refOngletClique.hasClass('motscles') :
                this.refChampsCibles = refChampsCibles = $('.motscles__champs');
                intFiltre = 4;
                break;

            case refOngletClique.hasClass('projets') :
                this.refChampsCibles = refChampsCibles = $('.projets__champs');
                intFiltre = 5;
                break;
        }

        // 1. Ajustement de la visibilité de la section selon l'état
        if (this.refGroupeBtnOnglets.hasClass('filtres__liste--'+intFiltre)) {
            this.refGroupeBtnOnglets.removeClass('filtres__liste--'+intFiltre);
            refOngletClique.attr('aria-expanded', 'false');
            this.modifierTabIndex(2, true);

        }
        else {
            this.refGroupeBtnOnglets.removeClass().addClass('filtres__liste filtres__liste--'+intFiltre);
            // On ajoute le focus dans le premier input de la section (avec délai, sinon crois que le input est caché et ne s'applique pas)
            setTimeout(function(this){
                refChampsCibles.children('.form__conteneur').first().children('input').focus();
            }, 350);
            refOngletClique.attr('aria-expanded', 'true');
            this.modifierTabIndex(2, false);
        }

        this.refChampsCibles.toggleClass('filtre--cache');
        this.refChampsCibles.siblings('.filtre').addClass('filtre--cache');

        refOngletClique.find('.icone__chevron').toggleClass('icone__chevron--haut');
        refOngletClique.siblings().find('.icone__chevron').removeClass('icone__chevron--haut');

        refOngletClique.find('.onglet__texte').toggleClass('onglet__texte--actif');
        refOngletClique.siblings().find('.onglet__texte').removeClass('onglet__texte--actif');
    }


    // FONCTIONS UTILITAIRES
    /**
     * Modifie l'attribut tabindex de certains éléments selon l'argument reçu
     * @param intNiveauFiltre : 0 = initialisation, on cache tout. 1 = premier niveau filtre, on rend les onglets dispo. 2 = deuxième niveau, on rend les options dispo.
     * @param blnCacher : 0 (false) = ajouter les attributs. 1 (true) = enlever les attributs.
     */
    private modifierTabIndex(intNiveauFiltre, blnCacher):void {
        if (intNiveauFiltre==0) {
            this.refForm.attr('tabindex', '-1');
            this.refGroupeBtnOnglets.children().children('button').attr('tabindex', '-1');
            this.refElementsFormulaire.attr('tabindex', "-1");
            this.refElementsFormulaire.children().children('button').attr('tabindex', '-1');
            this.refBtnForm.children().children('button, a').attr('tabindex', '-1');
            $('.form__conteneur').find('input').attr('tabindex', '-1');
        }
        else {
            if (intNiveauFiltre==1) {
                if (blnCacher==false) {
                    this.refForm.removeAttr('tabindex');
                    this.refGroupeBtnOnglets.children().children('button').removeAttr('tabindex');
                    this.refBtnForm.children().children('button, a').removeAttr('tabindex');
                    this.refElementsFormulaire.removeAttr('tabindex');
                    this.refElementsFormulaire.children().children('button').removeAttr('tabindex');
                }
                else {
                    this.refForm.attr('tabindex', '-1');
                    this.refGroupeBtnOnglets.children().children('button').attr('tabindex', '-1');
                    this.refBtnForm.children().children('button, a').attr('tabindex', '-1');
                    this.refElementsFormulaire.attr('tabindex', "-1");
                    this.refElementsFormulaire.children().children('button').attr('tabindex', '-1');
                }
            }
            else {
                if (blnCacher==false) {
                    // On rend non-tabable tout le monde au départ
                    $('.form__conteneur').find('input').attr('tabindex', '-1');
                    // Puis donne accès au tab à la section voulue
                    this.refChampsCibles.children('.form__conteneur').find('input').removeAttr('tabindex');
                }
                else {
                    $('.form__conteneur').find('input').attr('tabindex', '-1');
                }
            }
        }
    }
}