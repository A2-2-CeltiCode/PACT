  // Fonction de validation des données avant soumission du formulaire
  function validerFormulaire(event) {
    // Empêcher l'envoi du formulaire par défaut
    event.preventDefault();

    // Récupérer les champs du formulaire
    const email = document.querySelector('input[name="email"]');
    const numtel = document.querySelector('input[name="numtel"]');
    const rue = document.querySelector('input[name="rue"]');
    const codepostal = document.querySelector('input[name="codepostal"]');
    const ville = document.querySelector('input[name="ville"]');
    const banquerib = document.querySelector('input[name="banquerib"]');

    // Initialiser les messages d'erreur
    let erreurs = [];

    // Validation de l'email
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email.value)) {
        erreurs.push("L'email n'est pas valide.");
    }

    // Validation du numéro de téléphone (exemple : 01 02 03 04 05)
    const regexNumTel = /^(\d{2} ){4}\d{2}$/;
    if (!regexNumTel.test(numtel.value)) {
        erreurs.push("Le numéro de téléphone doit être au format : 01 02 03 04 05.");
    }

    // Validation du code postal (5 chiffres)
    const regexCodePostal = /^\d{5}$/;
    if (!regexCodePostal.test(codepostal.value)) {
        erreurs.push("Le code postal doit contenir exactement 5 chiffres.");
    }

    // Validation du RIB (jusqu'à 34 caractères alphanumériques)
    const regexRIB = /^[a-zA-Z0-9]{1,34}$/;
    if (!regexRIB.test(banquerib.value)) {
        erreurs.push("Le RIB doit contenir jusqu'à 34 caractères alphanumériques.");
    }

    // Validation des champs obligatoires (non vides)
    if (!rue.value.trim() || !ville.value.trim() || !banquerib.value.trim()) {
        erreurs.push("Tous les champs obligatoires doivent être remplis.");
    }

    // Afficher les erreurs ou soumettre le formulaire
    const messageErreur = document.getElementById('messageErreur');
    if (erreurs.length > 0) {
        messageErreur.innerHTML = erreurs.join('<br>');
        messageErreur.style.display = 'block';
        // Ramener en haut de la page pour voir les erreurs
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        // Soumettre le formulaire si tout est valide
        document.getElementById('formulaireComptePro').submit();
    }
}

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

    const messageErreur = document.getElementById('messageErreur');
    messageErreur.style.display = 'none';
    messageErreur.innerHTML = '';
}