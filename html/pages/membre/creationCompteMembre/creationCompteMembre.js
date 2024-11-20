function formValide() {
    // Validation du numéro de téléphone : uniquement des chiffres (max 10)
    var telephone = document.forms["creerComptePro"]["telephone"].value;
    if (!/^\d{10}$/.test(telephone) && telephone !== "") {
        alert("Le numéro de téléphone doit contenir uniquement 10 chiffres.");
        return false;
    }

    // Validation de l'email : doit contenir un "@" et un "."
    var email = document.forms["creerComptePro"]["email"].value;
    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
        alert("L'adresse email n'est pas valide.");
        return false;
    }

    // Validation du code postal : 5 chiffres
    var codePostal = document.forms["creerComptePro"]["codePostal"].value;
    if (!/^\d{5}$/.test(codePostal)) {
        alert("Le code postal doit contenir 5 chiffres.");
        return false;
    }

    // Validation de la ville : uniquement des lettres
    var ville = document.forms["creerComptePro"]["ville"].value;
    if (!/^[A-Za-z\s]+$/.test(ville)) {
        alert("La ville doit contenir uniquement des lettres et des espaces.");
        return false;
    }

    // Validation du mot de passe : respect des règles
    var motDePasse = document.forms["creerComptePro"]["motDePasse"].value;
    if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}/.test(motDePasse)) {
        alert("Le mot de passe doit comporter au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.");
        return false;
    }

    // Validation de la confirmation du mot de passe : doit correspondre
    var confirmMdp = document.forms["creerComptePro"]["confirmMdp"].value;
    if (motDePasse !== confirmMdp) {
        alert("Les mots de passe ne correspondent pas.");
        return false;
    }

    return true; // Si toutes les validations passent, soumettre le formulaire
}
