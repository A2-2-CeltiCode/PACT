document.addEventListener("DOMContentLoaded", () => {
    const btnModifier = document.querySelector(".modifier button[onclick='activerModification()']");
    const btnChangerMotDePasse = document.querySelector(".modifier button[onclick='ouvrirPopupMotDePasse()']");
    const btnEnregistrer = document.getElementById("btnEnregistrer");
    const btnAnnuler = document.getElementById("btnAnnuler");
    const editableInputs = document.querySelectorAll("input.editable");
    const messageErreur = document.getElementById("messageErreur");
    const btnEnregistrerMdp = document.getElementById("btnMdpEnregistrer");
    const btnAnnulerMdp = document.getElementById("btnMdpAnnuler");
    const footer = document.getElementsByTagName("footer")[0];

    // Variables pour la pop-up de changement de mot de passe
    const popupMotDePasse = document.getElementById("popupMotDePasse");
    const erreurPopup = document.getElementById("erreurPopup");
    const formChangerMotDePasse = document.getElementById("formulaireMotDePasse");


    // Rouvrir la pop-up si une erreur est détectée (via PHP)
    if (erreurPopup && erreurPopup.textContent.trim() !== "") {
        popupMotDePasse.style.display = "block";
    }
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
        footer.classList.remove('footer-fixed');
        footer.classList.add('footer-relative');

        // Effacer les messages d'erreur s'ils existent
        effacerMessageErreur();
    }

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
        footer.classList.remove('footer-relative');
        footer.classList.add('footer-fixed');
    

        // Effacer les messages d'erreur s'ils existent
        effacerMessageErreur();
    }

    /**
     * Valider le formulaire principal avant soumission.
     */
    function validerFormulaire(event) {
        event.preventDefault();
        let erreurs = [];
    
        const champs = {
            nom: {
                element: document.querySelector('input[name="nom"]'),
                regex: /^[A-Za-zÀ-ÖØ-öø-ÿ]+(?:[-\s][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/,
                message: "Le champ 'Nom' contient des caractères invalides.",
            },
            prenom: {
                element: document.querySelector('input[name="prenom"]'),
                regex: /^[A-Za-zÀ-ÖØ-öø-ÿ]+(?:[-\s][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/,
                message: "Le champ 'Prénom' contient des caractères invalides.",
            },
            email: {
                element: document.querySelector('input[name="email"]'),
                regex: /^[^\s@]+@[^\s@]+.[^\s@]+$/,
                message: "L'adresse e-mail n'est pas valide.",
            },
            numtel: {
                element: document.querySelector('input[name="numtel"]'),
                regex: /^\d{2}([ .]?\d{2}){4}$/,
                message: "Le numéro de téléphone doit contenir 10 chiffres.",
            },
            codepostal: {
                element: document.querySelector('input[name="codepostal"]'),
                regex: /^\d{5}$/,
                message: "Le code postal doit contenir exactement 5 chiffres.",
            },
        };
    
        for (const champ in champs) {
            const { element, regex, message } = champs[champ];
            if (!regex.test(element.value)) {
                erreurs.push(message);
            }
        }
    
        if (erreurs.length > 0) {
            afficherMessageErreur(erreurs);
        } else {
            document.getElementById("formulaireCompteMembre").submit();
        }
    }

    /**
     * Ouvrir la pop-up de changement de mot de passe.
     */
    function ouvrirPopupMotDePasse() {
        popupMotDePasse.style.display = "block";
    }

    /**
     * Fermer la pop-up de changement de mot de passe.
     */
    function fermerPopupMotDePasse() {
        const popupMotDePasse = document.getElementById("popupMotDePasse");
        popupMotDePasse.style.display = "none";
        const erreurPopup = document.getElementById("erreurPopup");
        erreurPopup.style.display = "none";
        erreurPopup.innerHTML = "";
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
        if (messageErreur) {
            messageErreur.style.display = "none";
            messageErreur.innerHTML = "";
        }
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

    // Écouteurs d'événements
    btnModifier.addEventListener("click", activerModification);
    btnAnnuler.addEventListener("click", annulerModification);
    btnEnregistrer.addEventListener("click", validerFormulaire);
    btnChangerMotDePasse.addEventListener("click", ouvrirPopupMotDePasse);
    // Événements pour la pop-up de mot de passe
    btnEnregistrerMdp.addEventListener("click", validerMotDePasse);
    btnAnnulerMdp.addEventListener("click", fermerPopupMotDePasse);

    // Boutons de la pop-up
    document
        .querySelector("#popupMotDePasse button[onclick='validerMotDePasse(event)']")
        .addEventListener("click", validerMotDePasse);
    document
        .querySelector("#popupMotDePasse button[onclick='fermerPopupMotDePasse()']")
        .addEventListener("click", fermerPopupMotDePasse);
});