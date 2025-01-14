let currentIndex = 0; 
const images = document.querySelectorAll('.carousel-image'); 
const totalImages = images.length; 
let compteur = 1; 

// Fonction pour mettre à jour la position du carrousel
function updateCarousel() {
    const offset = -currentIndex * 100; 
    document.querySelector('.carousel-images').style.transform = `translateX(${offset}%)`;
}

// Activation du bouton "Suivant" uniquement si le carrousel a plus d'une image
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

    // Désactiver le bouton "Suivant" lorsqu'on atteint la dernière image
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

    // Désactiver le bouton "Précédent" lorsqu'on est sur la première image
    if (compteur == 1) {
        document.querySelector('.prev').classList.add('desactive');
        document.querySelector('.next').classList.remove('desactive'); 
    }
});

const modal = document.getElementById("myModal"); 
const span = document.getElementsByClassName("close")[0]; 
const modalImage = document.getElementById("modal-image"); 

// Fonction pour ouvrir la modal
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
