// Fonction pour activer la modification des champs
function activerModification() {
    // Rendre tous les champs modifiables
    document.querySelectorAll('.editable').forEach(function (element) {
        element.removeAttribute('readonly');
        element.style.backgroundColor = '#f0f0f0'; // Indiquer visuellement que le champ est éditable
    });

    // Afficher le bouton "Enregistrer"
    document.getElementById('btnEnregistrer').style.display = 'block';
}