document.addEventListener("DOMContentLoaded", function () {
  // Gestion du carousel
  const prevButton = document.querySelector(".carousel-button.prev");
  const nextButton = document.querySelector(".carousel-button.next");
  const images = document.querySelectorAll(".carousel-image");
  let currentIndex = 0;

  function updateCarousel() {
      images.forEach((img, index) => {
          img.style.display = index === currentIndex ? "block" : "none";
      });
      prevButton.style.display = currentIndex === 0 ? "none" : "flex";
      nextButton.style.display = currentIndex === images.length - 1 ? "none" : "flex";
  }

  if (prevButton && nextButton) {
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
  }

  // Gestion du "Voir plus"
  const detailElement = document.querySelector(".offre-detail");
  if (detailElement) {
      const voirPlusButton = document.createElement("span");
      voirPlusButton.classList.add("voir-plus");
      voirPlusButton.textContent = "Voir plus";

      const maxCharacters = 100;
      if (detailElement.textContent.length > maxCharacters) {
          detailElement.classList.add("collapsed");
          detailElement.parentNode.insertBefore(voirPlusButton, detailElement.nextSibling);
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
          fetch(`detailsOffre.php?idOffre=${idOffre}&sortBy=${sortBy}`)
              .then((response) => response.text())
              .then((data) => {
                  const parser = new DOMParser();
                  const doc = parser.parseFromString(data, "text/html");
                  const avisList = doc.querySelector(".liste-avis > div:last-child");
                  document.querySelector(".liste-avis > div:last-child").innerHTML = avisList.innerHTML;
                  initializeEvents();
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
              const idReponse = this.dataset.idreponse;
              fetch(`signalerReponse.php?idReponse=${idReponse}`)
                  .then((response) => response.json())
                  .then((data) => {
                      if (data.success) {
                          toast.classList.add("show");
                          setTimeout(() => {
                              toast.classList.remove("show");
                          }, 3000);
                      }
                  });
          });
      });
  }

  // Gestion de la popup de création d'avis
  function initializeAvisPopup() {
      const creerAvisButton = document.querySelector(".btn-creer-avis");
      const popupCreerAvis = document.getElementById("popup-creer-avis");
      const closeCreerAvisBtn = popupCreerAvis.querySelector(".close");
      const form = popupCreerAvis.querySelector("form");
      const imagePreview = document.getElementById("imagePreview");
      const dropZone = document.querySelector(".drop-zone");

      // Ouverture de la popup
      creerAvisButton.addEventListener("click", function() {
          popupCreerAvis.style.display = "block";
          document.body.style.overflow = "hidden";
      });

      // Fermeture de la popup
      function closePopup() {
          popupCreerAvis.style.display = "none";
          document.body.style.overflow = "";
          form.reset();
          imagePreview.innerHTML = "";
      }

      closeCreerAvisBtn.addEventListener("click", closePopup);

      // Fermeture en cliquant en dehors
      window.addEventListener("click", function(event) {
          if (event.target === popupCreerAvis) {
              closePopup();
          }
      });

      // Système de notation par étoiles
      const ratingInputs = form.querySelectorAll('.rating input[type="radio"]');
      const ratingLabels = form.querySelectorAll('.rating label');

      ratingLabels.forEach((label, index) => {
          label.addEventListener("mouseover", () => {
              for (let i = ratingLabels.length - 1; i >= index; i--) {
                  ratingLabels[i].classList.add("hover");
              }
          });

          label.addEventListener("mouseout", () => {
              ratingLabels.forEach(label => label.classList.remove("hover"));
          });
      });

      
      // Validation du formulaire
      if (form) {
          form.addEventListener("submit", (e) => {
              e.preventDefault();
              
              const noteSelected = form.querySelector('input[name="note"]:checked');
              if (!noteSelected) {
                  alert("Veuillez attribuer une note.");
                  return;
              }

              const titre = form.querySelector('input[name="titre"]').value.trim();
              if (titre.length < 3) {
                  alert("Le titre doit contenir au moins 3 caractères.");
                  return;
              }

              const commentaire = form.querySelector('textarea[name="commentaire"]').value.trim();
              if (commentaire.length < 10) {
                  alert("Le commentaire doit contenir au moins 10 caractères.");
                  return;
              }

              const contexte = form.querySelector('select[name="contexteVisite"]').value;
              if (!contexte) {
                  alert("Veuillez sélectionner un contexte de visite.");
                  return;
              }

              form.submit();
          });
      }
  }

  // Fonction d'initialisation globale des événements
  function initializeEvents() {
      initializeRepondreButtons();
      initializeThumbButtons();
      initializeSignalerButtons();
      initializeAvisPopup();
  }

  // Initialisation lors du chargement
  initializeEvents();
});