function activerModification() {
    // Rendre tous les champs modifiables
    document.querySelectorAll('.editable').forEach(function (element) {
        element.removeAttribute('readonly');
        element.style.backgroundColor = '#f0f0f0';
    });

    // Afficher les boutons "Enregistrer" et "Annuler"
    document.getElementById('btnEnregistrer').style.display = 'flex';
    document.getElementById('btnAnnuler').style.display = 'flex';
}

function annulerModification () {
    document.querySelectorAll('.editable').forEach(function (element) {
        element.addAttribute('readonly');
        element.style.backgroundColor = 'white';
    });

    // Cacher les boutons "Enregistrer" et "Annuler"
    document.getElementById('btnEnregistrer').style.display = 'none';
    document.getElementById('btnAnnuler').style.display = 'none';

}