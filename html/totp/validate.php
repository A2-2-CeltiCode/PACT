<?php

session_start();

header('Content-type: application/json');

require_once $_SERVER["DOCUMENT_ROOT"]."/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/../vendor/autoload.php";

use OTPHP\TOTP;

if (!isset($_SESSION["idCompte"]) && !isset($_SESSION["totpid"])) {
    http_response_code(403);
    ?>
    <h1>FORBIDDEN</h1>
    <p>You don't have permission to access this ressource</p>
    <hr>
    <?php
    die();
}

if (isset($_SESSION["timeout"]) && unserialize($_SESSION["timeout"])["until"] > time()) {
    date_default_timezone_set('Europe/Paris');
    echo json_encode(array(
        "valid" => false,
        "reason" => "wait until",
        "until" => date(DATE_ISO8601, unserialize($_SESSION["timeout"])["until"])
    ));
    die();
}

$id = $_SESSION["idCompte"] ?? $_SESSION["totpid"];
try {

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);

    $stmt = $dbh->prepare("SELECT secret FROM pact._totpsecret WHERE idCompte = ?;");
    $dbh = null;

    $stmt->execute([$id]);
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $secret = $result[0]->secret;

} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br>";
    die();
}

$key = "lDBEaGbZqSp76wDqvMqG7uIfHMGosT5ONE6Q17frN1c=";

$otp = TOTP::createFromSecret(openssl_decrypt($secret, "aes-256-cbc", base64_decode($key), $options = 0, $id));

$valid = $otp->verify($_POST["code"], leeway: 10);

if ($valid) {
    unset($_SESSION["timeout"]);
    if (!isset($_SESSION["idCompte"])) {
        $_SESSION["idCompte"] = $_SESSION["totpid"];
    }
    echo json_encode(array(
        "valid" => true
    ));
} else {
    if (isset($_SESSION["timeout"])) {
        $timeout = unserialize($_SESSION["timeout"]);
        $timeout["until"] = time()+ 2 ** ($timeout["number"] + 2);
        $timeout["number"]++;
        $_SESSION["timeout"] = serialize($timeout);
    } else {
        $_SESSION["timeout"] = serialize(array(
            "until" => time()+8,
            "number" => 1
        ));
    }
    echo json_encode(array(
        "valid" => false,
        "reason" => "incorrect code"
    ));
}

?>