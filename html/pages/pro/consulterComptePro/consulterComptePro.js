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

    // Initialiser les messages d'erreur
    let erreurs = [];

    // Validation des champs de mot de passe
    const ancienMdp = document.getElementById('ancienMdp').value;
    const nouveauMdp = document.getElementById('nouveauMdp').value;
    const confirmerMdp = document.getElementById('confirmerMdp').value;

    if (nouveauMdp || confirmerMdp) {
        if (nouveauMdp.length < 8) {
            erreurs.push("Le nouveau mot de passe doit contenir au moins 8 caractères.");
        }
        if (nouveauMdp !== confirmerMdp) {
            erreurs.push("La confirmation du mot de passe ne correspond pas.");
        }
    }

    // Afficher les erreurs ou soumettre le formulaire
    const messageErreur = document.getElementById('messageErreur');
    if (erreurs.length > 0) {
        messageErreur.innerHTML = erreurs.join('<br>');
        messageErreur.style.display = 'block';
        // Ramener en haut de la page pour voir les erreurs
        window.scrollTo(0, 0);
    } else {
        // Soumettre le formulaire si tout est valide
        document.getElementById('formulaireComptePro').submit();
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