document.addEventListener("DOMContentLoaded", function () {
    const tailleDeplacements = 332;
  
    // Gestion des carrousels horizontaux
    for (const carousel of document.getElementsByClassName("carrousel")) {
      const prevBtn = carousel.nextElementSibling.children[0];
      const nextBtn = carousel.nextElementSibling.children[1];
  
      prevBtn.onclick = () => {
        carousel.scrollLeft -= tailleDeplacements;
        checkButtonStatus();
      };
  
      nextBtn.onclick = () => {
        carousel.scrollLeft += tailleDeplacements;
        checkButtonStatus();
      };
  
      // Vérifier si les boutons doivent être activés ou désactivés
      function checkButtonStatus() {
        prevBtn.classList.toggle("disabled", carousel.scrollLeft <= 0);
        nextBtn.classList.toggle(
          "disabled",
          carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth - 10
        );
      }
  
      // Vérifier au chargement
      checkButtonStatus();
  
      // Vérifier pendant le défilement
      carousel.addEventListener("scroll", checkButtonStatus);
    }
  
    // Gestion du carrousel principal
    const carouselContainer = document.querySelector(".carousel");
    if (!carouselContainer) return;
  
    const prevButton = document.querySelector(".carousel-button.prev");
    const nextButton = document.querySelector(".carousel-button.next");
    const items = document.querySelectorAll(".carousel-item");
    const dotsContainer = document.querySelector(".carousel-dots");
  
    let currentIndex = 0;
    let autoplayInterval;
    let autoplayDelay = 7000; 
  
    // Ajout d'un indicateur de progression
    const progressBar = document.createElement("div");
    progressBar.className = "carousel-progress";
    carouselContainer.appendChild(progressBar);
  
    // Création des points de navigation
    items.forEach((item, index) => {
      if (items.length !== 1) {
        const dot = document.createElement("span");
        dot.addEventListener("click", () => {
          goToSlide(index);
          resetAutoplay();
        });
        dotsContainer.appendChild(dot);
      }
    });
  
    // Mise à jour des points de navigation
    function updateDots() {
      const dots = dotsContainer.querySelectorAll("span");
      dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentIndex);
      });
    }
  
    // Mise à jour de la barre de progression
    function updateProgressBar() {
      progressBar.style.width = "0%";
      if (items.length > 1) {
        progressBar.style.transition = "none"; // Ajouté pour réinitialiser la transition
        setTimeout(() => {
          progressBar.style.transition = "width " + autoplayDelay + "ms linear";
          progressBar.style.width = "100%";
        }, 50);
      }
    }
  
    // Navigation vers une slide spécifique
    function goToSlide(index) {
      // Supprime la classe active de toutes les slides
      items.forEach((item) => {
        item.classList.remove("active");
        item.style.display = "none";
      });
  
      currentIndex = (index + items.length) % items.length;
  
      // Ajoute la classe active à la slide actuelle
      items[currentIndex].style.display = "block";
      setTimeout(() => {
        items[currentIndex].classList.add("active");
      }, 10);
  
      updateDots();
      updateProgressBar(); // Assurez-vous que la barre de progression est mise à jour à chaque changement de slide
    }
  
    // Initialisation du carrousel
    function initCarousel() {
      goToSlide(0);
  
      // Cacher les boutons de navigation si un seul élément
      if (items.length <= 1) {
        prevButton.classList.add("desactive");
        nextButton.classList.add("desactive");
        dotsContainer.style.display = "none";
        progressBar.style.display = "none";
      } else {
        prevButton.classList.remove("desactive");
        nextButton.classList.remove("desactive");
        startAutoplay();
      }
    }
  
    // Démarrer l'autoplay
    function startAutoplay() {
      if (items.length <= 1) return;
  
      clearInterval(autoplayInterval);
      updateProgressBar();
  
      autoplayInterval = setInterval(() => {
        goToSlide(currentIndex + 1);
      }, autoplayDelay);
    }
  
    // Réinitialiser l'autoplay après interaction de l'utilisateur
    function resetAutoplay() {
      clearInterval(autoplayInterval);
      startAutoplay();
    }
  
    // Événements pour les boutons de navigation
    prevButton.addEventListener("click", function () {
      goToSlide(currentIndex - 1);
      resetAutoplay();
    });
  
    nextButton.addEventListener("click", function () {
      goToSlide(currentIndex + 1);
      resetAutoplay();
    });
  
    // Fonctionnalité tactile
    let touchStartX = 0;
    let touchEndX = 0;
  
    function handleTouchStart(event) {
      touchStartX = event.changedTouches[0].screenX;
    }
  
    function handleTouchEnd(event) {
      touchEndX = event.changedTouches[0].screenX;
  
      if (touchStartX - touchEndX > 50) {
        // Swipe à gauche
        goToSlide(currentIndex + 1);
      } else if (touchEndX - touchStartX > 50) {
        // Swipe à droite
        goToSlide(currentIndex - 1);
      }
  
      resetAutoplay();
    }
  
    // Mise en pause de l'autoplay au survol
    carouselContainer.addEventListener("mouseenter", function () {
      clearInterval(autoplayInterval);
      progressBar.style.transition = "none";
      progressBar.style.width = "0%";
    });
  
    carouselContainer.addEventListener("mouseleave", function () {
      startAutoplay();
    });
  
    // Gestion des événements tactiles
    carouselContainer.addEventListener("touchstart", handleTouchStart);
    carouselContainer.addEventListener("touchend", handleTouchEnd);
  
    // Touches clavier pour la navigation
    document.addEventListener("keydown", function (e) {
      if (
        document.activeElement.tagName === "INPUT" ||
        document.activeElement.tagName === "TEXTAREA"
      ) {
        return;
      }
  
      if (e.key === "ArrowLeft") {
        goToSlide(currentIndex - 1);
        resetAutoplay();
      } else if (e.key === "ArrowRight") {
        goToSlide(currentIndex + 1);
        resetAutoplay();
      }
    });
  
    // Initialiser le carrousel
    initCarousel();
  });
  