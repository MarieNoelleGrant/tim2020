/**
 * @author Marie-Noëlle Grant <m.noelle.grant@gmail.com>
 */

export class GestionVideo {

    private refBaliseVideo;
    private verifierHauteurScroll_lier:any;
    private arreterPartirVideo_lier:any;

    public constructor() {
        this.refBaliseVideo = $('.banner__video');
        this.verifierHauteurScroll_lier = this.verifierHauteurScroll.bind(this);
        this.arreterPartirVideo_lier = this.arreterPartirVideo.bind(this);

        this.initialiser();
    }

    // Méthode d'initialisation
    // ***********************************************************************************
    private initialiser():void {
        $(window).on('scroll', this.verifierHauteurScroll_lier);

        this.refBaliseVideo.closest('div').append('<div class="banner__video_controls"><div class="bouton__conteneur"><button type="button" class="bouton bouton__primaire bouton--blanc"><span class="screen-reader-only banner__video_controlsLibelle">Arrêter le video</span><span class="icone icone__video icone__video--pause"></span></button></div></div>');
        this.refBaliseVideo.siblings('.banner__video_controls').on('mousedown', this.arreterPartirVideo_lier);
    }


    /**
     * Vérifie la position du vidéo par rapport à l'écran, appelle une méthode qui arrête ou part le vidéo selon la position du scroll.
     * Fait appel à la méthode stopStartVideo selon l'état.
     */
    private verifierHauteurScroll():void {
        let positionActuelle = $(window).scrollTop();

        if (positionActuelle>=this.refBaliseVideo.height()) {
            this.stopStartVideo('stop');
        }
        else {
            // Pour partir le video seulement si il était déjà en marche à la base
            if ($('.banner__video_controls').find('.icone__video').hasClass('icone__video--pause')) {
                this.stopStartVideo('start');
            }
        }
    }

    /**
     * Méthode activée au clic du bouton, appelle la méthode utilitaire stopStart selon l'état.
     * @param evenement
     */
    private arreterPartirVideo(evenement):void {
        let refIcone = $(evenement.currentTarget).find('.icone');

        if (refIcone.hasClass('icone__video--play')) {
            this.stopStartVideo('start');
            refIcone.siblings('.banner__video_controlsLibelle').text('Arrêter le vidéo');
        }
        else {
            this.stopStartVideo('stop');
            refIcone.siblings('.banner__video_controlsLibelle').text('Faire jouer le vidéo');
        }

        refIcone.toggleClass('icone__video--play').toggleClass('icone__video--pause');
    }

    /**
     * Arrête ou part le vidéo, selon l'argument reçu.
     */
    private stopStartVideo(refAction):void {
        if (refAction=='start') {
            this.refBaliseVideo.get(0).play();
        }
        else {
            this.refBaliseVideo.get(0).pause();
        }
    }
}