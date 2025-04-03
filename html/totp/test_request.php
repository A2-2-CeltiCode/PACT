<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <pre>
Session id = <?=$_SESSION["idCompte"]?>  totp id = <?=$_SESSION["totpid"]?>
        </pre>
        <textarea id="code"></textarea>
        <button id="validateButton" onclick="validateTOTP()">validate TOTP</button>
        <button id="sessionSetter" onclick="setSessionId()">Set Session ID</button>
        <button id="totpidSetter" onclick="setTotpId()">Set TOTP ID</button>
        <button id="generateTOTP" onclick="generateTOTP()" >generate TOTP</button>
        <button onclick="validate()">validate</button>
        <br>
        <img id="otpqrcode">
    </body>
    <script src="test_request.js"></script>
</html>