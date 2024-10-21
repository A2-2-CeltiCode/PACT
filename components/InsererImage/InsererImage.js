document.addEventListener('DOMContentLoaded', function () {
    // Paramètres dynamiques passés via les attributs de la zone de dépôt
    const dropZoneId = document.querySelector('.drop-zone').getAttribute('data-id');
    const maxFiles = parseInt(document.querySelector('.drop-zone').getAttribute('data-max-files'), 10);
    const maxFileNameLength = 20; // Longueur maximale des noms de fichiers avant troncation

    const dropZone = document.getElementById(dropZoneId);
    const fileInput = dropZone.nextElementSibling; // L'input file se trouve juste après la zone de dépôt
    const successMessage = dropZone.nextElementSibling.nextElementSibling; // Le message de succès après l'input

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
        displayFileNames(files);
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

        displayFileNames(fileInput.files);
        successMessage.style.display = 'block'; // Afficher le message de succès
    });

    // Fonction pour afficher les noms des fichiers sur des lignes séparées, avec troncation
    function displayFileNames(files) {
        successMessage.innerHTML = ''; // Réinitialiser le message de succès
        Array.from(files).forEach(file => {
            const p = document.createElement('p');
            p.textContent = truncateFileName(file.name, maxFileNameLength);
            successMessage.appendChild(p);
        });
    }

    // Fonction pour tronquer le nom du fichier si sa longueur dépasse une limite
    function truncateFileName(fileName, maxLength) {
        if (fileName.length > maxLength) {
            return fileName.substring(0, maxLength) + '...';
        }
        return fileName;
    }
});
