<?php

function calculerJoursEnLigne(PDO $pdo, int $idOffre, string $mois): int {
    // Préparer la requête pour récupérer les jours de début et de fin pour l'offre donnée
    $sql = "SELECT jourdebut, jourfin FROM pact._historiqueenligne WHERE idoffre = :idOffre AND TO_CHAR(jourdebut, 'MM-YYYY') = :mois";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->bindValue(':mois', $mois, PDO::PARAM_STR);
    $stmt->execute();
    
    $joursEnLigne = 0;
    
    // Récupérer tous les résultats dans un tableau
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Parcourir les résultats et calculer le nombre de jours en ligne
    foreach ($rows as $row) {
        
        $jourDebut = new DateTime($row['jourdebut']);
        if ($row['jourfin'] === null) {
            $jourFin = DateTime::createFromFormat('m-Y-d', $mois . '-01');
            if (!$jourFin) {
                // Gérer l'erreur de formatage ici
                throw new Exception("Format de date invalide : " . $mois . '-01');
            }
            $jourFin->modify('last day of this month');
        } else {
            $jourFin = new DateTime($row['jourfin']);
        }
        
        // Calculer la différence en jours
        $interval = $jourDebut->diff($jourFin);
        $joursEnLigne += $interval->days + 1; // Ajouter 1 pour inclure le jour de début
    }
    
    return $joursEnLigne;
}
