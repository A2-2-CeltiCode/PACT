document.addEventListener("DOMContentLoaded", function () {
    const selectElement = document.querySelector('select[name="typeOffre"]');
    const sections = document.querySelectorAll('.section');
    const promotionSelectElement = document.querySelector('select[name="typePromotion"]');
    const datePromotionContainer = document.getElementById('datePromotionContainer');

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

    // Événement déclenché lorsque le type de promotion est modifié
    promotionSelectElement.addEventListener('change', function () {
        const selectedValue = this.value;
        if (selectedValue === 'Aucune') {
            datePromotionContainer.style.display = 'none';
        } else {
            datePromotionContainer.style.display = 'block';
        }
    });
});

function addRepas() {
    // Trouver le conteneur de repas
    const repasContainer = document.getElementById('repasContainer');

    // Créer un nouvel élément pour le repas
    const newRepasDiv = document.createElement('div');
    newRepasDiv.className = 'repas-input';

    // Créer un champ de saisie
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'repas[]';
    input.placeholder = 'Nom du repas';

    // Créer le bouton de suppression
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.textContent = 'Supprimer';
    removeButton.onclick = function() {
        newRepasDiv.remove();  // Supprimer le div contenant le repas
    };

    // Ajouter le champ de saisie et le bouton de suppression au nouvel élément
    newRepasDiv.appendChild(input);
    newRepasDiv.appendChild(removeButton);

    // Ajouter le nouvel élément au conteneur
    repasContainer.appendChild(newRepasDiv);
}

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

// Gérer le champ de repas initial pour ne pas être supprimé pendant l'écriture
document.querySelector('input[name="repas[]"]').addEventListener('focus', function() {
    initialRemoveButton.style.display = 'none';  // Cacher le bouton de suppression
});

// Afficher le bouton de suppression uniquement si le champ n'est pas en cours d'écriture
document.querySelectorAll('.repas-input input').forEach(input => {
    input.addEventListener('focus', function() {
        const removeButtons = document.querySelectorAll('.remove-btn');
        removeButtons.forEach(button => button.style.display = 'none');  // Cacher tous les boutons
    });

    input.addEventListener('blur', function() {
        const removeButtons = document.querySelectorAll('.remove-btn');
        removeButtons.forEach(button => button.style.display = 'inline-block');  // Afficher tous les boutons
        initialRemoveButton.style.display = 'inline-block'; // Toujours afficher le bouton de suppression du premier champ
    });
});

function toggleLangue(show) {
    var langueDiv = document.getElementById("langue");
    if (show) {
        langueDiv.style.display = "block";
    } else {
        langueDiv.style.display = "none";
    }
}

function toggleDropdown(dropdownId) {
    var dropdown = document.getElementById(dropdownId);
    dropdown.classList.toggle("show");
}

function validateForm() {
    const durepromotion = document.getElementById("durepromotion").value;
    if (durepromotion > 4) {
        alert("La durée de la promotion ne doit pas dépasser 4.");
        return false;
    }
    return true;
}