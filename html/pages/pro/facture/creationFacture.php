<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $stmt = $dbh->prepare("SELECT * FROM pact._facture WHERE idfacture = 1");
    $stmt->execute();
    $offres = $stmt->fetchAll();


} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./creationFacture.css">
    <link rel="stylesheet" href="../../../ui.css">
    <title>Facturation</title>
</head>

<header>
    <h1>Facture</h1>
    <img class="logo" src="/ressources/icone/logo.svg" alt="Logo PACT">
</header>

<body>
    <main>
        <table class="partie">
            <tr>
                <th>Vendeur</th>
                <td>Mon Entreprise</td>
            </tr>
            <tr>
                <td></td>
                <td>22 Avenue des Martins 4450 Ville</td>
        </table>
        <table class="partie">
            <tr>
                <th>Client</th>
                <td>Nom du Client</td>
            </tr>
            <tr>
                <td></td>
                <td>55 Avenue des Clients</td>
        </table>

        <table class="info-facture">
            <tr>
                <th>Date d'émission</th>
                <th>Numéro de facture</th>
                <th>Désignation de l'offre</th>
                <th>Date de la prestation de services (mois concerné)</th>
                <th>Date d'échéance du règlement</th>
            </tr>
            <tr>
                <td>21.05.2000</td>
                <td>36</td>
                <td>L'offre de Caca</td>
                <td>40.06.0000</td>
                <td>4 jours</td>
            </tr>
        </table>
        <table class="tarifs-facture" cellspacing="0" cellpadding="0">
            <tr>
                <th>Nom du service</th>
                <th>Quantité du service</th>
                <th>Abonnement HT</th>
                <th>Abonnement TTC</th>
                <th>Option activée HT</th>
                <th>Option activée TTC</th>
            </tr>
            <tr>
                <td>Service de caca</td>
                <td>4 caca</td>
                <td>15€</td>
                <td>18€</td>
                <td>2€</td>
                <td>5€</td>
            </tr>
            <tr>
                <td>Service de caca</td>
                <td>4 caca</td>
                <td>15€</td>
                <td>18€</td>
                <td>2€</td>
                <td>5€</td>
            </tr>
            <tr>
                <td>Service de caca</td>
                <td>4 caca</td>
                <td>15€</td>
                <td>18€</td>
                <td>2€</td>
                <td>5€</td>
            </tr>
            <tr>
                <td>Service de caca</td>
                <td>4 caca</td>
                <td>15€</td>
                <td>18€</td>
                <td>2€</td>
                <td>5€</td>
            </tr>
        </table>
        <table class="total">
        <tr>
                <th>Total HT</th>
                <td>100€</td>
            </tr>            <tr>
                <th>Total TVA</th>
                <td>20€</td>
            </tr>
            <tr>
                <th>Total TTC</t>
                <td>120€</td>
            </tr>
        </table>
    </main>
</body>

</html>
<?php //lien de référence pour la facturation https://www.zervant.com/fr/modele-facture/#pid=1 ?>