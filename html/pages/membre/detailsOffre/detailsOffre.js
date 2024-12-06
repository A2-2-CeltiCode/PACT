addEventListener("DOMContentLoaded", (event) => {
    document.querySelector("#contexte > option:first-child").disabled = true;
});

let currentIndex = 0;
const images = document.querySelectorAll('.carousel-image');
const totalImages = images.length;
let compteur = 1;

function updateCarousel() {
    const offset = -currentIndex * 100;
    document.querySelector('.carousel-images').style.transform = `translateX(${offset}%)`;
    
}


if (images.length > 1) {
    document.querySelector('.next').classList.remove('desactive');
}


document.querySelector('.next').addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % totalImages;
    updateCarousel();
    if (compteur < totalImages) {
        compteur++;
    }
    if (compteur == totalImages) {
        document.querySelector('.next').classList.add('desactive');
        document.querySelector('.prev').classList.remove('desactive');
    }
});

document.querySelector('.prev').addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + totalImages) % totalImages;
    updateCarousel();
    if (compteur > 1) {
        compteur--;
    }
    if (compteur == 1) {
        document.querySelector('.prev').classList.add('desactive');
        document.querySelector('.next').classList.remove('desactive');
    }
});


function popupavis() {

    const popupOverlay = document.getElementById('popupOverlay');

    const popup = document.getElementById('popup');

    const closePopup = document.getElementById('closePopup');

    const emailInput = document.getElementById('emailInput');

// Function to open the popup

    function openPopup() {

        document.body.classList.add("noscroll")
        popupOverlay.style.display = 'block';

    }

// Function to close the popup

    function closePopupFunc() {

        document.body.classList.remove("noscroll")
        popupOverlay.style.display = 'none';

    }

// Function to submit the signup form

    function submitForm() {

        const email = emailInput.value;

// Add your form submission logic here

        console.log(`Email submitted: ${email}`);

        closePopupFunc(); // Close the popup after form submission

    }

// Event listeners

// Trigger the popup to open (you can call this function on a button click or any other event)

    openPopup();

// Close the popup when the close button is clicked

    closePopup.addEventListener('click', closePopupFunc);

// Close the popup when clicking outside the popup content

    popupOverlay.addEventListener('click', function (event) {

        if (event.target === popupOverlay) {

            closePopupFunc();

        }

    });
}

const modal = document.getElementById("myModal");

const span = document.getElementsByClassName("close")[1];

const modalImage = document.getElementById("modal-image");

function openUp(e) {
    const src = e.srcElement.src;
    modal.style.display = "block";
    modalImage.src = src;
    document.body.classList.add("noscroll")
}

span.onclick = function () {
    modal.style.display = "none";
    document.body.classList.remove("noscroll")
};

window.onclick = function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
        document.body.classList.remove("noscroll")
    }
};

function submitForm() {
    let valid = true;
    if (new Date() < new Date(document.getElementById("datevisite").value)) {
        valid = false;
    } else if (document.getElementById("contexte").selectedIndex === 0) {
        valid = false;
    }
    return valid;
}