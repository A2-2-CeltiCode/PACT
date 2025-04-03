<?php
error_reporting(0);
ini_set('display_errors', 0);
require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
    $idAvis = intval($_POST['idAvis']);
    $titre = htmlspecialchars($_POST['titre']);
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $note = intval($_POST['note']);
    $contexteVisite = htmlspecialchars($_POST['contexteVisite']);
    $stmt = $dbh->prepare("SELECT idoffre FROM pact._avis WHERE idavis = :idAvis");
    $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
    $idOffre = $stmt->execute();
    $idOffre = $_POST['idOffre'];
    print_r($idOffre);

    try {

        // Mettre à jour les informations de l'avis
        $stmt = $dbh->prepare("UPDATE pact._avis SET titre = :titre, commentaire = :commentaire, note = :note, contextevisite = :contexteVisite WHERE idavis = :idAvis");
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':contexteVisite', $contexteVisite);
        $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Gestion des images
            if (isset($_FILES['drop-zone']) && is_array($_FILES['drop-zone']['name'])) {
                // Supprimer les anciennes images associées à l'avis
                $stmt = $dbh->prepare("SELECT pact._image.nomImage FROM pact._image 
                                       JOIN pact._representeAvis ON pact._image.idImage = pact._representeAvis.idImage 
                                       WHERE pact._representeAvis.idAvis = :idAvis");
                $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
                $stmt->execute();
                $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($images as $image) {
                    $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/ressources/avis/$idAvis/" . $image['nomImage'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Supprime l'image du serveur
                    }
                }

                // Supprimer les entrées dans la base de données
                $stmt = $dbh->prepare("DELETE FROM pact._representeAvis WHERE idAvis = :idAvis");
                $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
                $stmt->execute();

                $stmt = $dbh->prepare("DELETE FROM pact._image WHERE idImage IN (
                                       SELECT idImage FROM pact._representeAvis WHERE idAvis = :idAvis)");
                $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
                $stmt->execute();

                // Ajouter les nouvelles images
                $avis_dir = $_SERVER['DOCUMENT_ROOT'] . "/ressources/avis/$idAvis";
                if (!file_exists($avis_dir) && !mkdir($avis_dir, 0777, true) && !is_dir($avis_dir)) {
                    die("Erreur : Impossible de créer le dossier des images.");
                }

                foreach ($_FILES['drop-zone']['name'][0] as $key => $nomImage) {
                    if (!empty($_FILES['drop-zone']['tmp_name'][$key]) && is_string($nomImage)) {
                        $tmp_name = $_FILES['drop-zone']['tmp_name'][0][$key];
                        $extension = pathinfo($nomImage, PATHINFO_EXTENSION);
                        $nouveauNomImage = uniqid() . '.' . $extension;
                        
                        if (move_uploaded_file($tmp_name, $avis_dir . '/' . $nouveauNomImage)) {
                            // Insérer la nouvelle image dans la base de données
                            $stmt = $dbh->prepare("INSERT INTO pact._image (nomImage) VALUES (:nomImage)");
                            $stmt->bindParam(':nomImage', $nouveauNomImage, PDO::PARAM_STR);
                            $stmt->execute();
                            $idImage = $dbh->lastInsertId();

                            // Associer l'image à l'avis
                            $stmt = $dbh->prepare("INSERT INTO pact._representeAvis (idAvis, idImage) VALUES (:idAvis, :idImage)");
                            $stmt->bindParam(':idAvis', $idAvis, PDO::PARAM_INT);
                            $stmt->bindParam(':idImage', $idImage, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }
            }

            // Redirection après modification
            header("Location: detailsOffre.php?id=" . $idOffre);
            exit;
        } else {
            echo "Erreur lors de la mise à jour de l'avis.";
        }
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage();
    }
}
