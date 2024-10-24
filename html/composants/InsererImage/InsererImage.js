document.addEventListener('DOMContentLoaded', function () {
    // Sélectionner toutes les zones de dépôt
    const dropZones = document.querySelectorAll('.drop-zone');

    dropZones.forEach(dropZone => {
        const dropZoneId = dropZone.getAttribute('data-id');
        const maxFiles = parseInt(dropZone.getAttribute('data-max-files'), 10);
        const maxFileNameLength = 20; // Longueur maximale des noms de fichiers avant troncation

        const fileInput = document.getElementById(`fileInput-${dropZoneId}`); // Input associé à la zone
        const successMessage = document.getElementById(`successMessage-${dropZoneId}`); // Message de succès associé

        // Événement pour le clic sur la zone de dépôt
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        // Empêcher le comportement par défaut
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
                successMessage.style.display = 'none';
                alert(`Vous ne pouvez déposer que ${maxFiles} images maximum.`);
                return;
            }

            fileInput.files = files;
            displayFileNames(files);
            successMessage.style.display = 'block';
        });

        // Gérer le changement d'état du file input
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > maxFiles) {
                successMessage.style.display = 'none';
                alert(`Vous ne pouvez sélectionner que ${maxFiles} images maximum.`);
                fileInput.value = '';
                return;
            }

            displayFileNames(fileInput.files);
            successMessage.style.display = 'block';
        });

        // Fonction pour afficher les noms des fichiers
        function displayFileNames(files) {
            successMessage.innerHTML = '';
            Array.from(files).forEach(file => {
                const p = document.createElement('p');
                p.textContent = truncateFileName(file.name, maxFileNameLength);
                successMessage.appendChild(p);
            });
        }

        // Fonction pour tronquer les noms de fichiers
        function truncateFileName(fileName, maxLength) {
            if (fileName.length > maxLength) {
                return fileName.substring(0, maxLength) + '...';
            }
            return fileName;
        }
    });
});
