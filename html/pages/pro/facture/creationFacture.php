<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";

function getMoisPrestation($mois)
{
    $moisMapping = [
        "1" => "Janvier",
        "2" => "Février",
        "3" => "Mars",
        "4" => "Avril",
        "5" => "Mai",
        "6" => "Juin",
        "7" => "Juillet",
        "8" => "Août",
        "9" => "Septembre",
        "10" => "Octobre",
        "11" => "Novembre",
        "12" => "Décembre"
    ];
    return $moisMapping[$mois] ?? "Mois invalide";
}


function calculerJoursRestants($jourDebut, $nbjours)
{
    $dateDebut = new DateTime($jourDebut);
    $jourCourant = (int) $dateDebut->format('d');
    $mois = (int) $dateDebut->format('m');
    $annee = (int) $dateDebut->format('Y');

    $joursDansMois = 0;

    switch ($mois) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            $joursDansMois = 31;
            break;
        case 4:
        case 6:
        case 9:
        case 11:
            $joursDansMois = 30;
            break;
        case 2:
            $joursDansMois = ($annee % 4 === 0 && ($annee % 100 !== 0 || $annee % 400 === 0)) ? 29 : 28;
            break;
        default:
            throw new Exception("Mois invalide : $mois");
    }

    $joursRestantMois = $joursDansMois - $jourCourant + 1;
    return min($nbjours, $joursRestantMois);
}

