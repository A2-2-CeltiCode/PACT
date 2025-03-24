document.addEventListener("DOMContentLoaded", () => {
    const btnModifier = document.getElementById("modifier");
    const btnChangerMotDePasse = document.querySelector(".modifier button[onclick='ouvrirPopupMotDePasse()']");
    const btnEnregistrer = document.getElementById("btnEnregistrer");
    const btnAnnuler = document.getElementById("btnAnnuler");
    const messageErreur = document.getElementById("messageErreur");
    const btnEnregistrerMdp = document.getElementById("btnEnregistrerMdp");
    const btnAnnulerMdp = document.getElementById("btnAnnulerMdp");
    const editableInputs = document.querySelectorAll("input.editable");
    const footer = document.getElementsByTagName("footer")[0];
    const tdNonModif = document.querySelectorAll("td.nonE");
    const nonEditableInputs = document.querySelectorAll("input.nonEditable");

    // Variables pour la pop-up de changement de mot de passe
    const popupMotDePasse = document.getElementById("popupMotDePasse");
    const erreurPopup = document.getElementById("erreurPopup");
    const formChangerMotDePasse = document.getElementById("formulaireMotDePasse");
    let keydownHandler;


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
            input.style.backgroundColor = "#f9f9f9";
        });
        nonEditableInputs.forEach(input => {
            input.style.backgroundColor = "#999a9b00";
        });
        
        tdNonModif.forEach(input => {
            input.style.backgroundColor = "#b3b1b150";
        })

        keydownHandler = function(event) {
            if (event.key === "Enter") {
                document.getElementById("btnEnregistrer").click();
            } else if (event.key === "Escape") {
                document.getElementById("btnAnnuler").click();
        }};

        document.addEventListener("keydown", keydownHandler);
        
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
        document.removeEventListener("keydown", keydownHandler);
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

        const email = document.querySelector("input[name='email']").value;
        const numTel = document.querySelector("input[name='numtel']").value;
        const rue = document.querySelector("input[name='rue']").value;
        const codePostal = document.querySelector("input[name='codepostal']").value;
        const ville = document.querySelector("input[name='ville']").value;
        const nom = document.querySelector("input[name='nom']").value;
        const prenom = document.querySelector("input[name='prenom']").value;

        // Validation des champs
        if (!/^(\d{2}([ .])?){4}\d{2}$/.test(numTel)) {
            erreurs.push("Le numéro de téléphone doit contenir uniquement 10 chiffres.");
        }
        if (!/^\d{5}$/.test(codePostal)) {
            erreurs.push("Le code postal doit contenir exactement 5 chiffres.");
        }
        if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,255}$/.test(email)) {
            erreurs.push("L'adresse email n'est pas valide et doit contenir au maximum 255 caractères.");
        }
        if (!/^.{1,50}$/.test(rue)) {
            erreurs.push("La rue doit contenir au maximum 50 caractères.");
        }
        if (!/^.{1,50}$/.test(nom)) {
            erreurs.push("Le nom doit contenir au maximum 50 caractères.");
        }
        if (!/^.{1,50}$/.test(prenom)) {
            erreurs.push("Le prenom doit contenir au maximum 50 caractères.");
        }
        if (!/^[A-Za-z\s-]{1,50}$/.test(ville)) {
            erreurs.push("La ville peut contenir uniquement des lettres, des espaces et des tirets.");
        }
    
        // Afficher les erreurs ou soumettre le formulaire
        if (erreurs.length > 0) {
            afficherMessageErreur(erreurs);
            window.scrollTo(0, 0);
        } else {
            document.getElementById("formulaireCompteMembre").submit();
        }
    }

    /**
     * Ouvrir la pop-up de changement de mot de passe.
     */
    function ouvrirPopupMotDePasse() {
        popupMotDePasse.style.display = "block";
        btnEnregistrerMdp.style.display = "inline-block";
        btnAnnulerMdp.style.display = "inline-block";

        keydownHandler = function(event) {
            if (event.key === "Enter") {
                document.getElementById("btnEnregistrerMdp").click();
            } else if (event.key === "Escape") {
                document.getElementById("btnAnnulerMdp").click();
        }};

        document.addEventListener("keydown", keydownHandler);
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

        document.removeEventListener("keydown", keydownHandler);
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

    let copyBtn = document.getElementById("copyButton");
    if (copyBtn.attributes["onclick"].nodeValue.length < 75) {
        copyBtn.disabled = true;
        copyBtn.classList.add("btnDisabled");
    }
});

function copyKey(key) {
    let btn = document.getElementById("copyButton");
    btn.classList.add("material-symbols-outlined");
    btn.style.backgroundColor = "var(--valide)";
    btn.children[0].textContent = "check";
    navigator.clipboard.writeText(key);
    setTimeout(() => {
        btn.classList.remove("material-symbols-outlined");
        btn.style.backgroundColor = "var(--primaire-membre)";
        btn.children[0].textContent = "copier";
    }, 3000);
}

function generateKey() {
    let url = window.location.protocol + "//" + window.location.host + "/pages/membre/consulterCompteMembre/genererCle.php";
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", url, false );
    xmlHttp.send( null )
    let generateBtn = document.getElementById("generateButton");
    let copyBtn = document.getElementById("copyButton");
    let genText = document.getElementById("genText");
    copyBtn.disabled = false;
    copyBtn.classList.remove("btnDisabled");
    genText.value = "Généré";
    generateBtn.classList.add("material-symbols-outlined");
    generateBtn.style.backgroundColor = "var(--valide)";
    generateBtn.children[0].textContent = "check";
    setTimeout(() => {
        generateBtn.classList.remove("material-symbols-outlined");
        generateBtn.style.backgroundColor = "var(--primaire-membre)";
        generateBtn.children[0].textContent = "Regénérer";
    }, 3000);
}

function deleteAccount() {
    let url = window.location.protocol + "//" + window.location.host + "/pages/membre/consulterCompteMembre/supprimerCompte.php"
    window.location.replace(url)
}

function showDeletePopup() {
    let popup = document.getElementById("deleteConfirmationPopup")
    popup.style.display = "block"

}

function hideDeletePopup() {
    let popup = document.getElementById("deleteConfirmationPopup")
    popup.style.display = "none"
}