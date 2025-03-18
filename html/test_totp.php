<?php
require_once(__DIR__."/../vendor/autoload.php");

use OTPHP\TOTP;

$sec = TOTP::generate(null);

echo "the secret is {$sec->getSecret()}";

echo "<br>";
$sec->setLabel('Label of your web');
$grCodeUri = $sec->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
    '[DATA]'
);
echo "<img src='{$grCodeUri}'>";