  // Activer la modification des champs
  function activerModification() {
    document.querySelectorAll('.editable').forEach(function (element) {
        element.removeAttribute('readonly');
        element.style.backgroundColor = '#f0f0f0'; // Indiquer visuellement la modification
    });

    // Afficher les boutons "Enregistrer" et "Annuler"
    document.getElementById('btnEnregistrer').style.display = 'block';
    document.getElementById('btnAnnuler').style.display = 'block';
}

// Annuler les modifications et désactiver les champs
function annulerModification() {
    document.querySelectorAll('.editable').forEach(function (element) {
        // Restaurer la valeur originale
        element.value = element.getAttribute('data-original');
        // Désactiver le champ
        element.setAttribute('readonly', true);
        element.style.backgroundColor = '#f9f9f9'; // Retour au style initial
    });

    // Masquer les boutons "Enregistrer" et "Annuler"
    document.getElementById('btnEnregistrer').style.display = 'none';
    document.getElementById('btnAnnuler').style.display = 'none';
}