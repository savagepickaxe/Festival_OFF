var menu = {
  lblMenuFerme: '☰',
  lblMenuOuvert: '✖',
  refBouton: null,
  refLibelle: null,
  refMenu: null,

  configurerNav: function () {
    // Ajout de la classe 'js' au body pour indiquer que JavaScript est activé
    document.body.classList.add('js');

    // Sélection du menu et de son parent dans le HTML
    this.refMenu = document.querySelector(".menu");
    const menuParent = this.refMenu.parentElement; // Par exemple, le header

    // Vérifier si le bouton existe déjà pour éviter de le dupliquer
    if (document.querySelector('.menu__controle')) {
      return; // Si le bouton existe déjà, arrêter la fonction
    }

    // Création du bouton et du libellé
    this.refBouton = document.createElement("button");
    this.refLibelle = document.createElement("span");

    // Ajout du libellé dans le bouton
    this.refBouton.appendChild(this.refLibelle);

    // Ajout des classes au bouton et au libellé
    this.refBouton.className = 'menu__controle';
    this.refLibelle.className = 'menu__libelle';

    // Association du texte du libellé
    this.refLibelle.innerHTML = this.lblMenuFerme;

    // Ajout du bouton dans le parent du menu (et non dans le menu lui-même)
    menuParent.prepend(this.refBouton);

    // Ajout de l'écouteur d'événement sur le bouton du menu
    this.refBouton.addEventListener('click', () => {
      this.ouvrirFermerNav();
    });
  },

  ouvrirFermerNav: function () {
    // Bascule de la classe de fermeture du menu
    this.refMenu.classList.toggle("menu--ferme");

    // Changement du libellé du bouton selon l'état du menu
    if (this.refMenu.classList.contains("menu--ferme")) {
      this.refLibelle.innerHTML = this.lblMenuFerme;
    } else {
      this.refLibelle.innerHTML = this.lblMenuOuvert;
    }
  }
};

// Écouteur d'événement pour initialiser le menu au chargement de la page
window.addEventListener('load', function () {
  menu.configurerNav();
});

// GSAP
document.addEventListener("DOMContentLoaded", (event) => {
  gsap.registerPlugin(ScrollTrigger);
});
