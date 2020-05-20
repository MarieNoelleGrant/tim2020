/**
 * @author Marie-Noëlle Grant <m.noelle.grant@gmail.com>
 *
 * RÉGLES SUPPLÉMENTAIRES POUR LES VISIONNEUSES ---------------------------------------------------------------------------------------------
 */


export class Visionneuses {

    private btnPrecedent;
    private btnSuivant;
    private refVisionneuse;
    private refSVGDeco:JQuery<HTMLElement>;
    private positionActuelle: number;
    private changerImage_lier:any;

    public constructor() {
        this.changerImage_lier = this.changerImage.bind(this);
        this.btnPrecedent = $('.glide__btnPrecedent');
        this.btnSuivant = $('.glide__btnSuivant');
        this.refVisionneuse = $('.glide__slides');
        this.positionActuelle = 1;
        this.refSVGDeco = $('.programme__svg');

        this.initialiser();
    }

    // Méthode d'initialisation
    // ***********************************************************************************
    private initialiser() {
        this.btnPrecedent.on('click', this.changerImage_lier);
        this.btnSuivant.on('click', this.changerImage_lier);
    }

    /**
     * Méthode pour modifier la charte selon la discipline sélectionnée.
     * Modifie les classes présentes sur le svg.
     * Modifie aussi le nom des boutons.
     * @param evenement - Référence du bouton cliqué (précédent ou suivant)
     */
    private changerImage(evenement) {
        let leMasqueActuel = document.querySelector('.visionneuse__masque--' + this.positionActuelle);
        let laCouleurActuelle = document.querySelector('.visionneuse__couleur--' + this.positionActuelle);
        let arrValeursRotation = [0, 108, 162, 216, 252];
        let arrValeursRotationInverse = [360, 252, 144, 90, 36];
        let arrDisciplines = ["Prog", "Intégration", "Design", "Conception", "Médias"];
        let txtBtnSuivant = "";
        let txtBtnPrecedent = "";

        leMasqueActuel.setAttribute('opacity', '0');
        laCouleurActuelle.setAttribute('opacity', '0');

        // Pour adapter la rotation si jamais l'écran est dans la largeur mobile/tablette verticale
        if ($(window).width()<=840) {
            for(let intCpt=0; intCpt<5; intCpt++) {
                arrValeursRotation[intCpt] = arrValeursRotation[intCpt] - 90;
                arrValeursRotationInverse[intCpt] = arrValeursRotationInverse[intCpt] + 90;
            }
        }

        // On vérifie si le clic a été fait sur le bouton précédent ou suivant
        // On change la position actuelle selon
        if ($(evenement.currentTarget).hasClass('glide__btnPrecedent')) {
            this.positionActuelle--;
            // Pour remettre la position actuelle à la fin si visionneuse terminée
            if (this.positionActuelle == 0) {
                this.positionActuelle = 5;
            }

            // Pour faire tourner le svg décoratif selon la discipline en cours
            this.refSVGDeco.attr('style','transition: transform ease-in-out 0.3s; transform: rotate('+arrValeursRotationInverse[this.positionActuelle-1]+'deg)');
        }
        else {
            this.positionActuelle++;
            if (this.positionActuelle == 6) {
                this.positionActuelle = 1;
            }
            this.refSVGDeco.attr('style','transition: transform ease-in-out 0.3s; transform: rotate(-'+arrValeursRotation[this.positionActuelle-1]+'deg)');
        }


        // On change le libelle des boutons selon la discipline en cours
        if (this.positionActuelle==1){
            txtBtnPrecedent = arrDisciplines[4];
            txtBtnSuivant = arrDisciplines[this.positionActuelle];
        }
        else if (this.positionActuelle==5){
            txtBtnPrecedent = arrDisciplines[this.positionActuelle-2];
            txtBtnSuivant = arrDisciplines[0];
        }
        else {
            txtBtnPrecedent = arrDisciplines[this.positionActuelle-2];
            txtBtnSuivant = arrDisciplines[this.positionActuelle];
        }

        this.btnPrecedent.text(txtBtnPrecedent);
        this.btnSuivant.text(txtBtnSuivant);

        // On change l'état visible de la portion du svg correspondant à la discipline
        $('.visionneuse__masque--' + this.positionActuelle).attr('opacity', '1');
        $('.visionneuse__couleur--' + this.positionActuelle).attr('opacity', '0.8');

    }
}