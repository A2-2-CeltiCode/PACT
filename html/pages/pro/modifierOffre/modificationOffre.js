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

// Afficher les valeurs sélectionnées au chargement de la page
document.addEventListener('DOMContentLoaded', (event) => {
    displaySelectedValues();
});



