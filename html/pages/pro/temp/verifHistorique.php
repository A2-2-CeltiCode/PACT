<?php
function verifierEtCreerHistoriqueMensuel($idcompte, $pdo) {
    // Récupérer toutes les offres du compte
    $queryOffres = $pdo->prepare("SELECT idoffre, estenligne FROM pact._offre WHERE idcompte = :idcompte");
    $queryOffres->execute(['idcompte' => $idcompte]);
    $offres = $queryOffres->fetchAll(PDO::FETCH_ASSOC);

    // Parcourir chaque offre et vérifier l'historique
    foreach ($offres as $offre) {
        if ($offre['estenligne']) {
            $idoffre = $offre['idoffre'];
            
            // Récupérer l'historique de l'offre
            $queryHistorique = $pdo->prepare("SELECT jourdebut FROM pact._historiqueenligne WHERE idoffre = :idoffre ORDER BY jourdebut DESC LIMIT 1");
            $queryHistorique->execute(['idoffre' => $idoffre]);
            $dernierHistorique = $queryHistorique->fetch(PDO::FETCH_ASSOC);
            
            
            
            // Déterminer la date de début pour les nouvelles entrées
            $dateDebut = $dernierHistorique ? new DateTime($dernierHistorique['jourdebut']) : new DateTime($offre['datecreation']);
            $dateDebut->modify('first day of next month');
            $dateFin = new DateTime();
            $interval = new DateInterval('P1M');
            $period = new DatePeriod($dateDebut, $interval, $dateFin);

            foreach ($period as $date) {
                // Créer une nouvelle entrée pour chaque mois depuis la dernière date
                $nouvelleDateDebut = $date->format('Y-m-01');
                $dernierJourMois = $date->format('Y-m-t');
                $queryInsert = $pdo->prepare("INSERT INTO pact._historiqueenligne (idoffre, jourdebut, jourFin) VALUES (:idoffre, :jourdebut, :jourFin)");
                
                // Si le mois est le mois en cours, ne pas définir jourFin
                if ($date->format('Y-m') === (new DateTime())->format('Y-m')) {
                    $queryInsert = $pdo->prepare("INSERT INTO pact._historiqueenligne (idoffre, jourdebut) VALUES (:idoffre, :jourdebut)");
                    $queryInsert->execute([
                        'idoffre' => $idoffre,
                        'jourdebut' => $nouvelleDateDebut
                    ]);
                } else {
                    $queryInsert->execute([
                        'idoffre' => $idoffre,
                        'jourdebut' => $nouvelleDateDebut,
                        'jourFin' => $dernierJourMois
                    ]);
                }
            }
        }
    }

    return true; // Toutes les entrées historiques sont présentes ou créées
}
?>