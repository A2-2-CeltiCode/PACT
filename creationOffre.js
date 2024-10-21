document.addEventListener("DOMContentLoaded", function () {
    const selectElement = document.querySelector('select[name="typeOffre"]');
    const sections = document.querySelectorAll('.section');

    // Fonction pour masquer toutes les sections
    function hideAllSections() {
        sections.forEach(section => {
            section.style.display = 'none';
        });
    }

    // Fonction pour afficher la section correspondante
    function showSection(typeOffre) {
        hideAllSections(); // On masque toutes les sections d'abord
        const section = document.getElementById(typeOffre);
        if (section) {
            section.style.display = 'block';
        }
    }

    // Événement déclenché lorsque le type d'offre est modifié
    selectElement.addEventListener('change', function () {
        const selectedValue = this.value;
        showSection(selectedValue);
    });

    // On cache toutes les sections au début
    hideAllSections();
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('myForm').addEventListener('submit', function(event) {
        const input = document.getElementById('adressePostale').value;
        const regex = /^[0-9]+/; // Regex pour une adresse postale simple

        if (!regex.test(input)) {
            event.preventDefault(); // Empêche l'envoi du formulaire
                alert("Veuillez entrer une adresse postale valide."); // Affiche un message d'erreur
        }
    });
});
