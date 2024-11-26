<?php

function getOffres(PDO $pdo, $sort = 'idoffre DESC', $minPrix = null, $maxPrix = null, $titre = null, $nomcategories = [], $ouverture = null, $fermeture = null, $localisation = null, $etat = null) {
    $sql = "SELECT * FROM pact.vue_offres WHERE 1=1";

    // Ajout des filtres
    if ($minPrix !== null && $minPrix !== '') {
        $sql .= " AND valprix >= :minPrix";
    }
    if ($maxPrix !== null && $maxPrix !== '') {
        $sql .= " AND valprix <= :maxPrix";
    }
    if ($titre !== null && $titre !== '') {
        $sql .= " AND LOWER(titre) LIKE LOWER(:titre)";
    }
    if (!empty($nomcategories) && !in_array('Tout', $nomcategories)) {
        $categoriesPlaceholders = implode(',', array_map(function($index) {
            return ":category_$index";
        }, array_keys($nomcategories)));
        $sql .= " AND nomcategorie IN ($categoriesPlaceholders)";
    }
    if ($ouverture !== null && $ouverture !== '') {
        $sql .= " AND (
            (heureouverture < heurefermeture AND :ouverture BETWEEN heureouverture AND heurefermeture) OR
            (heureouverture > heurefermeture AND (:ouverture >= heureouverture OR :ouverture <= heurefermeture))
        )";
    }
    if ($fermeture !== null && $fermeture !== '') {
        $sql .= " AND (
            (heureouverture < heurefermeture AND :fermeture BETWEEN heureouverture AND heurefermeture) OR
            (heureouverture > heurefermeture AND (:fermeture >= heureouverture OR :fermeture <= heurefermeture))
        )";
    }
    if ($localisation !== null && $localisation !== '') {
        $sql .= " AND (LOWER(rue) LIKE LOWER(:localisation) OR LOWER(ville) LIKE LOWER(:localisation) OR LOWER(CAST(codepostal AS TEXT)) LIKE LOWER(:localisation))";
    }
    if ($etat !== null && $etat !== '') {
        if ($etat === 'ouvert') {
            $sql .= " AND (
                (heureouverture < heurefermeture AND CURRENT_TIME BETWEEN heureouverture AND heurefermeture) OR
                (heureouverture > heurefermeture AND (CURRENT_TIME >= heureouverture OR CURRENT_TIME <= heurefermeture))
            )";
        } elseif ($etat === 'ferme') {
            $sql .= " AND (
                (heureouverture < heurefermeture AND (CURRENT_TIME < heureouverture OR CURRENT_TIME > heurefermeture)) OR
                (heureouverture > heurefermeture AND (CURRENT_TIME < heureouverture AND CURRENT_TIME > heurefermeture))
            )";
        }
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
    if ($titre !== null && $titre !== '') {
        $stmt->bindValue(':titre', '%' . $titre . '%', PDO::PARAM_STR);
    }
    if ($ouverture !== null && $ouverture !== '') {
        $stmt->bindValue(':ouverture', $ouverture, PDO::PARAM_STR);
    }
    if ($fermeture !== null && $fermeture !== '') {
        $stmt->bindValue(':fermeture', $fermeture, PDO::PARAM_STR);
    }
    if ($localisation !== null && $localisation !== '') {
        $stmt->bindValue(':localisation', '%' . $localisation . '%', PDO::PARAM_STR);
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