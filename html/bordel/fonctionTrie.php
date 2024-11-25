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
        $sql .= " AND (";
        $conditions = [];
        foreach ($nomcategories as $index => $category) {
            $conditions[] = "nomcategorie = :category_$index";
        }
        $sql .= implode(' OR ', $conditions);
        $sql .= ")";
    }

    // Ajout du tri
    $sql .= " ORDER BY " . $sort;

    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres
    if ($minPrix !== null && $minPrix !== '') {
        $stmt->bindValue(':minPrix', $minPrix, PDO::PARAM_INT);
    }
    if ($maxPrix !== null && $maxPrix !== '') {
        $stmt->bindValue(':maxPrix', $maxPrix, PDO::PARAM_INT);
    }
    if ($titre !== null) {
        $stmt->bindValue(':titre', '%' . $titre . '%', PDO::PARAM_STR);
    }

    // Liaison des catégories
    if (!empty($nomcategories) && !in_array('Tout', $nomcategories)) {
        foreach ($nomcategories as $index => $category) {
            $stmt->bindValue(":category_$index", $category, PDO::PARAM_STR);
        }
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}