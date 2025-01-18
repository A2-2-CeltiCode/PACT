// Fonction pour afficher ou masquer un élément spécifique (langueDiv ici)
function toggleLangue(show) {
    var langueDiv = document.getElementById("langue"); 
    if (show) {
        langueDiv.style.display = "block"; 
    } else {
        langueDiv.style.display = "none"; 
    }
}



// Fonction pour récupérer les valeurs des cases à cocher qui sont actuellement sélectionnées
function getSelectedCheckboxes() {
    
    const checkboxes = document.querySelectorAll('.checkbox-select input[type="checkbox"]:checked');
    const selectedValues = []; 
    checkboxes.forEach(checkbox => {
        selectedValues.push(checkbox.value); 
    });
    return selectedValues; 
}

// Fonction pour afficher les valeurs sélectionnées dans les divs avec la classe "selected-values"
function displaySelectedValues() {
    const selectedValues = getSelectedCheckboxes(); 
    const displayDivs = document.querySelectorAll('.selected-values'); 
    displayDivs.forEach(displayDiv => {
        displayDiv.innerHTML = ''; 
        selectedValues.forEach(value => {
            const valueDiv = document.createElement('div'); 
            valueDiv.textContent = value; 
            displayDiv.appendChild(valueDiv); 
        });
    });
}

// Fonction pour réinitialiser toutes les cases à cocher et mettre à jour l'affichage
function resetCheckboxes() {
    const checkboxes = document.querySelectorAll('.checkbox-select input[type="checkbox"]'); 
    checkboxes.forEach(checkbox => {
        checkbox.checked = false; 
    });
    displaySelectedValues(); 
}

// Ajoute un gestionnaire d'événement "change" sur chaque case à cocher
document.querySelectorAll('.checkbox-select input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', displaySelectedValues); // Met à jour l'affichage en cas de modification
});

// Affiche les valeurs sélectionnées dès le chargement de la page
document.addEventListener('DOMContentLoaded', (event) => {
    displaySelectedValues(); // Met à jour l'affichage dès que la page est complètement chargée
});
