/**
 * @file ficheArtiste.js
 * @brief Ce fichier permet de gérer le carrousel d'images de l'artiste
 * @details Ce fichier permet de gérer le carrousel d'images de l'artiste, en fonction des boutons précédent et suivant
 * @version 1.0
 * @date 2024-11-04
 * @auteur Daoud Coulibaly 2040480@csfoy.ca
 * @see ficheArtiste.html
 */

let strNiveau = "../../";

// Récupérer la partie query string de l'URL actuelle
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const intIdArtiste = urlParams.get("id_artiste");

//Références
//-- Boutons
let refBtnPrecedent = document.getElementById("btn-precedent");
let refBtnsuivant = document.getElementById("btn-suivant");

//-- Images
let refImageBtnPrecedent = document.querySelector(".bouton__precedent__image");
let refImageBtnSuivant = document.querySelector(".bouton__suivant__image");
let refImage = document.querySelector(".infoArtiste__image");
let refImageTable = document.querySelector(".infoArtiste__image--table");
let refImageMobile = document.querySelector(".infoArtiste__image--mobile");
let intImageactuelle = 0;
let intImageMax = 5;

//Événements
//-- Click boutons carroussel
refBtnPrecedent.addEventListener("click", carroussel);
refBtnsuivant.addEventListener("click", carroussel);

//-- Mouseover boutons carroussel
refBtnPrecedent.addEventListener("mouseover", apparenceBtnSurvol);
refBtnsuivant.addEventListener("mouseover", apparenceBtnSurvol);
refBtnPrecedent.addEventListener("mouseout", apparenceBtnStandard);
refBtnsuivant.addEventListener("mouseout", apparenceBtnStandard);

/**
 * Fonction qui permet de changer l'image de l'artiste en fonction du bouton cliqué
 */
function carroussel() {
    switch (this.id) {
        case "btn-suivant":
            intImageactuelle++;
            break;
        case "btn-precedent":
            intImageactuelle--;
            break;
    }

    if (intImageactuelle < 0) {
        intImageactuelle = intImageMax - 1;
    }
    if (intImageactuelle > intImageMax - 1) {
        intImageactuelle = 0;
    }

    refImageMobile.srcset = strNiveau + "liaisons/images/artistes/carre/" + intIdArtiste + "_" + intImageactuelle + "__w320_carre.jpg 1x, " + strNiveau + "liaisons/images/artistes/carre/" + intIdArtiste + "_" + intImageactuelle + "__w640_carre.jpg 2x";
    refImageTable.srcset = strNiveau + "liaisons/images/artistes/rect/" + intIdArtiste + "_" + intImageactuelle + "__w830_rect.jpg 1x, " + strNiveau + "liaisons/images/artistes/rect/" + intIdArtiste + "_" + intImageactuelle + "__w1660_rect.jpg 2x";
    refImage.src = strNiveau + "liaisons/images/artistes/carre/" + intIdArtiste + "_" + intImageactuelle + "__w320_carre.jpg";
}

/**
 * Fonction qui permet de changer l'apparence des boutons au survol de la souris
 */
function apparenceBtnSurvol() {
    switch (this.id) {
        case "btn-suivant":
            refImageBtnSuivant.src = strNiveau + "liaisons/images/fiche_artiste/button_carroussel-suivant_hover.svg";
            break;
        case "btn-precedent":
            refImageBtnPrecedent.src = strNiveau + "liaisons/images/fiche_artiste/button_carroussel-precedent_hover.svg";
            break;
    }
}

/**
 * Fonction qui permet de changer l'apparence des boutons à leur état standard
 */
function apparenceBtnStandard() {
    switch (this.id) {
        case "btn-suivant":
            refImageBtnSuivant.src = strNiveau + "liaisons/images/fiche_artiste/button_carroussel-suivant.svg";
            break;
        case "btn-precedent":
            refImageBtnPrecedent.src = strNiveau + "liaisons/images/fiche_artiste/button_carroussel-precedent.svg";
            break;
    }
}