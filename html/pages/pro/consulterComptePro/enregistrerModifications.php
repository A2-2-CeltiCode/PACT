<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idCompte'])) {
    header("Location: login.php");
    exit;
}

// Configuration de la base de données
$host = 'localhost';        // Remplacez par votre hôte
$dbname = 'postgres';       // Remplacez par le nom de votre base de données
$user = 'postgres';         // Remplacez par votre utilisateur
$password = '13phenix';     // Remplacez par votre mot de passe
$idCompte = 2;//$_SESSION['idCompte']; // ID de l'utilisateur connecté

try {
    // Connexion à la base de données
    $pdo = new PDO("pgsql:host=$host;port=5433;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Définir le schéma "pact" pour la session
    $pdo->exec("SET search_path TO pact");

    // Vérifier que les données ont été envoyées
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer et valider les données du formulaire
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $numtel = htmlspecialchars($_POST['numtel'], ENT_QUOTES, 'UTF-8');
        $rue = htmlspecialchars($_POST['rue'], ENT_QUOTES, 'UTF-8');
        $codepostal = filter_var($_POST['codepostal'], FILTER_VALIDATE_INT);
        $ville = htmlspecialchars($_POST['ville'], ENT_QUOTES, 'UTF-8');
        $banquerib = htmlspecialchars($_POST['banquerib'], ENT_QUOTES, 'UTF-8');

        // Vérification des données obligatoires
        if (!$email || !$numtel || !$rue || !$codepostal || !$ville || !$banquerib) {
            throw new Exception("Toutes les informations doivent être correctement renseignées.");
        }


        // Préparer et exécuter la mise à jour de _compte
        $sqlCompte = "UPDATE _compte
                    SET email = :email
                    WHERE idCompte = :idCompte";
        $stmtCompte = $pdo->prepare($sqlCompte);
        $stmtCompte->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtCompte->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmtCompte->execute();

        // Préparer et exécuter la mise à jour de _adresse
        $sqlAdresse = "UPDATE _adresse
                    SET rue = :rue, codePostal = :codepostal, ville = :ville, numTel = :numtel
                    WHERE idAdresse = (
                        SELECT idAdresse FROM _compte WHERE idCompte = :idCompte
                    )";
        $stmtAdresse = $pdo->prepare($sqlAdresse);
        $stmtAdresse->bindParam(':rue', $rue, PDO::PARAM_STR);
        $stmtAdresse->bindParam(':codepostal', $codepostal, PDO::PARAM_INT);
        $stmtAdresse->bindParam(':ville', $ville, PDO::PARAM_STR);
        $stmtAdresse->bindParam(':numtel', $numtel, PDO::PARAM_STR);
        $stmtAdresse->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmtAdresse->execute();

        // Préparer et exécuter la mise à jour de _comptePro
        $sqlComptePro = "UPDATE _comptePro
                        SET banqueRib = :banquerib
                        WHERE idCompte = :idCompte";
        $stmtComptePro = $pdo->prepare($sqlComptePro);
        $stmtComptePro->bindParam(':banquerib', $banquerib, PDO::PARAM_STR);
        $stmtComptePro->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmtComptePro->execute();



        // Retourner un message de succès
        $_SESSION['message'] = "Vos informations ont été mises à jour avec succès.";
    }
} catch (Exception $e) {
    // Retourner un message d'erreur
    $_SESSION['error'] = "Erreur lors de la mise à jour des informations : " . $e->getMessage();
}

// Recharger la page précédente
header("Location: consulterComptePro.php");
exit;