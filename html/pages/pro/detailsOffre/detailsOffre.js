document.addEventListener("DOMContentLoaded", function () {
  const prevButton = document.querySelector(".carousel-button.prev");
  const nextButton = document.querySelector(".carousel-button.next");
  const images = document.querySelectorAll(".carousel-image");
  let currentIndex = 0;
  const items = document.querySelectorAll(".carousel-image");
  const dotsContainer = document.querySelector('.carousel-dots');

// création des dots
items.forEach((item, index) => {
  const dot = document.createElement('span');
  dot.addEventListener('click', () => goToSlide(index));
  dotsContainer.appendChild(dot);
});

// Mise a jour des Dots (point de déplacement en bas des imgs)
function updateDots() {
  const dots = dotsContainer.querySelectorAll('span');
  dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
  });
}

// déplacement entre les slide
function goToSlide(index) {
  items[currentIndex].classList.remove('active');
  items[currentIndex].style.display = index === currentIndex ? "block" : "none";
  currentIndex = (index + items.length) % items.length;
  items[currentIndex].style.display = index === currentIndex ? "block" : "none";
  items[currentIndex].classList.add('active');
  updateDots();
}

// Initialize
updateDots();

  function updateCarousel() {
    images.forEach((img, index) => {
      img.style.display = index === currentIndex ? "block" : "none";
    });
    prevButton.style.display = currentIndex === 0 ? "flex" : "flex";
    nextButton.style.display = currentIndex === images.length - 1 ? "flex" : "flex";
        updateDots();
  }

  prevButton.addEventListener("click", function () {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateCarousel();
  });

  nextButton.addEventListener("click", function () {
    currentIndex = (currentIndex + 1) % images.length;
        updateCarousel();
  });

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

document.addEventListener("DOMContentLoaded", function () {
  const detailElement = document.querySelector(".offre-detail");
  if (!detailElement) return;

  // Créer le bouton "voir plus"
  const voirPlusButton = document.createElement("span");
  const voirplusother = document.getElementById("offre-detail");
  voirPlusButton.classList.add("voir-plus");
  voirPlusButton.textContent = "Voir plus";

  // Ajouter la classe collapsed initialement si le contenu dépasse un certain nombre de caractères
  const maxCharacters = 100; // Définir le nombre maximum de caractères avant d'afficher "Voir plus"
  if (detailElement.textContent.length > maxCharacters) {
    detailElement.classList.add("collapsed");
    // Insérer le bouton après l'élément de détail
    detailElement.parentNode.insertBefore(
      voirPlusButton,
      detailElement.nextSibling
    );
  }

  // Gérer le clic sur le bouton
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
});

document.addEventListener("DOMContentLoaded", function () {
  const repondreButtons = document.querySelectorAll(".btn-repondre");
  const popupRepondre = document.getElementById("popup-repondre");
  const closeBtn = popupRepondre.querySelector(".close");
  const idAvisInput = document.getElementById("popup-idAvis");

  repondreButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const idAvis = this.closest(".avi").dataset.idavis;
      idAvisInput.value = idAvis;
      popupRepondre.style.display = "block";
    });
  });

  closeBtn.addEventListener("click", function () {
    popupRepondre.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target === popupRepondre) {
      popupRepondre.style.display = "none";
    } else {
      document.querySelectorAll(".avi.prioritaire").forEach((element) => {
        element.classList.remove("prioritaire");
      });
    }
  });

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

  const sortBySelect = document.getElementById("sortBy");
  const filterBySelect = document.getElementById("filterBy");

  function fetchAvis() {
    const sortBy = sortBySelect.value;
    const filterBy = filterBySelect.value;

    fetch(
      `detailsOffre.php?idOffre=${idOffre}&sortBy=${sortBy}&filterBy=${filterBy}`
    )
      .then((response) => response.text())
      .then((data) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, "text/html");
        const avisList = doc.querySelector(".liste-avis > div:last-child");
        document.querySelector(".liste-avis > div:last-child").innerHTML = avisList.innerHTML;
        initButtons(); // Réinitialiser les écouteurs d'événements
      });
  }

  sortBySelect.addEventListener("change", fetchAvis);
  filterBySelect.addEventListener("change", fetchAvis);

  function initButtons() {
    const repondreButtons = document.querySelectorAll(".btn-repondre");
    const signalerButtons = document.querySelectorAll(".btn-signaler");
    const idAvisInput = document.getElementById("popup-idAvis");
    const popupRepondre = document.getElementById("popup-repondre");
    const closeBtn = popupRepondre.querySelector(".close");

    repondreButtons.forEach(button => {
      button.addEventListener("click", function () {
        const idAvis = this.closest(".avi").dataset.idavis;
        idAvisInput.value = idAvis;
        popupRepondre.style.display = "block";
      });
    });

    closeBtn.addEventListener("click", function () {
      popupRepondre.style.display = "none";
    });

    window.addEventListener("click", function (event) {
      if (event.target === popupRepondre) {
        popupRepondre.style.display = "none";
      }
    });

    signalerButtons.forEach((button) => {
      button.addEventListener("click", function () {
        toast.classList.add("show");
        setTimeout(() => {
          toast.classList.remove("show");
        }, 3000);
      });
    });

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

    // Réinitialiser l'observateur pour marquer les avis comme vus
    const avisElements = document.querySelectorAll(".avi.non-vu");
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const idAvis = entry.target.dataset.idavis;
          fetch(`markAsSeen.php?idAvis=${idAvis}`, {
            method: 'POST'
          }).then(response => {
            // ...existing code...
          });
        }
      });
    });

    avisElements.forEach(avi => {
      observer.observe(avi);
    });
  }

  initButtons(); // Initialiser les écouteurs d'événements au chargement de la page
});


document.addEventListener("DOMContentLoaded", function () {
  const avisElements = document.querySelectorAll(".avi.non-vu");

  const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
          if (entry.isIntersecting) {
              const idAvis = entry.target.dataset.idavis;
              fetch(`markAsSeen.php?idAvis=${idAvis}`, {
                  method: 'POST'
              }).then(response => {

              });
          }
      });
  });

  avisElements.forEach(avi => {
      observer.observe(avi);
  });

  const blacklistButtons = document.querySelectorAll(".btn-blacklister");
    const popupBlacklist = document.getElementById("popup-blacklist");
    const closePopupBlacklist = popupBlacklist.querySelector(".close");
    const closePopupButton = document.getElementById("blacklist-decline");

    blacklistButtons.forEach((button) => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Empêche le formulaire de se soumettre immédiatement
            const form = this.closest("form");
            const idAvis = form.querySelector("input[name='idAvis']").value;
            const idOffre = form.querySelector("input[name='idOffre']").value;

            // Mettre à jour les champs cachés du popup avec les valeurs actuelles
            popupBlacklist.querySelector("input[name='idAvis']").value = idAvis;
            popupBlacklist.querySelector("input[name='idOffre']").value = idOffre;

            // Afficher le popup
            popupBlacklist.style.display = "block";
        });
    });

    closePopupBlacklist.addEventListener("click", function () {
        popupBlacklist.style.display = "none";
    });

    closePopupButton.addEventListener("click", function () {
        popupBlacklist.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === popupBlacklist) {
            popupBlacklist.style.display = "none";
        }
    });
});