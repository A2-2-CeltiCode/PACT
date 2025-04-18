document.addEventListener("DOMContentLoaded", function () {
    const prevButton = document.querySelector(".carousel-button.prev");
    const nextButton = document.querySelector(".carousel-button.next");
    const images = document.querySelectorAll(".carousel-image");
    let currentIndex = 0;
  
    function updateCarousel() {
      images.forEach((img, index) => {
        img.style.display = index === currentIndex ? "block" : "none";
      });
      prevButton.style.display = currentIndex === 0 ? "none" : "flex";
      nextButton.style.display =
        currentIndex === images.length - 1 ? "none" : "flex";
    }
  
    prevButton.addEventListener("click", function () {
      if (currentIndex > 0) {
        currentIndex--;
        updateCarousel();
      }
    });
  
    nextButton.addEventListener("click", function () {
      if (currentIndex < images.length - 1) {
        currentIndex++;
        updateCarousel();
      }
    });
  
    updateCarousel();
  });
  
  document.addEventListener("DOMContentLoaded", function () {
    const detailElement = document.querySelector(".offre-detail");
    if (!detailElement) return;
  
    // Créer le bouton "voir plus"
    const voirPlusButton = document.createElement("span");
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
  });
  
  document.addEventListener("DOMContentLoaded", function () {
    const sortBySelect = document.getElementById("sortBy");

    function initializeEvents() {
        const repondreButtons = document.querySelectorAll(".btn-repondre");
        const popup = document.getElementById("popup-repondre");
        const closeBtn = popup.querySelector(".close");
        const idAvisInput = document.getElementById("popup-idAvis");

        repondreButtons.forEach(button => {
            button.addEventListener("click", function () {
                const idAvis = this.closest(".avi").dataset.idavis;
                idAvisInput.value = idAvis;
                popup.style.display = "block";
            });
        });

        closeBtn.addEventListener("click", function () {
            popup.style.display = "none";
        });

        window.addEventListener("click", function (event) {
            if (event.target === popup) {
                popup.style.display = "none";
            }
        });

        const thumbsUpButtons = document.querySelectorAll(".thumbs-up");
        const thumbsDownButtons = document.querySelectorAll(".thumbs-down");

        thumbsUpButtons.forEach(button => {
            button.addEventListener("click", function () {
                const idAvis = this.dataset.idavis;
                fetch(`thumbs.php?idAvis=${idAvis}&type=up`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            popupCreerAvis.style.display = "block";
                        }
                    });
            });
        });

        thumbsDownButtons.forEach(button => {
            button.addEventListener("click", function () {
                const idAvis = this.dataset.idavis;
                fetch(`thumbs.php?idAvis=${idAvis}&type=down`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            popupCreerAvis.style.display = "block";
                        }
                    });
            });
        });

        const creerAvisButton = document.querySelector(".btn-creer-avis");
        const popupCreerAvis = document.getElementById("popup-creer-avis");
        const closeCreerAvisBtn = popupCreerAvis.querySelector(".close");

        creerAvisButton.addEventListener("click", function () {
            popupCreerAvis.style.display = "block";
        });

        closeCreerAvisBtn.addEventListener("click", function () {
            popupCreerAvis.style.display = "none";
        });

        window.addEventListener("click", function (event) {
            if (event.target === popupCreerAvis) {
                popupCreerAvis.style.display = "none";
            }
        });
    }

    function fetchAvis() {
        const sortBy = sortBySelect.value;
        fetch(`detailsOffre.php?idOffre=${idOffre}&sortBy=${sortBy}`)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, "text/html");
                const avisList = doc.querySelector("#avis-list > div:last-child");
                document.querySelector("#avis-list > div:last-child").innerHTML = avisList.innerHTML;
                initializeEvents(); // Réinitialiser les événements après le tri
            });
    }

    sortBySelect.addEventListener("change", fetchAvis);

    initializeEvents(); // Initialiser les événements au chargement de la page
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