



/*




}



































































/*
var menu = {
  lblMenuFerme: '☰',
  lblMenuOuvert: '✖',
  refBouton: null,
  refLibelle: null,
  refMenu: null,

  configurerNav: function ()
  {
    console.log("allo")
    //********** Création du bouton du menu mobile
    document.body.classList.add('js');
    // On sélectionne le menu dans le HTML
    this.refMenu = document.querySelector(".menu");

    // Création du bouton et du libellé
    this.refBouton = document.createElement("button");
    this.refLibelle = document.createElement("span");

    // On ajoute le libellé dans le bouton
    this.refBouton.appendChild(this.refLibelle);

    // On ajoute les classes au bouton et au libellé
    this.refBouton.className = 'menu__controle noir';
    this.refLibelle.className = 'menu__libelle';

    // On associe le texte du libellé à l'élément html
    this.refLibelle.innerHTML = this.lblMenuFerme;

    // Ajout du bouton dans l'entête de la page html
    this.refMenu.prepend(this.refBouton);

    // Ajout de l'écouteur d'événement sur le bouton du menu
    this.refBouton.addEventListener('click', function () {
      menu.ouvrirFermerNav();
    });
    //this.refBouton.addEventListener('click', this.ouvrirFermerNav.bind(this));
  },

  ouvrirFermerNav: function ()
  {
    // On bascule la classe de fermeture du menu
    
    this.refMenu.classList.toggle("menu--ferme");
 let menuBg = document.querySelector('.menu');
 let boutonMenu = document.querySelector('.menu__controle');

    // On change le libellé du bouton selon l'état du menu
    if (this.refMenu.classList.contains("menu--ferme"))
    {
      this.refLibelle.innerHTML = this.lblMenuFerme;
     

      //La timeline pour enlever header
      const tl = gsap.timeline({})
      menuBg.classList.remove('background-menu');
      menuBg.classList.add('background-menuOff');

      boutonMenu.classList.add('noir');
   
      gsap.to('  .char', {
        opacity:0,
       duration: 0.5,
        stagger: -0.025,
        ease: 'power4.out',
        delay:0.2,
        y: -50,
      })
  
    }
    else
    {
      
      this.refLibelle.innerHTML = this.lblMenuOuvert;



      
      //Faire lever les lettres au click du nav
      gsap.from(' .char', {
        opacity:1,
      
        y: 50,
        stagger: 0.025,
        
        ease: 'power4.out',
        duration: 0.5,
        delay:0.2,
       
      })
      menuBg.classList.add('background-menu');
      menuBg.classList.remove('background-menuOff');
      boutonMenu.classList.remove('noir');
      boutonMenu.classList.add('blanc');
    }

 
  
  }
};


//*******************
// Écouteurs d'événements
//*******************

window.addEventListener('load', function () { menu.configurerNav(); });



//GSAP
 document.addEventListener("DOMContentLoaded", (event) => {
  gsap.registerPlugin(ScrollTrigger)

  
  

  
 });
 let texteMenu = document.querySelectorAll('.menu__lien');
 const text = new SplitType(texteMenu);
*/