<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
try {


    $idOffre = 3;
    $idAdresse = 5;
    $idCompte = 3;
    $idFacture = 1;

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $offre = $dbh->query('SELECT * FROM pact._offre' . ' WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetch();
    $pro = $dbh->query('SELECT * FROM pact.vue_compte_pro WHERE idcompte = ' . $idCompte, PDO::FETCH_ASSOC)->fetch();

    $nomPACT = "PACT";
    $adressePACT = $dbh->query('SELECT * FROM pact._adresse' . ' WHERE idadresse = ' . $idAdresse, PDO::FETCH_ASSOC)->fetch();
    $dateDuJour = date("d-m-Y");
    $facture = $dbh->query('SELECT * FROM pact.vue_facture WHERE idfacture = ' . $idFacture, PDO::FETCH_ASSOC)->fetch();

    $semaines_actifs = $dbh->query('SELECT * FROM pact._annulationoption WHERE idoffre = ' . $idOffre, PDO::FETCH_ASSOC)->fetchAll();

    $mois_prestation = date(format: 'm', timestamp: strtotime($facture['dateprestaservices']));

    switch ($mois_prestation) {
        case "1":
            $mois_prestation = "Janvier";
            break;
        case "2":
            $mois_prestation = "Février";
            break;
        case "3":
            $mois_prestation = "Mars";
            break;
        case "4":
            $mois_prestation = "Avril";
            break;
        case "5":
            $mois_prestation = "Mai";
            break;
        case "6":
            $mois_prestation = "Juin";
            break;
        case "7":
            $mois_prestation = "Juillet";
            break;
        case "8":
            $mois_prestation = "Août";
            break;
        case "9":
            $mois_prestation = "Septembre";
            break;
        case "10":
            $mois_prestation = "Octobre";
            break;
        case "11":
            $mois_prestation = "Novembre";
            break;
        case "12":
            $mois_prestation = "Décembre";
            break;
        default:
            $mois_prestation = "Mois invalide";
            break;
    }


    $date_prestation = $mois_prestation . ' ' . date('Y', strtotime($facture['dateprestaservices']));

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
<pre><?php
//print_r($semaines_actifs) ?>
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
                <td><?php echo $nomPACT ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $adressePACT['rue'] . ', ' . $adressePACT['codepostal'] . ' ' . $adressePACT['ville'] ?>
                </td>
        </table>
        <table class="partie">
            <tr>
                <th>Professionnel</th>
                <td><?php echo $pro['denominationsociale'] . ' - ' . $pro['raisonsocialepro'] ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $pro['rue'] . ', ' . $pro['codepostal'] . ' ' . $pro['ville'] ?></td>
        </table>

        <table class="info-facture">
            <tr>
                <th>Date d'émission</th>
                <th>Numéro de facture</th>
                <th>Nom de l'offre</th>
                <th>Date de la prestation de services</th>
                <th>Date d'échéance du règlement</th>
            </tr>
            <tr>
                <td><?php echo $dateDuJour ?></td>
                <td><?php echo $facture['idfacture'] ?></td>
                <td><?php echo $offre['titre'] ?></td>
                <td><?php echo $date_prestation ?></td>
                <td><?php echo date("d-m-Y", strtotime($facture['dateecheance'])) ?></td>
            </tr>
        </table>
        <?php

        try {
            // Récupération des prix des forfaits
            $forfaits = $dbh->query('SELECT * FROM pact._forfait', PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_ASSOC);

            // Récupération des prix des options
            $options = $dbh->query('SELECT * FROM pact._option', PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_ASSOC);

            // Indexer les forfaits et options par nom
            $forfaits_indexed = [];
            foreach ($forfaits as $forfait) {
                $forfaits_indexed[$forfait['nomforfait']] = $forfait;
            }

            $options_indexed = [];
            foreach ($options as $option) {
                $options_indexed[$option['nomoption']] = $option;
            }

        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br>";
            die();
        }
        ?>
        <table class="tarifs-facture" cellspacing="0" cellpadding="0">
            <tr>
                <th>Nom du service</th>
                <th>Quantité du service</th>
                <th>Prix HT</th>
                <th>Prix TTC</th>
                <th>Total</th>
            </tr>
            <?php
            $totalHT = 0;
            $totalTTC = 0;

            foreach ($semaines_actifs as $service) {
                // Récupérer les informations de l'option
                $option = $options_indexed[$service['nomoption']] ?? ['prixht' => 0, 'prixttc' => 0];

                // Calculer le total pour cette option
                $prixHT = $option['prixht'];
                $prixTTC = $option['prixttc'];
                $quantite = $service['nbsemaines'];
                $totalOptionHT = $prixHT * $quantite;
                $totalOptionTTC = $prixTTC * $quantite;

                // Ajouter aux totaux globaux
                $totalHT += $totalOptionHT;
                $totalTTC += $totalOptionTTC;
                ?>
                <tr>
                    <td><?php echo $service['nomoption']; ?></td>
                    <td><?php echo $quantite . ' semaines'; ?></td>
                    <td><?php echo number_format($prixHT, 2, ',', ' ') . '€'; ?></td>
                    <td><?php echo number_format($prixTTC, 2, ',', ' ') . '€'; ?></td>
                    <td><?php echo number_format($totalOptionTTC, 2, ',', ' ') . '€'; ?></td>
                </tr>
            <?php } ?>
        </table>

        <table class="total">
            <tr>
                <th>Total HT</th>
                <td><?php echo number_format($totalHT, 2, ',', ' ') . '€'; ?></td>
            </tr>
            <tr>
                <th>Total TVA</th>
                <td><?php echo number_format($totalTTC - $totalHT, 2, ',', ' ') . '€'; ?></td>
            </tr>
            <tr>
                <th>Total TTC</th>
                <td><?php echo number_format($totalTTC, 2, ',', ' ') . '€'; ?></td>
            </tr>
        </table>

    </main>
</body>

</html>
<?php //lien de référence pour la facturation https://www.zervant.com/fr/modele-facture/#pid=1 ?>