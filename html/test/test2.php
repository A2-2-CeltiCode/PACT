<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche à proximité</title>
</head>
<body>
    <button id="rechercherProximite">Rechercher à proximité</button>
    <div id="resultats"></div>

    <script>
        document.getElementById('rechercherProximite').addEventListener('click', function() {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                fetch('./test.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ latitude, longitude })
                }).then(response => response.json())
                .then(data => {
                    // Handle the data received from the server
                    console.log(data);
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>