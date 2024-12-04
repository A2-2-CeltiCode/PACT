<?php
function mettreAJourJourFin($pdo, $idcompte) {
    // Récupérer toutes les entrées sans jourFin et qui ne sont pas du mois en cours
    $queryHistorique = $pdo->prepare("
        SELECT jourdebut 
        FROM pact._historiqueenligne 
        WHERE jourfin IS NULL 
        AND DATE_PART('month', jourdebut) != DATE_PART('month', CURRENT_DATE)
    ");
    
    $queryHistorique->execute();
    $historiques = $queryHistorique->fetchAll(PDO::FETCH_ASSOC);
    
    // Mettre à jour chaque entrée avec le dernier jour du mois correspondant
    foreach ($historiques as $historique) {
        $jourdebut = new DateTime($historique['jourdebut']);
        $jourfin = $jourdebut->format('Y-m-t'); // Dernier jour du mois

        $queryUpdate = $pdo->prepare("
            UPDATE pact._historiqueenligne 
            SET jourfin = :jourfin 
            WHERE jourdebut = :jourdebut
        ");
        $queryUpdate->bindValue(':jourfin', $jourfin, PDO::PARAM_STR);
        $queryUpdate->bindValue(':jourdebut', $historique['jourdebut'], PDO::PARAM_STR);
        $queryUpdate->execute();
    }

    return true; // Toutes les entrées historiques sont mises à jour
}
?>