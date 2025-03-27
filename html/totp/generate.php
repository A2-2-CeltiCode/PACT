<?php

session_start();

header('Content-type: application/json');

require_once $_SERVER["DOCUMENT_ROOT"]."/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/../vendor/autoload.php";

use OTPHP\TOTP;

if (!isset($_SESSION["idCompte"])) {
    http_response_code(403);
    ?>
    <h1>FORBIDDEN</h1>
    <p>You don't have permission to access this ressource</p>
    <hr>
    <?php
    die();
}

if (isset($_POST["code"])) {
    $otp = TOTP::createFromSecret($_SESSION["secret"]);

    $valid = $otp->verify($_POST["code"], leeway: 10);

    if ($valid) {
        $id = $_SESSION["idCompte"];
        $key = "lDBEaGbZqSp76wDqvMqG7uIfHMGosT5ONE6Q17frN1c=";
        $encSecret = openssl_encrypt($_SESSION["secret"], "aes-256-cbc", base64_decode($key), $options = 0, $id);
        unset($_SESSION["secret"]);
        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

            $stmt = $dbh->prepare("INSERT INTO pact._totpsecret VALUES (?, ?)");
            $dbh = null;

            $stmt->execute([$id, $encSecret]);

        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br>";
            die();
        }
    } else {
        echo json_encode(array(
            "valid" => false,
            "reason" => "incorrect code"
        ));
    }
} else {
    $otp = TOTP::generate(null);

    $otp->setLabel('Celticode PACT');
    $grCodeUri = $otp->getQrCodeUri(
        'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
        '[DATA]'
    );
    echo json_encode(array(
        "qrcode" => $grCodeUri,
        "code" => $otp->getSecret()
    ));
    $_SESSION["secret"] = $otp->getSecret();
}