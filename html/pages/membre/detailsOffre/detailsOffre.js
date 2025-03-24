document.addEventListener("DOMContentLoaded", function () {
  // Gestion du carousel
  const prevButton = document.querySelector(".carousel-button.prev");
  const nextButton = document.querySelector(".carousel-button.next");
  const images = document.querySelectorAll(".carousel-image");
  let currentIndex = 0;
  const items = document.querySelectorAll(".carousel-image");
  const dotsContainer = document.querySelector(".carousel-dots");

  // création des dots
  items.forEach((item, index) => {
    if (images.length !== 1) {
      const dot = document.createElement("span");
      dot.addEventListener("click", () => goToSlide(index));
      dotsContainer.appendChild(dot);
    }
  });

  // Mise a jour des Dots (point de déplacement en bas des imgs)
  function updateDots() {
    const dots = dotsContainer.querySelectorAll("span");
    dots.forEach((dot, index) => {
      dot.classList.toggle("active", index === currentIndex);
    });
  }

  // déplacement entre les slide
  function goToSlide(index) {
    items[currentIndex].classList.remove("active");
    items[currentIndex].style.display =
      index === currentIndex ? "block" : "none";
    currentIndex = (index + items.length) % items.length;
    items[currentIndex].style.display =
      index === currentIndex ? "block" : "none";
    items[currentIndex].classList.add("active");
    updateDots();
  }

  // Initialize
  updateDots();

  function updateCarousel() {
    images.forEach((img, index) => {
      img.style.display = index === currentIndex ? "block" : "none";
    });
    if (images.length === 1) {
      prevButton.style.display = "none";
      nextButton.style.display = "none";
    } else {
      prevButton.style.display = currentIndex === 0 ? "flex" : "flex";
      nextButton.style.display =
        currentIndex === images.length - 1 ? "flex" : "flex";
    }
    updateDots();
  }

  if (prevButton && nextButton) {
    prevButton.addEventListener("click", function () {
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      updateCarousel();
    });

    nextButton.addEventListener("click", function () {
      currentIndex = (currentIndex + 1) % images.length;
      updateCarousel();
    });
  }

  // Fonctionnalité de swipe
  let touchStartX = 0;
  let touchEndX = 0;

  function handleTouchStart(event) {
    touchStartX = event.changedTouches[0].screenX;
  }

  function handleTouchEnd(event) {
    touchEndX = event.changedTouches[0].screenX;
    if (touchStartX - touchEndX > 50) {
      // Swipe à gauche
      currentIndex = (currentIndex + 1) % images.length;
      updateCarousel();
    } else if (touchEndX - touchStartX > 50) {
      // Swipe à droite
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      updateCarousel();
    }
  }

  // Attacher les événements de swipe
  const carousel = document.querySelector(".carousel");
  carousel.addEventListener("touchstart", handleTouchStart);
  carousel.addEventListener("touchend", handleTouchEnd);

  updateCarousel();
});

// Gestion du "Voir plus"
const detailElement = document.querySelector(".offre-detail");
if (detailElement) {
  const voirPlusButton = document.createElement("span");
  const voirplusother = document.getElementById("offre-detail");
  voirPlusButton.classList.add("voir-plus");
  voirPlusButton.textContent = "Voir plus";

  const maxCharacters = 100;
  if (detailElement.textContent.length > maxCharacters) {
    detailElement.classList.add("collapsed");
    detailElement.parentNode.insertBefore(
      voirPlusButton,
      detailElement.nextSibling
    );
  }

  voirPlusButton.addEventListener("click", function () {
    if (detailElement.classList.contains("collapsed")) {
      detailElement.style.maxHeight = detailElement.scrollHeight + "px";
      detailElement.classList.remove("collapsed");
      voirPlusButton.textContent = "Voir moins";
    } else {
      detailElement.style.maxHeight = "4.5em";
      detailElement.classList.add("collapsed");
      voirPlusButton.textContent = "Voir plus";
    }
  });

  // Gérere le clic sur le text
  voirplusother.addEventListener("click", function () {
    if (detailElement.classList.contains("collapsed")) {
      detailElement.style.maxHeight = detailElement.scrollHeight + "px";
      detailElement.classList.remove("collapsed");
      voirPlusButton.textContent = "Voir moins";
    } else {
      detailElement.style.maxHeight = "4.5em";
      detailElement.classList.add("collapsed");
      voirPlusButton.textContent = "Voir plus";
    }
  });
}

// Gestion des réponses aux avis
const popup = document.getElementById("popup-repondre");
if (popup) {
  const closeBtn = popup.querySelector(".close");
  const idAvisInput = document.getElementById("popup-idAvis");

  function initializeRepondreButtons() {
    const repondreButtons = document.querySelectorAll(".btn-repondre");
    repondreButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const idAvis = this.closest(".avi").dataset.idavis;
        idAvisInput.value = idAvis;
        popup.style.display = "block";
      });
    });
  }

  closeBtn.addEventListener("click", function () {
    popup.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target === popup) {
      popup.style.display = "none";
    }
  });

  initializeRepondreButtons();
}

// Gestion du tri des avis
const sortBySelect = document.getElementById("sortBy");
if (sortBySelect) {
  function fetchAvis() {
    const sortBy = sortBySelect.value;
    const idOffre = new URLSearchParams(window.location.search).get("id");
    fetch(`detailsOffre.php?idOffre=${idOffre}&sortBy=${sortBy}`)
      .then((response) => response.text())
      .then((data) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, "text/html");
        const avisList = doc.querySelector(".liste-avis > div:last-child");
        document.querySelector(".liste-avis > div:last-child").innerHTML =
          avisList.innerHTML;
        initializeEvents(); // Réinitialiser les événements après le tri
      });
  }

  sortBySelect.addEventListener("change", fetchAvis);
}

