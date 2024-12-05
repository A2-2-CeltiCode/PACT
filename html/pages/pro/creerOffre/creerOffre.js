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
function getSelectedCheckboxes() {
    const checkboxes = document.querySelectorAll('.checkbox-select input[type="checkbox"]:checked');
    const selectedValues = [];
    checkboxes.forEach(checkbox => {
        selectedValues.push(checkbox.value);
    });
    return selectedValues;
}

function displaySelectedValues() {
    const selectedValues = getSelectedCheckboxes();
    const displayDivs = document.querySelectorAll('.selected-values');
    displayDivs.forEach(displayDiv => {
        displayDiv.innerHTML = ''; // Clear previous values
        selectedValues.forEach(value => {
            const valueDiv = document.createElement('div');
            valueDiv.textContent = value;
            displayDiv.appendChild(valueDiv);
        });
    });
}

function resetCheckboxes() {
    const checkboxes = document.querySelectorAll('.checkbox-select input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    displaySelectedValues(); // Mettre à jour l'affichage après réinitialisation
}

// Mettre à jour les valeurs affichées lorsque les cases à cocher sont modifiées
document.querySelectorAll('.checkbox-select input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', displaySelectedValues);
});

// Réinitialiser les cases à cocher lorsque le select typeOffre change
document.getElementById('typeOffre').addEventListener('change', resetCheckboxes);

// Afficher les valeurs sélectionnées au chargement de la page
document.addEventListener('DOMContentLoaded', displaySelectedValues);
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
