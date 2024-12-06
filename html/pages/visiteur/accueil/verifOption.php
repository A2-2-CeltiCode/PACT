<?php
function updateAnnulationOption($pdo) {
    $today = new DateTime();
    $sql = "SELECT idoffre, debutoption, nbsemaines FROM pact._annulationoption";
    $stmt = $pdo->query($sql);
    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($options as $option) {
        $debutoption = new DateTime($option['debutoption']);
        $debutoption->modify('+' . $option['nbsemaines'] . ' weeks');

        if ($today >= $debutoption) {
            $updateSql = "UPDATE pact._offre SET nomoption = 'Aucune' WHERE idoffre = :idoffre";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute(['idoffre' => $option['idoffre']]);
        }
    }
}