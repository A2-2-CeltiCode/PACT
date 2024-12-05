<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";

class Facture
{
    private $dbh;
    private $idOffre;
    private $idAdresse;
    private $idCompte;
    private $idFacture;
    private $offre;
    private $pro;
    private $adressePACT;
    private $facture;
    private $semainesActifs;
    private $forfait;
    private $optionsFiltrees;
    private $dateDuJour;
    private $datePrestation;

    private $nbJours;

    public function __construct($idOffre, $idAdresse, $idCompte, $idFacture,$nbJours,$dbh)
    {
        $this->idOffre = $idOffre;
        $this->idAdresse = $idAdresse;
        $this->idCompte = $idCompte;
        $this->idFacture = $idFacture;
        $this->dbh = $dbh;
        $this->dateDuJour = date("d-m-Y");
        $this->nbJours = $nbJours;
        $this->loadData();
    }

    private function loadData()
    {
        $this->offre = $this->fetchData('SELECT * FROM pact._offre WHERE idoffre = :idOffre', ['idOffre' => $this->idOffre]);
        $this->pro = $this->fetchData('SELECT * FROM pact.vue_compte_pro WHERE idcompte = :idCompte', ['idCompte' => $this->idCompte]);
        $this->adressePACT = $this->fetchData('SELECT * FROM pact._adresse WHERE idadresse = :idAdresse', ['idAdresse' => $this->idAdresse]);
        $this->facture = $this->fetchData('SELECT * FROM pact._facture WHERE idfacture = :idFacture', ['idFacture' => $this->idFacture]);
        $this->semainesActifs = $this->fetchAllData('SELECT * FROM pact._annulationoption WHERE idoffre = :idOffre', ['idOffre' => $this->idOffre]);
        $this->forfait = $this->fetchAllData('SELECT * FROM pact._offre JOIN pact._forfait USING(nomforfait) WHERE idoffre = :idOffre', ['idOffre' => $this->idOffre]);

        $moisPrestation = $this->getMoisPrestation(date('m', strtotime($this->facture['dateprestaservices'])));
        $this->datePrestation = $moisPrestation . ' ' . date('Y', strtotime($this->facture['dateprestaservices']));
        $this->optionsFiltrees = $this->filterOptions();
    }

    private function fetchData($query, $params)
    {
        $stmt = $this->dbh->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function fetchAllData($query, $params)
    {
        $stmt = $this->dbh->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMoisPrestation($mois)
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

    private function filterOptions()
    {
        return array_filter($this->semainesActifs, function ($option) {
            $moisPrestationNum = date('m', strtotime($this->facture['dateprestaservices']));
            $anneePrestation = date('Y', strtotime($this->facture['dateprestaservices']));
            $moisOption = date('m', strtotime($option['debutoption']));
            $anneeOption = date('Y', strtotime($option['debutoption']));
            return $moisOption == $moisPrestationNum && $anneeOption == $anneePrestation && !$option['estannulee'];
        });
    }

    private function calculerJoursRestants($jourDebut, $nbjours)
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

    public function render()
    {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="/pages/pro/facture/creationFacture.css">
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
                        <td><?php echo "{$this->adressePACT['rue']}, {$this->adressePACT['codepostal']} {$this->adressePACT['ville']}"; ?></td>
                    </tr>
                </table>
                <table class="partie">
                    <tr>
                        <th>Professionnel</th>
                        <td><?php echo "{$this->pro['denominationsociale']} - {$this->pro['raisonsocialepro']}"; ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo "{$this->pro['rue']}, {$this->pro['codepostal']} {$this->pro['ville']}"; ?></td>
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
                        <td><?php echo $this->dateDuJour; ?></td>
                        <td><?php echo $this->facture['idfacture']; ?></td>
                        <td><?php echo $this->offre['titre']; ?></td>
                        <td><?php echo $this->datePrestation; ?></td>
                        <td><?php echo date("d-m-Y", strtotime($this->facture['dateecheance'])); ?></td>
                    </tr>
                </table>
                <table class="tarifs-facture">
                    <tr>
                        <th>Nom du service</th>
                        <th>Quantité</th>
                        <th>Prix HT unitaire</th>
                        <th>Prix TTC unitaire</th>
                        <th>Total HT</th>
                        <th>Total TTC</th>
                    </tr>
                    <?php foreach ($this->optionsFiltrees as $option): ?>
                        <?php
                        $prixOptionHT = $this->fetchData('SELECT prixht FROM pact._option WHERE nomoption = :nomOption', ['nomOption' => $option['nomoption']]);
                        $prixOptionTTC = $this->fetchData('SELECT prixttc FROM pact._option WHERE nomoption = :nomOption', ['nomOption' => $option['nomoption']]);

                        $prixOptionTotalHT = (float)$prixOptionHT['prixht'] * $option['nbsemaines'];
                        $prixOptionTotalTTC = $prixOptionTTC['prixttc'] * $option['nbsemaines'];
                        ?>
                        <tr>
                            <td><?php echo "<strong>Option: </strong>" . $option['nomoption']; ?></td>
                            <td><?php echo "{$option['nbsemaines']} semaines"; ?></td>
                            <td><?php echo "{$prixOptionHT['prixht']}€" ?></td>
                            <td><?php echo "{$prixOptionTTC['prixttc']}€" ?></td>
                            <td><strong><?php echo "{$prixOptionTotalHT}€" ?></strong></td>
                            <td><strong><?php echo "{$prixOptionTotalTTC}€" ?></strong></td>
                        </tr>
                    <?php endforeach;
                    $queryHistorique = $this->fetchData('SELECT * FROM pact._historiqueenligne WHERE idoffre = :idOffre', ['idOffre' => $this->idOffre]);

                    $joursRestants = $this->calculerJoursRestants($queryHistorique['jourdebutnbjours'], $queryHistorique['nbjours']);
                    $prixForfaitHT = $this->forfait[0]['prixht'];
                    $prixForfaitTTC = $this->forfait[0]['prixttc'];
                    $prixForfaitTotalHT = $this->nbJours * $this->forfait[0]['prixht'];
                    $prixForfaitTotalTTC = $this->nbJours * $this->forfait[0]['prixttc'];
                    ?>

                    <tr>
                        <td><?php echo "<strong>Abonnement: </strong>" . $this->forfait[0]['nomforfait']; ?></td>
                        <td><?php echo $this->nbJours . " jours"; ?></td>
                        <td><?php echo "{$prixForfaitHT}€" ?></td>
                        <td><?php echo "{$prixForfaitTTC}€" ?></td>
                        <td><strong><?php echo "{$prixForfaitTotalHT}€" ?></strong></td>
                        <td><strong><?php echo "{$prixForfaitTotalTTC}€" ?></strong></td>
                    </tr>
                </table>

                <?php
                $TotalHT = $prixForfaitTotalHT + $prixOptionTotalHT;
                $TotalTTC = $prixForfaitTotalTTC + $prixOptionTotalTTC;
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
        <?php
        return ob_get_clean();
    }
}

include $_SERVER["DOCUMENT_ROOT"] . '/connect_params.php';



?>