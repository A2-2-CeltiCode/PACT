<?php
//header("Location: ./detailsOffre.php?id=" . $_POST['idOffre']);
session_start();
$idCompte = $_SESSION['idCompte'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/connect_params.php';
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

$stmt = $dbh->prepare("INSERT INTO pact._avis (idOffre, idCompte, commentaire, note, titre, contexteVisite, dateVisite, dateAvis, estVu) VALUES (:idoffre, :idcompte, :commentaire, :note, :titre, :contextevisite, :datevisite, CURRENT_DATE, FALSE)");

$stmt->bindParam(':idoffre', $_POST['idOffre'], PDO::PARAM_INT);
$stmt->bindParam(':idcompte', $_SESSION['idCompte'], PDO::PARAM_INT);
$stmt->bindParam(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
$stmt->bindParam(':note', $_POST['note'], PDO::PARAM_STR);
$stmt->bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
$stmt->bindParam(':contextevisite', $_POST['contexteVisite'], PDO::PARAM_STR);
$dateVisite = date('Y-m-d');
$stmt->bindParam(':datevisite', $dateVisite, PDO::PARAM_STR);

$stmt->execute();

$idavis = $dbh->lastInsertId();
$avis_dir = $_SERVER['DOCUMENT_ROOT'] . "/ressources/avis/$idavis";
if (!file_exists($avis_dir)) {
    mkdir($avis_dir, 0777, true);
}
if ($_FILES['drop-zone']['name'][0][0]) {


if (is_array($_FILES['drop-zone']['name'])) {
    foreach ($_FILES['drop-zone']['name'] as $key => $val) {

        $nomImage = $_FILES['drop-zone']['name'][$key];
        $tmp_name = $_FILES['drop-zone']['tmp_name'][$key];
        $location = $_SERVER["DOCUMENT_ROOT"] . "/ressources/avis/" . $idavis . '/';

        $extension = pathinfo($nomImage[0], PATHINFO_EXTENSION);

        $nouveauNomImage = uniqid() . '.' . $extension;

        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }

        move_uploaded_file($tmp_name[0], $location . $nouveauNomImage);
        $stmt = $dbh->prepare(
            "INSERT INTO pact._image(nomImage) 
            VALUES(:nomImage)"
        );
        $stmt->bindValue(':nomImage', $nouveauNomImage, PDO::PARAM_STR);
        $stmt->execute();
        $idImage = $dbh->lastInsertId();

        $stmt = $dbh->prepare(
            "INSERT INTO pact._representeavis(idAvis, idImage) 
            VALUES(:idAvis, :idImage)"
        );
        $stmt->bindValue(':idAvis', $idavis, PDO::PARAM_INT);
        $stmt->bindValue(':idImage', $idImage, PDO::PARAM_INT);
        $stmt->execute();
    }
} else {
    $nomImage = $_FILES['drop-zone']['name'];
    $tmp_name = $_FILES['drop-zone']['tmp_name'];
    $location = $_SERVER["DOCUMENT_ROOT"] . "/ressources/avis/" . $idavis . '/';

    $extension = pathinfo($nomImage, PATHINFO_EXTENSION);

    $nouveauNomImage = uniqid() . '.' . $extension;

    if (!file_exists($location)) {
        mkdir($location, 0777, true);
    }

    move_uploaded_file($tmp_name, $location . $nouveauNomImage);
    $stmt = $dbh->prepare(
        "INSERT INTO pact._image(nomImage) 
        VALUES(:nomImage)"
    );
    $stmt->bindValue(':nomImage', $nouveauNomImage, PDO::PARAM_STR);
    $stmt->execute();
    $idImage = $dbh->lastInsertId();

    $stmt = $dbh->prepare(
        "INSERT INTO pact._representeavis(idAvis, idImage) 
        VALUES(:idAvis, :idImage)"
    );
    $stmt->bindValue(':idAvis', $idavis, PDO::PARAM_INT);
    $stmt->bindValue(':idImage', $idImage, PDO::PARAM_INT);
    $stmt->execute();
}
}
header("Location: /pages/membre/detailsOffre/detailsOffre.php?id=" . $_POST['idOffre']);
?>