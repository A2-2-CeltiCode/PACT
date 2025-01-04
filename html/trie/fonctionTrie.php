<?php

function getOffres(PDO $pdo, $sort = 'idoffre DESC', $minPrix = null, $maxPrix = null, $titre = null, $nomcategories = [], $ouverture = null, $fermeture = null, $localisation = null, $etat = null, $estenligne = null, $idcompte = null, $note = null, $option = [], $latitude = null, $longitude = null) {
    $sql = "SELECT *, (6371 * acos(cos(radians(:latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians(:longitude)) + sin(radians(:latitude)) * sin(radians(latitude)))) AS distance FROM pact.vue_offres WHERE 1=1";

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
        $categoriesPlaceholders = implode(',', array_map(function($index) {
            return ":category_$index";
        }, array_keys($nomcategories)));
        $sql .= " AND nomcategorie IN ($categoriesPlaceholders)";
    } else {
        $sql .= " AND nomcategorie IN ('Spectacle', 'Activite', 'Restaurant', 'Parc d''attractions', 'Visite')";
    }
   
    if (!empty($option)) {
        $sql .= " AND (";
        $first = true;
        foreach ($option as $opt) {
            if ($opt == '1') {
                if (!$first) {
                    $sql .= " OR";
                }
                $sql .= " nomgamme = '€ (-25€)'";
                $first = false;
            }
            if ($opt == "2") {
                if (!$first) {
                    $sql .= " OR";
                }
                $sql .= " nomgamme = '€€ (25-40€)'";
                $first = false;
            }
            if ($opt == "3") {
                if (!$first) {
                    $sql .= " OR";
                }
                $sql .= " nomgamme = '€€€ (+40€)'";
                $first = false;
            }
        }
        $sql .= ")";
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
    if ($etat !== null && $etat !== '' && $etat !== 'ouvertetferme') {
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
    if($note !== null && $note !== '' && $note != 0){
        $sql .= " AND moynotes >= :note";
    }
    if ($estenligne !== null && $estenligne !== '') {
        $sql .= " AND estenligne = :estenligne";
    }
    if ($idcompte !== null && $idcompte !== '') {
        $sql .= " AND idcompte = :idcompte";
    }
    if ($latitude !== null && $longitude !== null) {
        $sql .= " HAVING distance < 100"; // Filtrer les offres dans un rayon de 100 km
    }

    // Ajout du tri
    $sql .= " ORDER BY " . $sort;

    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres
    if ($minPrix !== null && $minPrix !== '' && $minPrix != 0) {
        $stmt->bindValue(':minPrix', $minPrix, PDO::PARAM_INT);
    }
    if ($maxPrix !== null && $maxPrix !== '' && $maxPrix != 100) {
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
    if ($estenligne !== null && $estenligne !== '') {
        $stmt->bindValue(':estenligne', $estenligne === 'enligne' ? true : false, PDO::PARAM_BOOL);
    }
    if($note !== null && $note !== '' && $note != 0){
        $stmt->bindValue(':note', $note, PDO::PARAM_INT);
    }

    if ($idcompte !== null && $idcompte !== '') {
        $stmt->bindValue(':idcompte', $idcompte, PDO::PARAM_INT);
    }

    if ($latitude !== null && $longitude !== null) {
        $stmt->bindValue(':latitude', $latitude, PDO::PARAM_STR);
        $stmt->bindValue(':longitude', $longitude, PDO::PARAM_STR);
    }
    
    // Liaison des catégories
    if (!empty($nomcategories) && !in_array('Tout', $nomcategories)) {
        error_log(print_r($nomcategories, true));
        foreach ($nomcategories as $index => $category) {
            
            $stmt->bindValue(":category_$index", $category, PDO::PARAM_STR);
        }
    }

    

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}