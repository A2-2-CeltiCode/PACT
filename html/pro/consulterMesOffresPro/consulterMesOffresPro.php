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
            $dbh = new PDO( "pgsql:host=$server;port=5432;dbname=$dbname", $user,$pass);

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
            o.idadresse,
            cp.denominationSociale as guide,
            o.estenligne as enligne,
            ad.ville as lieu,
            COALESCE(pa.valprix, ppa.valprix, pv.valprix, ps.valprix) as prix,
            COALESCE(pa.tempsenminutes, pv.tempsenminutes, ps.tempsenminutes) as duree,
            COALESCE(pa.nomcategorie,  ppa.nomcategorie,ps.nomcategorie, pv.nomcategorie) as nomoffre
        FROM 
            _image i
        JOIN 
            _offre o ON i.idOffre = o.idOffre
        JOIN 
            _comptePro cp ON o.idCompte = cp.idCompte
        JOIN
            _adresse ad ON o.idadresse = ad.idadresse
        LEFT JOIN 
            _activite pa ON o.idOffre = pa.idOffre
        LEFT JOIN 
            _parcattractions ppa ON o.idOffre = ppa.idOffre
        LEFT JOIN 
            _restaurant pr ON o.idOffre = pr.idOffre
        LEFT JOIN 
            _visite pv ON o.idOffre = pv.idOffre
        LEFT JOIN 
            _spectacle ps ON o.idOffre = ps.idOffre
        ';
        


        $requete_preparee = $dbh->prepare($requeteSql);
        $requete_preparee->execute();
        $offres = $requete_preparee->fetchAll(PDO::FETCH_ASSOC);
        


        $offresEnLigne = [];
        $offresHorsLigne = [];

        foreach ($offres as $offre) {
            if (isset($offre['enligne']) && $offre['enligne']) {
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
                                <?php 
                                $typeO = str_replace([" ", "'"], '_', strtolower($offre['nomoffre']));
                                echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/$typeO.svg");
                                ?>
                                    <span><?php echo $offre['nomoffre']; ?></span>
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
                                        <img class="color-svg" src="imagesMesOffresPro/schedule.svg"><?php echo $offre['duree'],"min"; ?>
                                    </div>
                                </div>
                            </div>
                            <hr class="solid">
                            <div class="card-footer">
                                <span class="prix"><strong><?php echo $offre['prix'], '€'; ?></strong></span>
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

                                <?php 
                                $typeO = str_replace([" ", "'"], '_', strtolower($offre['nomoffre']));
                                echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/$typeO.svg");
                                ?> 
                                
                                <span><?php echo $offre['nomoffre']; ?></span>
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
                                        <img class="color-svg" src="imagesMesOffresPro/schedule.svg"><?php echo $offre['duree'],"min"; ?>
                                    </div>
                                </div>
                            </div>
                            <hr class="solid">
                            <div class="card-footer">
                                <span class="prix"><strong><?php echo $offre['prix'],"€"; ?></strong></span>
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