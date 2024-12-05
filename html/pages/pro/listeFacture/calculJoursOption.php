<?php

function calculerJoursOption(PDO $pdo, int $idOffre, string $mois): int {
    // Convertir le mois en date de début et de fin
    $debutMois = DateTime::createFromFormat('m-Y-d', $mois . '-01');
    if (!$debutMois) {
        throw new Exception("Format de date invalide : " . $mois . '-01');
    }
    $finMois = clone $debutMois;
    $finMois->modify('last day of this month');

    // Requête pour obtenir les options de l'offre
    $stmt = $pdo->prepare("
        SELECT debutoption, nbsemaines
        FROM pact._annulationoption
        WHERE idoffre = :idOffre AND estannulee = false AND TO_CHAR(debutoption, 'MM-YYYY') = :mois
    ");
    $stmt->execute([
        'idOffre' => $idOffre,
        'mois' => $mois
    ]);
    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalJours = 0;

    foreach ($options as $option) {
        $debutOption = new DateTime($option['debutoption']);
        $finOption = clone $debutOption;
        $finOption->modify('+' . ($option['nbsemaines'] * 7 - 1) . ' days');

        // Calculer l'intersection entre l'option et le mois
        $debutIntersection = max($debutOption, $debutMois);
        $finIntersection = $finOption;
        $joursIntersection = $finIntersection->diff($debutIntersection)->days + 1; // Ajouter 1 pour inclure le dernier jour
        $totalJours += $joursIntersection;
    }

    return $totalJours;
}
