<?php

require_once(__DIR__."/../vendor/autoload.php");

use OTPHP\TOTP;
/*
$sec = TOTP::generate(null);

echo "the secret is {$sec->getSecret()}";

echo "<br>";
$sec->setLabel('Label of your web');
$grCodeUri = $sec->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
    '[DATA]'
);
echo "<img src='{$grCodeUri}'>";
*/
$secret = "FJKTNNRARYFXG7H2KD4LMSG2CKDKLW4W5QAOZUV3LVXPRW3GI5EN5TQ6LXNSDUNSTUP5CQF7TG2JYM7EE4JFKPGWDA4N3G5RFI3GWQQ";
$key = "lDBEaGbZqSp76wDqvMqG7uIfHMGosT5ONE6Q17frN1c=";
$cipher = "aes-256-cbc";
// $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
$iv = 1;
?>
<pre>
<?php

echo $secret . PHP_EOL;
echo openssl_encrypt($secret, $cipher, base64_decode($key), $options = 0, $iv).PHP_EOL;
echo strlen(openssl_encrypt($secret, $cipher, base64_decode($key), $options = 0, $iv));

$otp = TOTP::createFromSecret($secret);
$otp->setLabel('Label of your web');
$grCodeUri = $otp->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
    '[DATA]'
);
echo "<img src='{$grCodeUri}'>";

?>
</pre>