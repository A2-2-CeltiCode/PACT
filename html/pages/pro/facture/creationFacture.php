<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $facture = $dbh->query("SELECT * FROM pact.vue_facture WHERE idfacture = 1", PDO::FETCH_ASSOC)->fetch();
    $offre = $dbh->query('SELECT * FROM pact._offre' . ' WHERE idoffre = 1', PDO::FETCH_ASSOC)->fetch();
    $client = $dbh->query("SELECT * FROM pact.vue_compte_membre WHERE idcompte = 1", PDO::FETCH_ASSOC)->fetch();
    $pro = $dbh->query("SELECT * FROM pact.vue_compte_pro WHERE idcompte = 2", PDO::FETCH_ASSOC)->fetch();

    $dateDuJour = date("Y-m-d");


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
<pre><?php /*
    <?php print_r($facture)?>
    <?php print_r($offre)?>
    <?php print_r($client)?>
    <?php print_r($pro)?>
    */
    ?>
</pre>

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
                <td><?php echo $pro['rue'] .', '. $pro['codepostal'] .' '. $pro['ville'] ?></td>
        </table>
        <table class="partie">
            <tr>
                <th>Client</th>
                <td>Nom du Client</td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $client['rue'] .', '. $client['codepostal'] .' '. $client['ville'] ?></td>
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
                <td><?php echo $dateDuJour?></td>
                <td><?php echo $facture['idfacture']?></td>
                <td><?php echo $offre['titre']?></td>
                <td><?php echo $facture['dateprestaservices']?></td>
                <td><?php echo $facture['dateecheance']?></td>
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
                <td><?php echo $offre['nomoption'] ?></td>
                <td>4 caca</td>
                <td>15€</td>
                <td>18€</td>
                <td>2€</td>
                <td>5€</td>
            </tr>
            <tr>
                <td><?php echo $offre['nomforfait']?></td>
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