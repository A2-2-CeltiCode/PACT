<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

$data = json_decode(file_get_contents('php://input'), true);
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$rayon = 100000; // Rayon en kilomètres

$sql = "
    SELECT * FROM (
        SELECT *, 
        (6371 * acos(cos(radians(:latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians(:longitude)) + sin(radians(:latitude)) * sin(radians(latitude)))) AS distance 
        FROM pact._offre
    ) AS subquery
    WHERE distance < :rayon 
    ORDER BY distance
";

$stmt = $dbh->prepare($sql);
$stmt->bindValue(':latitude', $latitude, PDO::PARAM_STR);
$stmt->bindValue(':longitude', $longitude, PDO::PARAM_STR);
$stmt->bindValue(':rayon', $rayon, PDO::PARAM_INT);
$stmt->execute();

$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($offres);
?>