// Gestion des pouces (likes/dislikes)
function initializeThumbButtons() {
  const thumbsUpButtons = document.querySelectorAll(".thumbs-up");
  const thumbsDownButtons = document.querySelectorAll(".thumbs-down");

  thumbsUpButtons.forEach((button) => {
    button.addEventListener("click", function () {
      if (this.disabled) return;
      this.disabled = true;
      const idAvis = this.dataset.idavis;
      fetch(`thumbs.php?idAvis=${idAvis}&type=up`)
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            this.textContent = `👍 ${data.thumbs_up}`;
            const thumbsDownButton = this.nextElementSibling;
            thumbsDownButton.textContent = `👎 ${data.thumbs_down}`;
          }
          this.disabled = false;
        });
    });
  });

  thumbsDownButtons.forEach((button) => {
    button.addEventListener("click", function () {
      if (this.disabled) return;
      this.disabled = true;
      const idAvis = this.dataset.idavis;
      fetch(`thumbs.php?idAvis=${idAvis}&type=down`)
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            this.textContent = `👎 ${data.thumbs_down}`;
            const thumbsUpButton = this.previousElementSibling;
            thumbsUpButton.textContent = `👍 ${data.thumbs_up}`;
          }
          this.disabled = false;
        });
    });
  });
}

// Initialisation des événements de signalement
function initializeSignalerButtons() {
  const signalerButtons = document.querySelectorAll(".btn-signaler");
  const toast = document.getElementById("toast");

  signalerButtons.forEach((button) => {
    button.addEventListener("click", function () {
      toast.classList.add("show");
      setTimeout(() => {
        toast.classList.remove("show");
      }, 3000);
    });
  });
}

// Initialisation des événements de signalement
const signalerButtons = document.querySelectorAll(".btn-signaler");
const toast = document.getElementById("toast");

signalerButtons.forEach((button) => {
  button.addEventListener("click", function () {
    toast.classList.add("show");
    setTimeout(() => {
      toast.classList.remove("show");
    }, 3000);
  });
});

let keydownHandler;

// Gestion de la popup de création d'avis
function initializeAvisPopup() {
  const creerAvisButton = document.querySelector(".btn-creer-avis");
  const popupCreerAvis = document.getElementById("popup-creer-avis");
  const popupDejaAvis = document.getElementById("popup-deja-avis");
  const closeCreerAvisBtn = popupCreerAvis.querySelector(".close");
  const closeDejaAvisBtn = popupDejaAvis.querySelector(".close");
  const form = popupCreerAvis.querySelector("form");
  const imagePreview = document.getElementById("imagePreview");
  const dropZone = document.querySelector(".drop-zone");

  // Ouverture de la popup
  creerAvisButton.addEventListener("click", function () {
    if (dejaPublieAvis) {
      popupDejaAvis.style.display = "block";
    } else {
      popupCreerAvis.style.display = "block";
      document.body.style.overflow = "hidden";
      keydownHandler = function(event) {
        if (event.key === "Enter") {
            document.getElementById("submit-avis").click();
        } else if (event.key === "Escape") {
            document.querySelector(".close").click();
        }};
    
      document.addEventListener("keydown", keydownHandler);
    }
  });

  // Fermeture de la popup
  function closePopup() {
    popupCreerAvis.style.display = "none";
    document.body.style.overflow = "";
    form.reset();
    imagePreview.innerHTML = "";
    document.removeEventListener("keydown", keydownHandler);
  }

  closeCreerAvisBtn.addEventListener("click", closePopup);
  closeDejaAvisBtn.addEventListener("click", function () {
    popupDejaAvis.style.display = "none";
  });

  // Fermeture en cliquant en dehors
  window.addEventListener("click", function (event) {
    if (event.target === popupCreerAvis) {
      closePopup();
    } else if (event.target === popupDejaAvis) {
      popupDejaAvis.style.display = "none";
    }
  });

  // Système de notation par étoiles
  const ratingInputs = form.querySelectorAll('.rating input[type="radio"]');
  const ratingLabels = form.querySelectorAll(".rating label");

  ratingLabels.forEach((label, index) => {
    label.addEventListener("mouseover", () => {
      for (let i = ratingLabels.length - 1; i >= index; i--) {
        ratingLabels[i].classList.add("hover");
      }
    });

    label.addEventListener("mouseout", () => {
      ratingLabels.forEach((label) => label.classList.remove("hover"));
    });
  });
}

// Initialisation des événements de suppression
function initializeSupprimerButtons() {
  const supprimerButtons = document.querySelectorAll(".btn-supprimer");

  supprimerButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const idAvis = this.dataset.idavis;
      if (confirm("Êtes-vous sûr de vouloir supprimer cet avis ?")) {
        fetch(`supprimerAvis.php?idAvis=${idAvis}`, {
          method: "POST",
        }).then((response) => {
          if (response.ok) {
            location.reload();
          }
        });
      }
    });
  });
}

// Fonction d'initialisation globale des événements
function initializeEvents() {
  initializeRepondreButtons();
  initializeThumbButtons();
  initializeAvisPopup();
  initializeSupprimerButtons();
  initializeSignalerButtons(); // Ajouter l'initialisation des boutons de signalement
}

// Initialisation lors du chargement
initializeEvents();
