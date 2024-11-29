document.addEventListener("DOMContentLoaded", () => {
    const btnModifier = document.getElementById("modifier"); // document.querySelector(".modifier button[onclick='activerModification()']");
    const btnChangerMotDePasse = document.querySelector(".modifier button[onclick='ouvrirPopupMotDePasse()']");
    const btnEnregistrer = document.getElementById("btnEnregistrer");
    const btnAnnuler = document.getElementById("btnAnnuler");
    const btnEnregistrerMdp = document.getElementById("btnEnregistrerMdp");
    const btnAnnulerMdp = document.getElementById("btnAnnulerMdp");
    const editableInputs = document.querySelectorAll("input.editable");
    const messageErreur = document.getElementById("messageErreur");

    const popupMotDePasse = document.getElementById("popupMotDePasse");
    const erreurPopup = document.getElementById("erreurPopup");
    const formChangerMotDePasse = document.getElementById("formulaireMotDePasse");

    /**
     * Activer la modification des champs.
     */
    function activerModification() {
        editableInputs.forEach(input => {
            input.removeAttribute("readonly");
            input.style.backgroundColor = "#f0f0f0";
        });

        btnEnregistrer.style.display = "inline-block";
        btnAnnuler.style.display = "inline-block";
        btnModifier.disabled = true;

        effacerMessageErreur();
    }

    document.getElementById("modifier").addEventListener("click", activerModification);

    /**
     * Annuler les modifications et restaurer les valeurs originales.
     */
    function annulerModification() {
        editableInputs.forEach(input => {
            input.value = input.dataset.original;
            input.setAttribute("readonly", "readonly");
            input.style.backgroundColor = "#f9f9f9";
        });

        btnEnregistrer.style.display = "none";
        btnAnnuler.style.display = "none";
        btnModifier.disabled = false;

        effacerMessageErreur();
    }

    /**
     * Valider le formulaire principal avant soumission.
     */
    function validerFormulaire(event) {
        event.preventDefault();

        let erreurs = [];

        const numTel = document.querySelector("input[name='numtel']").value;
        const codePostal = document.querySelector("input[name='codepostal']").value;
        const email = document.querySelector("input[name='email']").value;
        const rib = document.querySelector("input[name='banquerib']").value;

        // Validation des champs
        if (!/^(\d{2}([ .])?){4}\d{2}$/.test(numTel)) {
            erreurs.push("Le numéro de téléphone doit être au format : 01.02.03.04.05, 01 02 03 04 05 ou 0102030405.");
        }
        if (!/^\d{5}$/.test(codePostal)) {
            erreurs.push("Le code postal doit contenir exactement 5 chiffres.");
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            erreurs.push("L'adresse e-mail n'est pas valide.");
        }
        if (!/^[a-zA-Z0-9]{1,34}$/.test(rib)) {
            erreurs.push("Le RIB doit contenir jusqu'à 34 caractères alphanumériques.");
        }
        

        // Afficher les erreurs ou soumettre le formulaire
        if (erreurs.length > 0) {
            afficherMessageErreur(erreurs);
            window.scrollTo(0, 0);
        } else {
            document.getElementById("formulaireComptePro").submit();
        }
    }

    /**
     * Ouvrir la pop-up de changement de mot de passe.
     */
    function ouvrirPopupMotDePasse() {
        popupMotDePasse.style.display = "block";
        btnEnregistrerMdp.style.display = "inline-block";
        btnAnnulerMdp.style.display = "inline-block";
    }

    /**
     * Fermer la pop-up de changement de mot de passe.
     */
    function fermerPopupMotDePasse() {
        popupMotDePasse.style.display = "none";
        effacerMessageErreurPopup();

        formChangerMotDePasse.reset();
    }

    /**
     * Valider les champs de changement de mot de passe.
     */
    function validerMotDePasse(event) {
        event.preventDefault();

        const ancienMdp = document.getElementById("ancienMdp").value;
        const nouveauMdp = document.getElementById("nouveauMdp").value;
        const confirmerMdp = document.getElementById("confirmerMdp").value;

        let erreurs = [];
        const regexMdp = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!ancienMdp) {
            erreurs.push("L'ancien mot de passe est requis.");
        }
        if (!regexMdp.test(nouveauMdp)) {
            erreurs.push("Le nouveau mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.");
        }
        if (nouveauMdp !== confirmerMdp) {
            erreurs.push("Les mots de passe ne correspondent pas.");
        }

        if (erreurs.length > 0) {
            afficherMessageErreurPopup(erreurs);
        } else {
            formChangerMotDePasse.submit();
        }
    }

    /**
     * Afficher un message d'erreur principal.
     */
    function afficherMessageErreur(erreurs) {
        messageErreur.innerHTML = erreurs.join("<br>");
        messageErreur.style.display = "block";
    }

    /**
     * Effacer le message d'erreur principal.
     */
    function effacerMessageErreur() {
        messageErreur.style.display = "none";
        messageErreur.innerHTML = "";
    }

    /**
     * Afficher un message d'erreur dans la pop-up.
     */
    function afficherMessageErreurPopup(erreurs) {
        erreurPopup.innerHTML = erreurs.join("<br>");
        erreurPopup.style.display = "block";
    }

    /**
     * Effacer les messages d'erreur dans la pop-up.
     */
    function effacerMessageErreurPopup() {
        erreurPopup.style.display = "none";
        erreurPopup.innerHTML = "";
    }

    // Événements pour les boutons principaux
    btnModifier.addEventListener("click", activerModification);
    btnAnnuler.addEventListener("click", annulerModification);
    btnEnregistrer.addEventListener("click", validerFormulaire);
    btnChangerMotDePasse.addEventListener("click", ouvrirPopupMotDePasse);

    // Événements pour la pop-up de mot de passe
    btnEnregistrerMdp.addEventListener("click", validerMotDePasse);
    btnAnnulerMdp.addEventListener("click", fermerPopupMotDePasse);
});