<?php

function getOffres(PDO $pdo, $sort = 'idoffre DESC', $minPrix = null, $maxPrix = null, $titre = null, $nomcategories = [], $ouverture = null, $fermeture = null, $localisation = null, $etat = null, $estenligne = null, $idcompte = null, $note = null, $option = []) {
    $sql = "SELECT * FROM pact.vue_offres WHERE 1=1";

    // Ajout des filtres
    if ($minPrix !== null && $minPrix !== '' && $minPrix != 0) {
        $sql .= " AND valprix >= :minPrix";
    }
    if ($maxPrix !== null && $maxPrix !== '' && $maxPrix != 100) {
        $sql .= " AND valprix <= :maxPrix";
    }
    if ($titre !== null && $titre !== '') {
        $sql .= " AND LOWER(titre) LIKE LOWER(:titre)";
    }
    if (!empty($nomcategories) && !in_array('Tout', $nomcategories)) {
        $categoriesPlaceholders = implode(',', array_fill(0, count($nomcategories), '?'));
        $sql .= " AND nomcategorie IN ($categoriesPlaceholders)";
    }
    if ($ouverture !== null && $ouverture !== '') {
        $sql .= " AND heureouverture >= :ouverture";
    }
    if ($fermeture !== null && $fermeture !== '') {
        $sql .= " AND heurefermeture <= :fermeture";
    }
    if ($localisation !== null && $localisation !== '') {
        $sql .= " AND localisation LIKE :localisation";
    }
    if ($etat !== null && $etat !== 'ouvertetferme') {
        $sql .= " AND etat = :etat";
    }
    if ($note !== null && $note != 0) {
        $sql .= " AND note >= :note";
    }
    if (!empty($option)) {
        $optionsPlaceholders = implode(',', array_fill(0, count($option), '?'));
        $sql .= " AND nomoption IN ($optionsPlaceholders)";
    }

    $sql .= " ORDER BY $sort";

    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres
    if ($minPrix !== null && $minPrix !== '' && $minPrix != 0) {
        $stmt->bindValue(':minPrix', $minPrix, PDO::PARAM_INT);
    }
    if ($maxPrix !== null && $maxPrix !== '' && $maxPrix != 100) {
        $stmt->bindValue(':maxPrix', $maxPrix, PDO::PARAM_INT);
    }
    if ($titre !== null && $titre !== '') {
        $stmt->bindValue(':titre', "%$titre%", PDO::PARAM_STR);
    }
    if ($ouverture !== null && $ouverture !== '') {
        $stmt->bindValue(':ouverture', $ouverture, PDO::PARAM_STR);
    }
    if ($fermeture !== null && $fermeture !== '') {
        $stmt->bindValue(':fermeture', $fermeture, PDO::PARAM_STR);
    }
    if ($localisation !== null && $localisation !== '') {
        $stmt->bindValue(':localisation', "%$localisation%", PDO::PARAM_STR);
    }
    if ($etat !== null && $etat !== 'ouvertetferme') {
        $stmt->bindValue(':etat', $etat, PDO::PARAM_STR);
    }
    if ($note !== null && $note != 0) {
        $stmt->bindValue(':note', $note, PDO::PARAM_INT);
    }

    // Liaison des catégories et options
    $index = 1;
    foreach ($nomcategories as $categorie) {
        $stmt->bindValue($index, $categorie, PDO::PARAM_STR);
        $index++;
    }
    foreach ($option as $opt) {
        $stmt->bindValue($index, $opt, PDO::PARAM_STR);
        $index++;
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}