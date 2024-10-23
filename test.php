<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste déroulante avec cases à cocher</title>
    <style>
        /* Style de base pour le conteneur */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Style pour la liste déroulante cachée */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            padding: 12px 16px;
            z-index: 1;
        }

        /* Style lorsque la liste déroulante est visible */
        .dropdown-content.show {
            display: block;
        }
    </style>
</head>
<body>

<div class="dropdown">
    <button onclick="toggleDropdown()">Sélectionner</button>
    <div class="dropdown-content" id="myDropdown">
        <label><input type="checkbox" value="Option 1"> Option 1</label><br>
        <label><input type="checkbox" value="Option 2"> Option 2</label><br>
        <label><input type="checkbox" value="Option 3"> Option 3</label><br>
    </div>
</div>

<script>
    // Fonction pour basculer l'affichage de la liste déroulante
    function toggleDropdown() {
        var dropdown = document.getElementById("myDropdown");
        dropdown.classList.toggle("show");
    }
</script>

</body>
</html>
