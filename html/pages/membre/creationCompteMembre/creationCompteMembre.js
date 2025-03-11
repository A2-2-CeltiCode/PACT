function formValide() {
    // Validation du nom (max 50)
    var nom = document.forms["creerCompteMembre"]["nom"].value;
    if (!/^.{1,50}$/.test(nom) && nom !== "") {
        alert("Le nom doit contenir au maximum 50 caractères.");    
        return false;
    }

    // Validation du prenom (max 50)
    var prenom = document.forms["creerCompteMembre"]["prenom"].value;
    if (!/^.{1,50}$/.test(prenom) && prenom !== "") {
        alert("Le prenom doit contenir au maximum 50 caractères.");    
        return false;
    }

    // Validation du pseudo (max 255)
    var pseudo = document.forms["creerCompteMembre"]["pseudo"].value;
    if (!/^.{1,255}$/.test(pseudo) && pseudo !== "") {
        alert("Le pseudo doit contenir au maximum 255 caractères.");    
        return false;
    }

    // Validation de l'email : doit contenir un "@" et un "."
    var email = document.forms["creerCompteMembre"]["email"].value;
    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,255}$/.test(email)) {
        alert("L'adresse email n'est pas valide et doit contenir au maximum 255 caractères.");
        return false;
    }

    // Validation du numéro de téléphone : uniquement des chiffres (max 10)
    var telephone = document.forms["creerCompteMembre"]["telephone"].value;
    if (!/^\d{10}$/.test(telephone) && telephone !== "") {
        alert("Le numéro de téléphone doit contenir uniquement 10 chiffres.");
        return false;
    }

    

    // Validation du code postal : 5 chiffres
    var codePostal = document.forms["creerCompteMembre"]["codePostal"].value;
    if (!/^\d{5}$/.test(codePostal)) {
        alert("Le code postal doit contenir 5 chiffres.");
        return false;
    }

    // Validation de la ville : uniquement des lettres
    var ville = document.forms["creerCompteMembre"]["ville"].value;
    if (!/^[A-Za-z\s]{1,50}$/.test(ville)) {
        alert("La ville doit contenir uniquement des lettres et des espaces.");
        return false;
    }

    // Validation du mot de passe : respect des règles
    var motDePasse = document.forms["creerCompteMembre"]["motDePasse"].value;
    if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}/.test(motDePasse)) {
        alert("Le mot de passe doit comporter au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.");
        return false;
    }

    // Validation de la confirmation du mot de passe : doit correspondre
    var confirmMdp = document.forms["creerCompteMembre"]["confirmMdp"].value;
    if (motDePasse !== confirmMdp) {
        alert("Les mots de passe ne correspondent pas.");
        return false;
    }

    return true; // Si toutes les validations passent, soumettre le formulaire
}
