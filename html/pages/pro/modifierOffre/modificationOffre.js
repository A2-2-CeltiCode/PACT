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



// Afficher le bouton de suppression pour le premier champ de repas
const initialRemoveButton = document.querySelector('.remove-btn');
initialRemoveButton.style.display = 'inline-block';

// Gérer la logique de suppression pour le champ de repas initial
initialRemoveButton.onclick = function() {
    const repasInputs = document.querySelectorAll('.repas-input');
    if (repasInputs.length > 1) {
        initialRemoveButton.parentElement.remove();  // Supprimer le premier champ
    } else {
        alert('Vous devez avoir au moins un champ de repas.');
    }
};


function toggleLangue(show) {
    var langueDiv = document.getElementById("langue");
    if (show) {
        langueDiv.style.display = "block";
    } else {
        langueDiv.style.display = "none";
    }
}

function toggleDropdown(id) {
    var element = document.getElementById(id);
    if (element.style.display === "none" || element.style.display === "") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}