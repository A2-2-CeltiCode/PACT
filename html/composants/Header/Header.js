let selecteurLangue = document.getElementsByClassName("selecteur-langue");
let logoLangue = document.getElementsByClassName("logo-langue");

for (const selecteur of selecteurLangue) {
    selecteur.addEventListener("input", (e) => {
        for (const logo of logoLangue) {
            logo.src = `/ressources/icone/logo${e.target.value}.svg`
        }
        for (const sl of selecteurLangue) {
            sl.value = e.target.value
        }
    });
}

const menu = document.querySelector(".menu")
const menuItems = document.querySelectorAll(".menuItem")
const hamburger = document.querySelector(".hamburger")
const closeIcon = document.querySelector(".closeIcon")
const menuIcon = document.querySelector(".menuIcon")
const overlay = document.getElementById("overlay")

function toggleMenu() {
    if (menu.classList.contains("showMenu")) {
        menu.classList.remove("showMenu")
        closeIcon.style.display = "none"
        menuIcon.style.display = "block"
        document.body.classList.remove("noscroll")
        overlay.style.visibility = "hidden"
    } else {
        menu.classList.add("showMenu");
        closeIcon.style.display = "block"
        menuIcon.style.display = "none"
        document.body.classList.add("noscroll")
        overlay.style.visibility = "visible"
    }
}

function toggleArrow() {
    const profileOption = document.getElementById('profile-option');
    const selectMenu = document.getElementById('selecteur-profil');

    // Ajouter un log pour déboguer
    console.log("Valeur sélectionnée : ", selectMenu.value);

    // Vérifier si l'option sélectionnée est la valeur par défaut ou une autre
    if (selectMenu.value === "default") {
        profileOption.textContent = 'Mon compte ▼'; // Menu fermé
    } else {
        profileOption.textContent = 'Mon compte ▲'; // Menu déroulé
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const selectMenu = document.getElementById('selecteur-profil');


    // Vérifier si le selecteur-profil existe
    if (selectMenu) {
        selectMenu.addEventListener('click', function() {
            toggleArrow();
        });
    } else {
        console.error("L'élément selecteur-profil n'a pas été trouvé.");
    }

    console.log("JavaScript chargé.");

});


hamburger.addEventListener("click", toggleMenu);