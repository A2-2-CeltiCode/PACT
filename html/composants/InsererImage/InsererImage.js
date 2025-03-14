document.addEventListener("DOMContentLoaded", function () {
  // Sélectionner toutes les zones de dépôt
  const dropZones = document.querySelectorAll(".drop-zone");

  dropZones.forEach((dropZone) => {
    const dropZoneId = dropZone.getAttribute("data-id");
    const maxFiles = parseInt(dropZone.getAttribute("data-max-files"), 10);
    const maxFileNameLength = 20; // Longueur maximale des noms de fichiers avant troncation

    const fileInput = document.getElementById(`fileInput-${dropZoneId}`); // Input associé à la zone
    const successMessage = document.getElementById(
      `successMessage-${dropZoneId}`
    ); // Message de succès associé
    const imagePreview = document.getElementById(`imagePreview-${dropZoneId}`); // Conteneur de prévisualisation des images
    const imagePreviewModal = document.getElementById(
      `imagePreviewModal-${dropZoneId}`
    );
    const imagePreviewModalImg = document.getElementById(
      `imagePreviewModalImg-${dropZoneId}`
    );

    // Événement pour le clic sur la zone de dépôt
    dropZone.addEventListener("click", () => {
      fileInput.click();
    });

    // Empêcher le comportement par défaut
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
      dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    // Ajouter une classe quand un fichier est survolé
    ["dragenter", "dragover"].forEach((eventName) => {
      dropZone.addEventListener(
        eventName,
        () => {
          dropZone.classList.add("dragover");
        },
        false
      );
    });

    ["dragleave", "drop"].forEach((eventName) => {
      dropZone.addEventListener(
        eventName,
        () => {
          dropZone.classList.remove("dragover");
        },
        false
      );
    });

    // Gérer le dépôt du fichier
    dropZone.addEventListener("drop", (e) => {
      const files = e.dataTransfer.files;

      if (files.length + fileInput.files.length > maxFiles) {
        successMessage.style.display = "none";
        alert(`Vous ne pouvez déposer que ${maxFiles} images maximum.`);
        return;
      }

      const dt = new DataTransfer();
      Array.from(fileInput.files).forEach((file) => dt.items.add(file));
      Array.from(files).forEach((file) => dt.items.add(file));
      fileInput.files = dt.files;

      displayImages(fileInput.files); // Utiliser fileInput.files pour afficher toutes les images
      successMessage.style.display = "block";
    });

    // Gérer le changement d'état du file input
    fileInput.addEventListener("change", () => {
      if (fileInput.files.length > maxFiles) {
        successMessage.style.display = "none";
        alert(`Vous ne pouvez sélectionner que ${maxFiles} images maximum.`);
        fileInput.value = "";
        return;
      }

      const dt = new DataTransfer();
      Array.from(fileInput.files).forEach((file) => dt.items.add(file));
      fileInput.files = dt.files;

      displayImages(fileInput.files); // Utiliser fileInput.files pour afficher toutes les images
      successMessage.style.display = "block";
    });

    // Fonction pour afficher les images
    function displayImages(files) {
      // Ne pas vider imagePreview pour conserver les anciennes images
      Array.from(files).forEach((file) => {
        const reader = new FileReader();
        reader.onload = function (e) {
          const container = document.createElement("div");
          container.classList.add("image-preview-container");

          const img = document.createElement("img");
          img.src = e.target.result;
          img.alt = file.name;
          img.addEventListener("click", () => {
            imagePreviewModalImg.src = e.target.result;
            imagePreviewModal.classList.add("show");
          });

          const deleteBtn = document.createElement("button");
          deleteBtn.classList.add("delete-btn");
          deleteBtn.innerHTML = "&times;";
          deleteBtn.addEventListener("click", () => {
            container.remove();
            removeFile(file.name); // Utiliser le nom du fichier pour le supprimer
          });

          container.appendChild(img);
          container.appendChild(deleteBtn);
          imagePreview.appendChild(container);
        };
        reader.readAsDataURL(file);
      });
    }

    // Fonction pour supprimer un fichier de l'input
    function removeFile(fileName) {
      const dt = new DataTransfer();
      const files = fileInput.files;

      for (let i = 0; i < files.length; i++) {
        if (files[i].name !== fileName) {
          dt.items.add(files[i]);
        }
      }

      fileInput.files = dt.files;
      imagePreview.innerHTML = ""; // Vider imagePreview avant de réafficher les images restantes
      displayImages(fileInput.files); // Mettre à jour l'affichage après suppression
      successMessage.style.display =
        fileInput.files.length > 0 ? "block" : "none";
    }

    // Fermer la prévisualisation en plein écran en cliquant dessus
    imagePreviewModal.addEventListener("click", () => {
      imagePreviewModal.classList.remove("show");
    });

    // Fonction pour tronquer les noms de fichiers
    function truncateFileName(fileName, maxLength) {
      if (fileName.length > maxLength) {
        return fileName.substring(0, maxLength) + "...";
      }
      return fileName;
    }
  });
});
