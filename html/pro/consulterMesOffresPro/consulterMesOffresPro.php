<!DOCTYPE html>
<html>
    <?php require_once "../../../composants/Input/Input.php"?>
    <?php require_once "../../../composants/Button/Button.php"?>
    <?php require_once "../../../composants/Header/Header.php"?>
    <?php require_once "../../../composants/Footer/Footer.php"?>

    <?php 
        $server = 'localhost';
        $dbname = 'postgres';
        $user = 'postgres';
        $pass = "6969";

        try{
            $dbh = new PDO( "pgsql:host=$server;port:5432;dbname=$dbname", $user,$pass);

            $dbh->exec(statement:"SET search_path TO pact");
        }catch(PDOException $e){
            print "Erreur !:" . $e->getMessage() . "<br>";
            die();
        }


        $idcompte = isset($_POST["idcompte"])  ? intval($_POST["idcompte"]) : 2;

        $requeteSql = 
        'SELECT 
            i.nomImage as image,
            o.titre,
            o.ville as lieu,
            cp.denominationSociale as guide,
            o.estenligne as enligne
        FROM 
            _image i
        JOIN 
            _offre o ON i.idOffre = o.idOffre
        JOIN 
            _comptePro cp ON o.idCompte = cp.idCompte';
        


        $requete_preparee = $dbh->prepare($requete_sql);
        $requete_preparee->bindParam(':idOffre',  $idOffre,type:PDO::PARAM_INT);
        $requete_preparee->execute();
        $offres = $requete_preparee->fetch(PDO::FETCH_ASSOC);


        $offresEnLigne = [];
        $offresHorsLigne = [];
        foreach ($offres as $offre) {
            if ($offre['enligne']) {
                $offresEnLigne[] = $offre;
            } else {
                $offresHorsLigne[] = $offre;
            }
        }
    
    
    ?>
    <head>
        <link rel="stylesheet" type="text/css" href="./consulterMesOffresPro.css">
        <link rel="stylesheet" href="../../../ui.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="etatOffre.js" defer></script>

    </head>

    <?php Header::render(HeaderType::Pro);?>

    
    <body>
        
        <nav class="hautDePage">
            <div class="titre">Mes Offres</div>
            <div id="statue">
                <span class="option active" id="on">En ligne</span>
                <span class="option" id="off">Hors ligne</span>
            </div>
            <a href="creerUneOffre.php" class="buttons" >
                <?php Button::render(type:ButtonType::Pro,text:"Créer une offre")?>
            </a>
        </nav>


        <section class="sectionCarteEnligne" id="sectionEnLigne">
           <?php foreach ($offresEnLigne as $offre) {
            ?>
                <div class="carteOffre">
                    <img class="imgcarte" height="276px" width="276px" src="<?php echo $offre['image']; ?>">
                        <div class="contenuecarte">
                            <div class="card-header">
                                <div>
                                    <img class="color-svgtitle" src="imagesMesOffresPro/map.svg"><span>Visite</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <span class="title"><?php echo $offre['titre']; ?></span>
                                <div class="detail">
                                    <div class="composantdetail">
                                        <img class="color-svg" src="imagesMesOffresPro/person.svg"> <?php echo $offre['guide']; ?>
                                    </div>
                                    <div class="composantdetail">
                                        <img class="color-svg" src="imagesMesOffresPro/location.svg"><?php echo $offre['lieu']; ?>
                                    </div>
                                    <div class="composantdetail">
                                        <img class="color-svg" src="imagesMesOffresPro/schedule.svg"><?php echo $offre['duree']; ?>
                                    </div>
                                </div>
                            </div>
                            <hr class="solid">
                            <div class="card-footer">
                                <span class="prix"><strong><?php echo $offre['prix']; ?></strong></span>
                                <div class="composantdetail">
                                    <img class="color-svg" src="imagesMesOffresPro/bubble.svg"> 
                                    <span id="marginCommentaire"><?php echo "null"; ?></span>
                                    <img class="color-svg" src="imagesMesOffresPro/bookmark.svg"> 
                                </div>
                            </div>
                        </div>
                </div>
        
            <div class="buttons">
               <a href="modifierOffre.php"> <?php Button::render(type:ButtonType::Pro,text:"Modifier")?></a>
               <a href="voirLesAvis.php"> <?php Button::render(type:ButtonType::Pro,text:"Voir les avis")?></a>
            </div>
            
        <?php
        }
        ?>
           
        </section>

        <section class="sectionCarteHorsLigne" id="sectionHorsLigne" style="display: none;">
        <?php foreach ($offresHorsLigne as $offre) {
            ?>
                <div class="carteOffre">
                    <img class="imgcarte" height="276px" width="276px" src="<?php echo $offre['image']; ?>">
                        <div class="contenuecarte">
                            <div class="card-header">
                                <div>
                                    <img class="color-svgtitle" src="imagesMesOffresPro/map.svg"><span>Visite</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <span class="title"><?php echo $offre['titre']; ?></span>
                                <div class="detail">
                                    <div class="composantdetail">
                                        <img class="color-svg" src="imagesMesOffresPro/person.svg"> <?php echo $offre['guide']; ?>
                                    </div>
                                    <div class="composantdetail">
                                        <img class="color-svg" src="imagesMesOffresPro/location.svg"><?php echo $offre['lieu']; ?>
                                    </div>
                                    <div class="composantdetail">
                                        <img class="color-svg" src="imagesMesOffresPro/schedule.svg"><?php echo $offre['duree']; ?>
                                    </div>
                                </div>
                            </div>
                            <hr class="solid">
                            <div class="card-footer">
                                <span class="prix"><strong><?php echo $offre['prix']; ?></strong></span>
                                <div class="composantdetail">
                                    <img class="color-svg" src="imagesMesOffresPro/bubble.svg"> 
                                    <span id="marginCommentaire"><?php echo "null"; ?></span>
                                    <img class="color-svg" src="imagesMesOffresPro/bookmark.svg"> 
                                </div>
                            </div>
                        </div>
                </div>
        
            <div class="buttons">
               <a href="modifierOffre.php"> <?php Button::render(type:ButtonType::Pro,text:"Modifier")?></a>
               <a href="voirLesAvis.php"> <?php Button::render(type:ButtonType::Pro,text:"Voir les avis")?></a>
            </div>
            
        <?php
        }
        ?>

        </section>


    </body>


    <?php Footer::render(FooterType::Pro);?>





</html>