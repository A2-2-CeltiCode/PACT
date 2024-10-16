
<!DOCTYPE html>
<html>
<head>
    <?php require "components/Input/Input.php"?>
    <?php require "components/Button/Button.php"?>
    <title>Création d'une offre</title>

    <script src="creationOffre.js"></script>

    <link rel="stylesheet" href="./creationOffre.css">
</head>
<body>
          
    <div>
    <img source="logo.png" alt="Logo">
    <h1>Créez votre Offre</h1>
    </div>
    
    
    <form method="post" action="creationOffre.php" enctype="multipart/form-data">
        <section>
            <article>
                <label>Nom de l'offre</label><br>
                <?php Input::render(name:"nomOffre", type:"text") ?><br><br>

                
        
                <label>information de l'offre</label><br>
                <?php Input::render(name:"ville", type:"text") ?><br>
                <?php Input::render(name:"codePostal", type:"number") ?><br>
                <?php Input::render(name:"adressePostale", type:"text") ?><br>
                
                <label>Numéro de téléphone</label><br>
                <?php Input::render(name:"numtel", type:"number") ?><br>

                <label>Site Web</label><br>
                <?php Input::render(name:"siteWeb", type:"text") ?><br>

                <label>Description de l'offre</label><br>
                <?php Input::render(name:"descriptionOffre", type:"text") ?><br><br>

                <label>description Detaille</label><br>
                <?php Input::render(name:"descriptionDetaille", type:"text") ?><br><br>
            </article>
            <article>
                <label>Tags</label><br>
                <?php Input::render(name:"tags", type:"text") ?><br><br>
                
                <label>Type de forfait</label><br>
                <select name="typeForfait">
                    <option value="Standard">Standard</option>
                    <option value="Premium">Premium</option>
                </select>
                <br><br>

                <label>Photo</label><br>
                <!-- Zone de dépôt -->
                <div id="dropZone" class="drop-zone">
                    Déposez votre image ici ou cliquez pour sélectionner un fichier
                </div>
                
                
                <div id="successMessage" style="display:none; color: green;">Image ajoutée avec succès !</div><br><br>



                

                <label>Type de promotion de l'offre</label><br>
                <select name="typePromotion">
                    <option value="Aucune">Aucune</option>
                    <option value="Relief">Rekief</option>
                    <option value="aLaUne">A la une</option>
                </select><br><br>
            
            
                <label>Type d'offre</label><br>
                <select name="typeOffre" onchange="showFields()" required>
                    <option value="">Sélectionner un type d'offre</option>
                    <option value="Activite">Activite</option>
                    <option value="Visite">Visite</option>
                    <option value="Spectacle">Spectacle</option>
                    <option value="ParcAttraction">Parc d'attraction</option>
                    <option value="Restauration">Restauration</option>
                </select><br><br>

                <!-- Section Activité -->
                <div id="Activite" class="section" style="display:none;">
                    <label>Capacité d'accueil</label><br>
                    <?php Input::render(name: "capacite", type: "number") ?><br><br>
                    <label>Durée de l'activité</label><br>
                    <?php Input::render(name: "duree", type: "number") ?><br><br>
                </div>

                <!-- Section Visite -->
                <div id="Visite" class="section" style="display:none;">
                    <label>Capacité d'accueil</label><br>
                    <?php Input::render(name: "capacite", type: "number") ?><br><br>
                    <label>Durée de la visite</label><br>
                    <?php Input::render(name: "duree", type: "number") ?><br><br>
                    <label>Visite guidée</label><br>
                    <input type="radio" id="oui" name="guide" value="oui">
                    <label for="oui">Oui</label><br>
                    <input type="radio" id="non" name="guide" value="non">
                    <label for="non">Non</label><br><br>
                </div>

                <!-- Section Spectacle -->
                <div id="Spectacle" class="section" style="display:none;">
                    <label>Capacité d'accueil</label><br>
                    <?php Input::render(name: "capacite", type: "number") ?><br><br>
                    <label>Durée du spectacle</label><br>
                    <?php Input::render(name: "duree", type: "number") ?><br><br>
                </div>

                <!-- Section Parc d'Attraction -->
                <div id="ParcAttraction" class="section" style="display:none;">
                    <label>Nombre d'attractions</label><br>
                    <?php Input::render(name: "nbAttractions", type: "number") ?><br><br>
                    <label>Âge minimum</label><br>
                    <?php Input::render(name: "ageMin", type: "number") ?><br><br>
                </div>

                <!-- Section Restauration (vide dans cet exemple) -->
                <div id="Restauration" class="section" style="display:none;">
                    <!-- VIDEEEE -->
                </div>
            </article>
        </section> 
        <div>
        <?php Button::render(text: "Annuler", type: ButtonType::Pro, submit: false); ?>
        <?php Button::render(text: "Valider", type: ButtonType::Pro, submit: true); ?>
        </div>
    </form>

    </form>

    
</body>
</html>

