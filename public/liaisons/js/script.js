/* document.addEventListener("DOMContentLoaded", function() {

gsap.registerPlugin(ScrollTrigger);

let refAnimation1 = document.querySelectorAll('.horaires_section');
let refArtiste1 = refAnimation1[1];

let animation = gsap.from(refAnimation1, {
    x: -1000,
    delay: 3,

});

ScrollTrigger.create({
    trigger: refAnimation1,
    animation: animation,
    start: "top 50%",
    end: "top 40%",
    opacity: 0,
    markers: true,
    scrub: 1,
    ease: "power4.out"
})
}); 
*/

let arrBoutonsDates = document.querySelectorAll(".dates_liste-item");
for (let intCpt = 0; intCpt < arrBoutonsDates.length; intCpt++) {
    arrBoutonsDates[intCpt].addEventListener("click", makeActive);
}
console.log(arrBoutonsDates);

function makeActive() {
    console.log("haha");
}