try {
    $idOffre = 1;
    $idAdresse = 5;
    $idCompte = 3;
    $idFacture = 2;

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $offre = $dbh->prepare('SELECT * FROM pact._offre WHERE idoffre = :idOffre');
    $offre->execute(['idOffre' => $idOffre]);
    $offre = $offre->fetch(PDO::FETCH_ASSOC);

    $pro = $dbh->prepare('SELECT * FROM pact.vue_compte_pro WHERE idcompte = :idCompte');
    $pro->execute(['idCompte' => $idCompte]);
    $pro = $pro->fetch(PDO::FETCH_ASSOC);

    $adressePACT = $dbh->prepare('SELECT * FROM pact._adresse WHERE idadresse = :idAdresse');
    $adressePACT->execute(['idAdresse' => $idAdresse]);
    $adressePACT = $adressePACT->fetch(PDO::FETCH_ASSOC);

    $facture = $dbh->prepare('SELECT * FROM pact._facture WHERE idfacture = :idFacture');
    $facture->execute(['idFacture' => $idFacture]);
    $facture = $facture->fetch(PDO::FETCH_ASSOC);

    $semainesActifs = $dbh->prepare('SELECT * FROM pact._annulationoption WHERE idoffre = :idOffre');
    $semainesActifs->execute(['idOffre' => $idOffre]);
    $semainesActifs = $semainesActifs->fetchAll(PDO::FETCH_ASSOC);

    $moisPrestation = getMoisPrestation(date('m', strtotime($facture['dateprestaservices'])));
    $datePrestation = $moisPrestation . ' ' . date('Y', strtotime($facture['dateprestaservices']));
    $dateDuJour = date("d-m-Y");

    $forfaitQuery = $dbh->prepare('SELECT * FROM pact._offre JOIN pact._forfait USING(nomforfait) WHERE idoffre = :idOffre');
    $forfaitQuery->execute(['idOffre' => $idOffre]);
    $forfait = $forfaitQuery->fetchAll(PDO::FETCH_ASSOC);

    $optionsFiltrees = array_filter($semainesActifs, function ($option) use ($facture) {
        $moisPrestationNum = date('m', strtotime($facture['dateprestaservices']));
        $anneePrestation = date('Y', strtotime($facture['dateprestaservices']));
        $moisOption = date('m', strtotime($option['debutoption']));
        $anneeOption = date('Y', strtotime($option['debutoption']));
        return $moisOption == $moisPrestationNum && $anneeOption == $anneePrestation && !$option['estannulee'];
    });
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
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

<body>
    <header>
        <h1>Facture</h1>
        <img class="logo" src="/ressources/icone/logo.svg" alt="Logo PACT">
    </header>
    <main>
        <table class="partie">
            <tr>
                <th>Vendeur</th>
                <td>PACT</td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo "{$adressePACT['rue']}, {$adressePACT['codepostal']} {$adressePACT['ville']}"; ?></td>
            </tr>
        </table>
        <table class="partie">
            <tr>
                <th>Professionnel</th>
                <td><?php echo "{$pro['denominationsociale']} - {$pro['raisonsocialepro']}"; ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo "{$pro['rue']}, {$pro['codepostal']} {$pro['ville']}"; ?></td>
            </tr>
        </table>
        <table class="info-facture">
            <tr>
                <th>Date d'émission</th>
                <th>Numéro de facture</th>
                <th>Nom de l'offre</th>
                <th>Date de prestation</th>
                <th>Date d'échéance</th>
            </tr>
            <tr>
                <td><?php echo $dateDuJour; ?></td>
                <td><?php echo $facture['idfacture']; ?></td>
                <td><?php echo $offre['titre']; ?></td>
                <td><?php echo $datePrestation; ?></td>
                <td><?php echo date("d-m-Y", strtotime($facture['dateecheance'])); ?></td>
            </tr>
        </table>
        <table class="tarifs-facture">
            <tr>
                <th>Nom du service</th>
                <th>Quantité</th>
                <th>Prix HT unitaire</th>
                <th>Prix TTC unitaire</th>
                <th>Total TTC</th>
            </tr>
            <?php foreach ($optionsFiltrees as $option): ?>
                <?php
                $prixOptionHT = $dbh->prepare('SELECT prixht FROM pact._option WHERE nomoption = :nomOption');
                $prixOptionHT->execute(['nomOption' => $option['nomoption']]);
                $priOptionxHT = $prixOptionHT->fetchColumn();

                $prixOptionTTC = $dbh->prepare('SELECT prixttc FROM pact._option WHERE nomoption = :nomOption');
                $prixOptionTTC->execute(['nomOption' => $option['nomoption']]);
                $prixOptionTTC = $prixOptionTTC->fetchColumn();

                $prixOptionTotal = $prixOptionTTC * $option['nbsemaines'];
                ?>
                <tr>
                    <td><?php echo $option['nomoption']; ?></td>
                    <td><?php echo "{$option['nbsemaines']} semaines"; ?></td>
                    <td><?php echo "{$priOptionxHT}€" ?></td>
                    <td><?php echo "{$prixOptionTTC}€" ?></td>
                    <td><strong><?php echo "{$prixOptionTotal}€" ?></strong></td>
                </tr>
            <?php endforeach;
            $queryHistorique = $dbh->prepare('SELECT * FROM pact._historiqueenligne WHERE idoffre = :idOffre');
            $queryHistorique->execute(['idOffre' => $idOffre]);
            $historique = $queryHistorique->fetch(PDO::FETCH_ASSOC);

            $joursRestants = calculerJoursRestants($historique['jourdebutnbjours'], $historique['nbjours']);
            $prixForfaitHT = $forfait[0]['prixht'];
            $prixForfaitTTC = $forfait[0]['prixttc'];
            $prixForfaitTotal = $joursRestants * $forfait[0]['prixttc'];
                ?>

            <tr>
                <td><?php echo $forfait[0]['nomforfait']; ?></td>
                <td><?php echo $joursRestants . " jours"; ?></td>
                <td><?php echo "{$prixForfaitHT}€" ?></td>
                <td><?php echo "{$prixForfaitTTC}€" ?></td>
                <td><strong><?php echo "{$prixForfaitTotal}€" ?></strong></td>
            </tr>
        </table>

        <?php $TotalHT = $$prixForfaitHT = $forfait[0]['prixht'] * $joursRestants + $priOptionxHT * $option['nbsemaines'];
        $TotalTTC = $prixForfaitTotal + $prixOptionTotal;

        ?>
        <table class="total">
            <tr>
                <th>Total HT</th>
                <td><?php echo "{$TotalHT}€" ?></td>
            </tr>
            <tr>
                <th>Total TTC</th>
                <td><?php echo "{$TotalTTC}€" ?></td>
            </tr>
        </table>
    </main>
</body>

</html>