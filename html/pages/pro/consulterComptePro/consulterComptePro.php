<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idCompte'])) {
    header("Location: login.php"); // Redirige vers une page de connexion si l'utilisateur n'est pas connecté
    exit;
}

// Configuration de la base de données
$host = 'localhost';       // Remplacez par votre hôte
$dbname = 'postgres'; // Remplacez par le nom de votre base de données
$user = 'postgres'; // Remplacez par votre utilisateur
$password = '13phenix';    // Remplacez par votre mot de passe

// Initialisation des variables
$message = "";
$userInfo = [];
$idCompte = $_SESSION['idCompte']; // Récupération de l'ID depuis la session

try {
    // Connexion à la base de données
    $pdo = new PDO("pgsql:host=$host;port=5433;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les informations d'un compte professionnel privé
    $sql = "SELECT idCompte, mdp, email, numTel, denominationSociale, 
                   raisonSocialePro, banqueRib, numSiren
            FROM vue_compte_pro_prive
            WHERE idCompte = :idCompte";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
    $stmt->execute();

    // Vérification des résultats
    if ($stmt->rowCount() > 0) {
        $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "Aucune information trouvée pour cet utilisateur.";
    }
} catch (PDOException $e) {
    // Gestion des erreurs
    $message = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Professionnel Privé</title>
</head>
<body>
    <h1>Vos informations professionnelles</h1>

    <!-- Message d'erreur ou confirmation -->
    <?php if ($message): ?>
        <p style="color: red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Affichage des informations si disponibles -->
    <?php if (!empty($userInfo)): ?>
        <table border="1">
            <tr>
                <th>ID Compte</th>
                <td><?= htmlspecialchars($userInfo['idCompte']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($userInfo['email']) ?></td>
            </tr>
            <tr>
                <th>Numéro de téléphone</th>
                <td><?= htmlspecialchars($userInfo['numTel']) ?></td>
            </tr>
            <tr>
                <th>Dénomination Sociale</th>
                <td><?= htmlspecialchars($userInfo['denominationSociale']) ?></td>
            </tr>
            <tr>
                <th>Raison Sociale</th>
                <td><?= htmlspecialchars($userInfo['raisonSocialePro']) ?></td>
            </tr>
            <tr>
                <th>Banque (RIB)</th>
                <td><?= htmlspecialchars($userInfo['banqueRib']) ?></td>
            </tr>
            <tr>
                <th>Numéro Siren</th>
                <td><?= htmlspecialchars($userInfo['numSiren']) ?></td>
            </tr>
        </table>
    <?php endif; ?>
</body>
</html>