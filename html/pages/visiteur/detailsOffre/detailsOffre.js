// Initialisation de l'index actuel et des variables nécessaires
let currentIndex = 0; 
const images = document.querySelectorAll('.carousel-image');
const totalImages = images.length; 
let compteur = 1; 

// Si le carrousel ne contient qu'une seule image, désactive les boutons "Suivant" et "Précédent"
if (totalImages == 1) {
    document.querySelector('.next').classList.add('desactive'); 
    document.querySelector('.prev').classList.add('desactive');
}

// Fonction pour mettre à jour la position du carrousel
function updateCarousel() {
    const offset = -currentIndex * 100; 
    document.querySelector('.carousel-images').style.transform = `translateX(${offset}%)`; 
}

// Si le carrousel contient plusieurs images, active le bouton "Suivant"
if (images.length > 1) {
    document.querySelector('.next').classList.remove('desactive');
}

// Gestionnaire d'événement pour le bouton "Suivant"
document.querySelector('.next').addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % totalImages; 
    updateCarousel(); 

    if (compteur < totalImages) {
        compteur++;
    }

    // Si on atteint la dernière image, désactive le bouton "Suivant" et active "Précédent"
    if (compteur == totalImages) {
        document.querySelector('.next').classList.add('desactive'); 
        document.querySelector('.prev').classList.remove('desactive'); 
    }
});

// Gestionnaire d'événement pour le bouton "Précédent"
document.querySelector('.prev').addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + totalImages) % totalImages; 
    updateCarousel(); 

    if (compteur > 1) {
        compteur--;
    }

    // Si on atteint la première image, désactive le bouton "Précédent" et active "Suivant"
    if (compteur == 1) {
        document.querySelector('.prev').classList.add('desactive'); 
        document.querySelector('.next').classList.remove('desactive'); 
    }
});

// Récupère les éléments pour la fenêtre modale
const modal = document.getElementById("myModal"); 
const span = document.getElementsByClassName("close")[0]; 
const modalImage = document.getElementById("modal-image"); 

// Fonction pour ouvrir la modal et afficher l'image sélectionnée
function openUp(e) {
    const src = e.srcElement.src; 
    modal.style.display = "block"; 
    modalImage.src = src; 
    document.body.classList.add("noscroll"); 
}

// Gestionnaire d'événement pour fermer la modal en cliquant sur le bouton "X"
span.onclick = function () {
    modal.style.display = "none"; 
    document.body.classList.remove("noscroll"); 
};

// Gestionnaire d'événement pour fermer la modal en cliquant en dehors de celle-ci
window.onclick = function (event) {
    if (event.target === modal) { 
        modal.style.display = "none"; 
        document.body.classList.remove("noscroll"); 
    }
};
