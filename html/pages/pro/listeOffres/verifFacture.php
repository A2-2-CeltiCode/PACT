<?php
function verifierEtCreerFacturesMensuelles($idcompte, $pdo) {
    // Récupérer toutes les offres du compte
    $queryOffres = $pdo->prepare("SELECT idoffre FROM pact._offre WHERE idcompte = :idcompte");
    $queryOffres->execute(['idcompte' => $idcompte]);
    $offres = $queryOffres->fetchAll(PDO::FETCH_ASSOC);

    // Parcourir chaque offre et vérifier les factures
    foreach ($offres as $offre) {
        $idoffre = $offre['idoffre'];

        // Récupérer les factures de l'offre
        $queryFactures = $pdo->prepare("SELECT dateprestaservices FROM pact._facture WHERE idoffre = :idoffre");
        $queryFactures->execute(['idoffre' => $idoffre]);
        $factures = $queryFactures->fetchAll(PDO::FETCH_ASSOC);

        // Vérifier qu'il y a une facture chaque mois
        $moisFactures = [];
        foreach ($factures as $facture) {
            $mois = date('Y-m', strtotime($facture['dateprestaservices']));
            if (!in_array($mois, $moisFactures)) {
                $moisFactures[] = $mois;
            }
        }

        // Comparer les mois facturés avec les mois attendus
        $dateDebut = new DateTime($factures[0]['dateprestaservices']);
        $dateFin = new DateTime();
        $interval = new DateInterval('P1M');
        $period = new DatePeriod($dateDebut, $interval, $dateFin);

        foreach ($period as $date) {
            $mois = $date->format('Y-m');
            if (!in_array($mois, $moisFactures)) {
                // Créer une nouvelle facture pour le mois manquant
                $nouvelleDatePresta = $date->format('Y-m-01');
                $nouvelleDateEcheance = $date->format('Y-m-20');
                $queryInsert = $pdo->prepare("INSERT INTO pact._facture (idoffre, dateprestaservices, dateecheance) VALUES (:idoffre, :dateprestaservices, :dateecheance)");
                $queryInsert->execute([
                    'idoffre' => $idoffre,
                    'dateprestaservices' => $nouvelleDatePresta,
                    'dateecheance' => $nouvelleDateEcheance
                ]);
            }
        }
    }

    return true; // Toutes les factures sont présentes ou créées
}
?>