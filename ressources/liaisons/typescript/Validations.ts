/**
 * @author Marie-Noëlle Grant <m.noelle.grant@gmail.com>
 *
 * VALIDATIONS JAVASCRIPT ---------------------------------------------------------------------------------------------
 */

export class Validations {

    // ATTRIBUTS
    private objMessages: JSON;
    //private refAjaxDispoCourriel:DispoCourriel;

    // -- Éléments de formulaire à valider
    private refNomComplet: HTMLInputElement = null;
    private refCourriel: HTMLInputElement = null;
    private refDestinataire: HTMLInputElement = null;
    private refSujet: HTMLInputElement = null;
    private refMessage: HTMLInputElement = null;
    private refEntreprise: HTMLInputElement = null;
    private refCaptcha: HTMLInputElement = null;

    // -- Liaisons pour le bind du this
    private validerChampTexte_lier: any = null;
    private validerSelect_lier: any = null;

    // Constructeur
    public constructor() {
        document.querySelector('form').noValidate = true;

        // Liaison des méthodes
        this.validerChampTexte_lier = this.validerChampTexte.bind(this);
        this.validerSelect_lier = this.validerSelect.bind(this);

        // Aller chercher le contenu du json
        fetch("../wp-content/themes/site_tim2020/js/messages-erreur.json")
            .then(response => {
                return response.json();
            })
            .then(response => {
                this.objMessages = response;
                // console.log("objJSON", this.objMessages);
                switch (document.querySelector('main').classList[0]) {
                    case 'contact':
                        this.initialiserFormContact();
                        break;
                    case 'stages':
                        this.initialiserFormStages();
                        break;
                }
            })
            .catch(error => {
                console.log(error);
            });
    }

    // ***********************************************************************************
    // Méthodes d'initialisation
    // ***********************************************************************************

    // Initialisation pour le formulaire de la page Contact
    // -----------------------------------------------------------------------------------
    private initialiserFormContact(): void {
        // 1) Initialisation des variables pour les champs
        this.refNomComplet = document.querySelector('#nomComplet');
        this.refCourriel = document.querySelector('#courriel');
        this.refDestinataire = document.querySelector('#destinataire');
        this.refSujet = document.querySelector('#sujet');
        this.refMessage = document.querySelector('#message');

        // 2) Initialisation des écouteurs d'événement
        this.refNomComplet.addEventListener('blur', this.validerChampTexte_lier);
        this.refCourriel.addEventListener('blur', this.validerChampTexte_lier);
        this.refDestinataire.addEventListener('blur', this.validerSelect_lier);
        this.refSujet.addEventListener('blur', this.validerChampTexte_lier);
        this.refMessage.addEventListener('blur', this.validerChampTexte_lier);
    }


    // Initialisation pour le formulaire de la page Stages
    // -----------------------------------------------------------------------------------
    private initialiserFormStages(): void {
        // 1) Initialisation des variables pour les champs
        this.refNomComplet = document.querySelector('#nomComplet');
        this.refEntreprise = document.querySelector('#nomEntreprise');
        this.refCourriel = document.querySelector('#courriel');
        this.refMessage = document.querySelector('#message');

        // 2) Initialisation des écouteurs d'événement
        this.refNomComplet.addEventListener('blur', this.validerChampTexte_lier);
        this.refCourriel.addEventListener('blur', this.validerChampTexte_lier);
        this.refEntreprise.addEventListener('blur', this.validerChampTexte_lier);
        this.refMessage.addEventListener('blur', this.validerChampTexte_lier);
    }


    // ***********************************************************************************
    // Méthodes de validation
    // ***********************************************************************************

    /**
     * Validations pour les différents champs texte : Nom, Prénom, Adresse, Ville, Code Postal, Courriel, Mot de passe,
     * Numéro de carte de crédit, Numéro de sécurité et le Nom sur la carte de crédit.
     * Fait la vérification en trois étape : si vide, si pattern non respecté, si ok.
     * Change le message d'erreur selon
     * @param evenement
     */
    private validerChampTexte(evenement): void {
        const refChamp = evenement.currentTarget;
        let $msgErreur = $(refChamp).siblings('.msgErreur');
        let texteMsgErreur: string = "";

        if (this.validerSiVide(refChamp) == true) {
            texteMsgErreur = '<span class="icone icone__erreur"></span>' + this.objMessages[refChamp.name]['erreurs']['vide'];
            $(refChamp).addClass('erreur');
        } else {
            if (refChamp.name != 'message') {
                if (this.validerPattern(refChamp, "") === false) {
                    texteMsgErreur = '<span class="icone icone__erreur"></span>' + this.objMessages[refChamp.name]['erreurs']['motif'];
                    $(refChamp).addClass('erreur');
                } else {
                    texteMsgErreur = '<span class="icone icone__crochet"></span>';
                    $(refChamp).removeClass('erreur');
                }
            }
            else {
                texteMsgErreur = '<span class="icone icone__crochet"></span>';
                $(refChamp).removeClass('erreur');
            }
        }

        $msgErreur.html(texteMsgErreur);
    }

    /**
     * Validations pour les différents champs de type select comme : Province.
     * @param evenement
     */
    private validerSelect(evenement): void {
        const refSelect = evenement.currentTarget;
        let $msgErreur = $(refSelect).siblings('.msgErreur');

        if (refSelect.value == '0' || refSelect.value == '') {
            $(refSelect).addClass('erreur');
            $msgErreur.html('<span class="icone icone__erreur"></span>' + this.objMessages[refSelect.name]['erreurs']['vide']);
        } else {
            $(refSelect).removeClass('erreur');
            $msgErreur.html('<span class="icone icone__crochet"></span>');
        }
    }


    // Méthodes utilitaires
    /**
     * Si champ est vide, retourne TRUE
     * @param refChamp
     * @return valeurRetournee
     */
    private validerSiVide(refChamp): boolean {
        let valeurRetournee: boolean = false;
        if (refChamp.value === "") {
            valeurRetournee = true;
        }
        return valeurRetournee;
    }

    /**
     * Vérifie premièrement si l'argument du motif est vide. Si oui, va chercher le motif dans le HTMl et concatene elements de sécurité.
     * Si non, prend le motif fourni en argument.
     * Ensuite, vérifie si le motif est respecté. Si oui, retourne TRUE
     * @param element
     * @param motif
     * @return booléen du régex vérifié
     */
    private validerPattern(element: HTMLInputElement, motif: string): boolean {
        let regexp: RegExp = null;
        if (motif === "") {
            motif = element.pattern;
            regexp = new RegExp("^" + motif + "$");
        } else {
            regexp = new RegExp(motif);
        }
        return regexp.test(element.value);
    }
}