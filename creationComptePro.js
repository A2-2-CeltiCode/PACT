// Fonction pour afficher ou masquer le champ SIREN
function afficherChampSiren() {
    var estPrive = document.getElementById("estPrive");
    var champSiren = document.getElementById("champSiren");
    if (estPrive.checked) {
        champSiren.style.display = "block"; // Afficher le champ SIREN
    } else {
        champSiren.style.display = "none"; // Masquer le champ SIREN
    }
}

function formValide() {
    // Vérification SIREN si "Entreprise privée" est cochée
    var estPrive = document.getElementById("estPrive").checked;
    var sirenInput = document.forms["creerComptePro"]["siren"].value.trim();

    if (estPrive) {
        if (sirenInput === "") {
            alert("Le numéro SIREN est requis pour une entreprise privée.");
            return false;
        }
        if (!/^\d{9}$/.test(sirenInput)) {
            alert("Le numéro SIREN doit contenir exactement 9 chiffres.");
            return false;
        }
    }

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

    // Validation du numéro de rue : uniquement des chiffres ou "bis" ou "ter"
    var numero = document.forms["creerComptePro"]["numero"].value;
    if (!/^\d+$/.test(numero) && !/^\d+(bis|ter)$/.test(numero) && numero !== "") {
    alert("Le numéro de rue doit être constitué de chiffres, possiblement suivi de 'bis' ou 'ter'.");
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

    // Validation de l'IBAN : 34 caractères maximum
    var iban = document.forms["creerComptePro"]["iban"].value;
    if (iban.length > 34) {
        alert("L'IBAN ne peut pas dépasser 34 caractères.");
        return false;
    }

    return true; // Si toutes les validations passent, soumettre le formulaire
}
