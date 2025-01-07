<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Promotion</title>
</head>
<body>
    <form action="creerOffre.php" method="post" onsubmit="return validateForm()">
        <label for="durepromotion">Durée de la promotion :</label>
        <input type="number" id="durepromotion" name="durepromotion" min="1" max="4" required>
        <br><br>
        <button type="submit">Envoyer</button>
    </form>

    <script>
        function validateForm() {
            const durepromotion = document.getElementById("durepromotion").value;
            if (durepromotion > 4) {
                alert("La durée de la promotion ne doit pas dépasser 4.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>