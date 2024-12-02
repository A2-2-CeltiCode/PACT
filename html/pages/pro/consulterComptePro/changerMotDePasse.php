<?php
// Démarrer la session
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";

$idCompte = $_SESSION['idCompte']; // ID de l'utilisateur connecté


try {
    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $ancienMdp = $_POST['ancienMdp'] ?? '';
        $nouveauMdp = $_POST['nouveauMdp'] ?? '';
        $confirmerMdp = $_POST['confirmerMdp'] ?? '';

        // Validation des champs
        if (empty($ancienMdp) || empty($nouveauMdp) || empty($confirmerMdp)) {
            throw new Exception("Tous les champs sont requis.");
        }

        // Vérification des critères du nouveau mot de passe
        $regexMdp = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($regexMdp, $nouveauMdp)) {
            throw new Exception("Le nouveau mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.");
        }

        if ($nouveauMdp !== $confirmerMdp) {
            throw new Exception("Le nouveau mot de passe et sa confirmation ne correspondent pas.");
        }

        // Connexion à la base de données
        $pdo = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("SET search_path TO pact");

        // Vérifier l'ancien mot de passe
        $ancienMdpHash = hash('sha256', $ancienMdp);
        $sql = "SELECT mdp FROM _compte WHERE idCompte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || $result['mdp'] !== $ancienMdpHash) {
            $_SESSION['error'] = "L'ancien mot de passe est incorrect.";
            header("Location: consulterComptePro.php");
            exit;
        }

        // Mettre à jour le mot de passe
        $nouveauMdpHash = hash('sha256', $nouveauMdp);
        $sqlUpdate = "UPDATE _compte SET mdp = :nouveauMdp WHERE idCompte = :idCompte";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':nouveauMdp', $nouveauMdpHash, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmtUpdate->execute();

        // Enregistrer un message de succès dans la session
        $_SESSION['message'] = "Le mot de passe a été changé avec succès.";
        header("Location: consulterComptePro.php");
        exit;
    }
} catch (Exception $e) {
    // Enregistrer les erreurs dans la session
    $_SESSION['error'] = $e->getMessage();
    header("Location: consulterComptePro.php");
    exit;
}