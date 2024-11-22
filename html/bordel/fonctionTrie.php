<?php

function getOffres(PDO $pdo, $sort = 'idoffre DESC', $minPrix = null, $maxPrix = null, $titre = null, $nomcategories = []) {
    $sql = "SELECT * FROM pact.vue_offres WHERE 1=1";

    // Ajout des filtres
    if ($minPrix !== null && $minPrix !== '') {
        $sql .= " AND valprix >= :minPrix";
    }
    if ($maxPrix !== null && $maxPrix !== '') {
        $sql .= " AND valprix <= :maxPrix";
    }
    if ($titre !== null) {
        $sql .= " AND LOWER(titre) LIKE LOWER(:titre)";
    }
    if (!empty($nomcategories) && !in_array('Tout', $nomcategories)) {
        $placeholders = implode(',', array_map(function($key) { return ":category_$key"; }, array_keys($nomcategories)));
        $sql .= " AND nomcategorie IN ($placeholders)";
    }

    // Ajout du tri
    $sql .= " ORDER BY " . $sort;

    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres
    if ($minPrix !== null && $minPrix !== '') {
        $stmt->bindParam(':minPrix', $minPrix, PDO::PARAM_INT);
    }
    if ($maxPrix !== null && $maxPrix !== '') {
        $stmt->bindParam(':maxPrix', $maxPrix, PDO::PARAM_INT);
    }
    if ($titre !== null) {
        $titre = '%' . $titre . '%';
        $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    }

    // Liaison des catégories
    if (!empty($nomcategories) && !in_array('Tout', $nomcategories)) {
        foreach ($nomcategories as $key => $category) {
            $stmt->bindValue(":category_$key", $category, PDO::PARAM_STR);
        }
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}