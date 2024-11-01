let refButtonlieux = document.querySelector(".main_head-dropBtn").addEventListener("click", openLieux);
let refDivLieux = document.querySelector(".main_head-dropDiv");

function openLieux() {
    if (refDivLieux.classList.contains("main_head-dropVisible")) {
        refDivLieux.classList.remove("main_head-dropVisible");
        refDivLieux.classList.add("main_head-dropHidden");
    } else {
    refDivLieux.classList.remove("main_head-dropHidden");
    refDivLieux.classList.add("main_head-dropVisible");
    }
}