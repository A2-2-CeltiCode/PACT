document.addEventListener('DOMContentLoaded', function () {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('fileInput');
    const successMessage = document.getElementById('successMessage');
    const maxFiles = 5; // Limite du nombre d'images

    // Événement pour le clic sur la zone de dépôt
    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    // Empêcher le comportement par défaut (ouvrir le fichier)
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Ajouter une classe quand un fichier est survolé
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('dragover');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('dragover');
        }, false);
    });

    // Gérer le dépôt du fichier
    dropZone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;

        if (files.length > maxFiles) {
            successMessage.style.display = 'none'; // Masquer le message de succès
            alert(`Vous ne pouvez déposer que ${maxFiles} images maximum.`);
            return;
        }

        fileInput.files = files;
        successMessage.style.display = 'block'; // Afficher le message de succès
    });

    // Gérer le changement d'état du file input
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > maxFiles) {
            successMessage.style.display = 'none'; // Masquer le message de succès
            alert(`Vous ne pouvez sélectionner que ${maxFiles} images maximum.`);
            fileInput.value = ''; // Réinitialiser l'input si la limite est dépassée
            return;
        }

        successMessage.style.display = 'block'; // Afficher le message de succès
    });
});
