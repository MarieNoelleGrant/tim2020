define(["require","exports"],function(e,r){"use strict";Object.defineProperty(r,"__esModule",{value:!0});var t=(i.prototype.initialiserFormContact=function(){this.refNomComplet=document.querySelector("#nomComplet"),this.refCourriel=document.querySelector("#courriel"),this.refDestinataire=document.querySelector("#destinataire"),this.refSujet=document.querySelector("#sujet"),this.refMessage=document.querySelector("#message"),this.refNomComplet.addEventListener("blur",this.validerChampTexte_lier),this.refCourriel.addEventListener("blur",this.validerChampTexte_lier),this.refDestinataire.addEventListener("blur",this.validerSelect_lier),this.refSujet.addEventListener("blur",this.validerChampTexte_lier),this.refMessage.addEventListener("blur",this.validerChampTexte_lier)},i.prototype.initialiserFormStages=function(){this.refNomComplet=document.querySelector("#nomComplet"),this.refEntreprise=document.querySelector("#nomEntreprise"),this.refCourriel=document.querySelector("#courriel"),this.refMessage=document.querySelector("#message"),this.refNomComplet.addEventListener("blur",this.validerChampTexte_lier),this.refCourriel.addEventListener("blur",this.validerChampTexte_lier),this.refEntreprise.addEventListener("blur",this.validerChampTexte_lier),this.refMessage.addEventListener("blur",this.validerChampTexte_lier)},i.prototype.validerChampTexte=function(e){var r=e.currentTarget,t=$(r).siblings(".msgErreur"),i="";1==this.validerSiVide(r)?(i='<span class="icone icone__erreur"></span>'+this.objMessages[r.name].erreurs.vide,$(r).addClass("erreur")):"message"!=r.name&&!1===this.validerPattern(r,"")?(i='<span class="icone icone__erreur"></span>'+this.objMessages[r.name].erreurs.motif,$(r).addClass("erreur")):(i='<span class="icone icone__crochet"></span>',$(r).removeClass("erreur")),t.html(i)},i.prototype.validerSelect=function(e){var r=e.currentTarget,t=$(r).siblings(".msgErreur");"0"==r.value||""==r.value?($(r).addClass("erreur"),t.html('<span class="icone icone__erreur"></span>'+this.objMessages[r.name].erreurs.vide)):($(r).removeClass("erreur"),t.html('<span class="icone icone__crochet"></span>'))},i.prototype.validerSiVide=function(e){var r=!1;return""===e.value&&(r=!0),r},i.prototype.validerPattern=function(e,r){return(""===r?(r=e.pattern,new RegExp("^"+r+"$")):new RegExp(r)).test(e.value)},i);function i(){var r=this;this.refNomComplet=null,this.refCourriel=null,this.refDestinataire=null,this.refSujet=null,this.refMessage=null,this.refEntreprise=null,this.refCaptcha=null,this.validerChampTexte_lier=null,this.validerSelect_lier=null,document.querySelector("form").noValidate=!0,this.validerChampTexte_lier=this.validerChampTexte.bind(this),this.validerSelect_lier=this.validerSelect.bind(this),fetch("../wp-content/themes/site_tim2020/js/messages-erreur.json").then(function(e){return e.json()}).then(function(e){switch(r.objMessages=e,document.querySelector("main").classList[0]){case"contact":r.initialiserFormContact();break;case"stages":r.initialiserFormStages()}}).catch(function(e){console.log(e)})}r.Validations=t});