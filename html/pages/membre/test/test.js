 // Activer la modification des champs
 function activerModification() {
    document.querySelectorAll('.editable').forEach(function (element) {
        element.removeAttribute('readonly');
        element.style.backgroundColor = '#f0f0f0'; // Indiquer visuellement la modification
    });

    // Afficher les boutons "Enregistrer" et "Annuler"
    document.getElementById('btnEnregistrer').style.display = 'block';
    document.getElementById('btnAnnuler').style.display = 'block';

    // Afficher les champs de mot de passe
    document.getElementById('changerMdp').style.display = 'table-row-group';
    document.getElementById('motDePassePlaceholder').style.display = 'none';
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

    // Réinitialiser les champs de mot de passe et masquer les options de changement
    document.getElementById('ancienMdp').value = '';
    document.getElementById('nouveauMdp').value = '';
    document.getElementById('confirmerMdp').value = '';
    document.getElementById('changerMdp').style.display = 'none';
    document.getElementById('motDePassePlaceholder').style.display = 'table-row';

    // Effacer le message d'erreur s'il existe
    const messageErreur = document.getElementById('messageErreur');
    messageErreur.style.display = 'none';
    messageErreur.innerHTML = '';
}

// Valider le formulaire avant soumission
function validerFormulaire(event) {
    // Empêcher l'envoi du formulaire par défaut
    event.preventDefault();

    // Récupérer les champs du formulaire
    const nom = document.querySelector('input[name="nom"]');
    const prenom = document.querySelector('input[name="prenom"]');
    const pseudo = document.querySelector('input[name="pseudo"]');
    const email = document.querySelector('input[name="email"]');
    const numtel = document.querySelector('input[name="numtel"]');
    const rue = document.querySelector('input[name="rue"]');
    const codepostal = document.querySelector('input[name="codepostal"]');
    const ville = document.querySelector('input[name="ville"]');


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
        erreurs.push("Le numéro de téléphone doit contenir exactement 10 chiffres");
    }

    // Validation du code postal (5 chiffres)
    const regexCodePostal = /^\d{5}$/;
    if (!regexCodePostal.test(codepostal.value)) {
        erreurs.push("Le code postal doit contenir exactement 5 chiffres.");
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
        document.getElementById('formulaireCompteMembre').submit();
    }
}

function ouvrirPopupMotDePasse() {
    document.getElementById('popupMotDePasse').style.display = 'block';
}

function fermerPopupMotDePasse() {
    document.getElementById('popupMotDePasse').style.display = 'none';
    document.getElementById('ancienMdp').value = '';
    document.getElementById('nouveauMdp').value = '';
    document.getElementById('confirmerMdp').value = '';
    document.getElementById('erreurPopup').style.display = 'none';
}

function validerMotDePasse(event) {
    event.preventDefault();

    const ancienMdp = document.getElementById('ancienMdp').value;
    const nouveauMdp = document.getElementById('nouveauMdp').value;
    const confirmerMdp = document.getElementById('confirmerMdp').value;

    let erreurs = [];
    if (!ancienMdp) erreurs.push("L'ancien mot de passe est requis.");
    if (nouveauMdp.length < 8) erreurs.push("Le nouveau mot de passe doit contenir au moins 8 caractères.");
    if (nouveauMdp !== confirmerMdp) erreurs.push("Les mots de passe ne correspondent pas.");

    const messageErreur = document.getElementById('erreurPopup');
    if (erreurs.length > 0) {
        messageErreur.innerHTML = erreurs.join('<br>');
        messageErreur.style.display = 'block';
    } else {
        document.getElementById('formulaireMotDePasse').submit();
    }
}