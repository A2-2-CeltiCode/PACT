// Fonction pour afficher ou masquer le champ SIREN
function toggleSirenField() {
    var checkbox = document.getElementById("estPrive");
    var sirenField = document.getElementById("champSiren");
    if (checkbox.checked) {
        sirenField.style.display = "block"; // Afficher le champ SIREN
    } else {
        sirenField.style.display = "none"; // Masquer le champ SIREN
    }
}
