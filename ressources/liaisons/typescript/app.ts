import {Visionneuses} from './Visionneuses';
import {Menu} from './Menu';
import {Filtres} from './Filtres';
import {Validations} from "./Validations";
import {GestionVideo} from "./GestionVideo";

// Contient les interactions pour le menu mobile + menu fixe
// ---------------------------------------------------------
new Menu();

// Pour la page programme : visionneuses + vidéo d'entête
// ---------------------------------------------------------
if($('main').hasClass('programme')) {
    new Visionneuses();
    new GestionVideo();
}

// Pour la page réalisations : gestion des filtres
// ---------------------------------------------------------
if($('main').hasClass('realisations')) {
    new Filtres();
}

// Pour les pages contact et stages : validation côté client
// ---------------------------------------------------------
if ($('main').hasClass('contact') || $('main').hasClass('stages')) {
    new Validations();
}

// TEST : pour empêcher le scroll horizontal sur les écrans mobiles
// ----------------------------------------------------------------
$('window').ready(
    function() {

        var $body = $(document);
        $body.bind('scroll', function() {
            // "Disable" the horizontal scroll.
            if ($body.scrollLeft() !== 0) {
                $body.scrollLeft(0);
            }
        });
    });

