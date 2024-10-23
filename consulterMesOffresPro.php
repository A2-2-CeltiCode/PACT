<!DOCTYPE html>
<html>
    <?php require_once "components/Input/Input.php"?>
    <?php require_once "components/Button/Button.php"?>
    <?php require_once "components/Header/Header.php"?>
    
    <?php require_once "components/Footer/Footer.php"?>
    <head>
        <link rel="stylesheet" type="text/css" href="./consulterMesOffresPro.css">
        <link rel="stylesheet" href="./variable.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="etatOffre.js" defer></script>

    </head>

    

    <?php Header::render(HeaderType::Pro);?>

    <body>

    
        <header class="hautDePage">
            <div class="titre">Mes Offres</div>
            <div id="statue">
                <span class="option active" id="on">En ligne</span>
                <span class="option" id="off">Hors ligne</span>
            </div>
            <a href="creerUneOffre.php" class="buttons" >
                <?php Button::render(type:ButtonType::Pro,text:"Créer une offre")?>
            </a>
        </header>

        <section class="sectionCarteEnligne" id="sectionEnLigne">
            <div class="carteOffre">
                <img class="imgcarte" alt="pink.jpg" height="276px" width="276px" src="imagesMesOffresPro/pink.jpg" >
                <div class="contenuecarte">
                    <div class="card-header">
                        <div>
                            <img class="color-svgtitle" src="imagesMesOffresPro/map.svg"><span>Visite</span>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <span class="title">Cathédrale Saint Corention</span>
                      
                    <div class="detail">
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/person.svg"> Cédric Hardi
                        </div>
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/location.svg">Qumpier
                        </div>
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/schedule.svg">2,5 heures
                        </div>
                    </div>
                    </div>
                    <hr class="solid">

                    <div class="card-footer">
                        <span class="prix"><strong>Gratuit</strong></span>
                        
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/bubble.svg"> 
                            <span id="marginCommentaire">12</span>
                            <img class="color-svg" src="imagesMesOffresPro/bookmark.svg"> 
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="buttons">
               <a href="modifierOffre.php"> <?php Button::render(type:ButtonType::Pro,text:"Modifier")?></a>
               <a href="voirLesAvis.php"> <?php Button::render(type:ButtonType::Pro,text:"Voir les avis")?></a>
            </div>


                <div class="carteOffre">
                <img class="imgcarte" alt="dusk.jpg" height="276px" width="276px" src="imagesMesOffresPro/dusk.jpg" >
                <div class="contenuecarte">
                    <div class="card-header">
                        <div>
                            <img class="color-svgtitle" src="imagesMesOffresPro/map.svg"><span>Visite</span>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <span class="title">Cathédrale Saint Corention</span>
                      
                    <div class="detail">
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/person.svg"> Cédric Hardi
                        </div>
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/location.svg">Qumpier
                        </div>
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/schedule.svg">2,5 heures
                        </div>
                    </div>
                    </div>
                    <hr class="solid">

                    <div class="card-footer">
                        <span class="prix"><strong>Gratuit</strong></span>
                        
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/bubble.svg"> 
                            <span id="marginCommentaire">12</span>
                            <img class="color-svg" src="imagesMesOffresPro/bookmark.svg"> 
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="buttons">
                <?php Button::render(type:ButtonType::Pro,text:"Modifier")?>
                <?php Button::render(type:ButtonType::Pro,text:"Voir les avis")?>
            </div>




                <div class="carteOffre">
                    <img class="imgcarte" alt="landscape.jpg" height="276px" width="276px" src="imagesMesOffresPro/landscape.jpg" >
                <div class="contenuecarte">
                    <div class="card-header">
                        <div>
                            <img class="color-svgtitle" src="imagesMesOffresPro/map.svg"><span>Visite</span>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <span class="title">Cathédrale Saint Corention</span>
                      
                    <div class="detail">
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/person.svg"> Cédric Hardi
                        </div>
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/location.svg">Qumpier
                        </div>
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/schedule.svg">2,5 heures
                        </div>
                    </div>
                    </div>
                <hr class="solid">

                    <div class="card-footer">
                        <span class="prix"><strong>Gratuit</strong></span>
                        
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/bubble.svg"> 
                            <span id="marginCommentaire">12</span>
                            <img class="color-svg" src="imagesMesOffresPro/bookmark.svg"> 
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="buttons">
                <?php Button::render(type:ButtonType::Pro,text:"Modifier")?>
                <?php Button::render(type:ButtonType::Pro,text:"Voir les avis")?>
            </div>
           
        </section>

        <section class="sectionCarteHorsLigne" id="sectionHorsLigne" style="display: none;">
        <div class="carteOffre">
                <img class="imgcarte" alt="hiver.jpg" height="276px" width="276px" src="imagesMesOffresPro/hiver.jpg" >
                <div class="contenuecarte">
                    <div class="card-header">
                        <div>
                            <img class="color-svgtitle" src="imagesMesOffresPro/map.svg"><span>Visite</span>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <span class="title">Cathédrale Saint Corention</span>
                      
                    <div class="detail">
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/person.svg"> Cédric Hardi
                        </div>
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/location.svg">Qumpier
                        </div>
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/schedule.svg">2,5 heures
                        </div>
                    </div>
                    </div>
                    <hr class="solid">

                    <div class="card-footer">
                        <span class="prix"><strong>Gratuit</strong></span>
                        
                        <div class="composantdetail">
                            <img class="color-svg" src="imagesMesOffresPro/bubble.svg"> 
                            <span id="marginCommentaire">12</span>
                            <img class="color-svg" src="imagesMesOffresPro/bookmark.svg"> 
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="buttons">
                <?php Button::render(type:ButtonType::Pro,text:"Modifier")?>
                <?php Button::render(type:ButtonType::Pro,text:"Voir les avis")?>
            </div>

        </section>


    </body>


    <footer>
    <a href="creationComptePro.php" class="lien-bouton"><button>DEVENIR MEMBRE</button></a>
        <div class="liens-importants">
            <a href="mentions.php">Mentions Légales</a>
            <a href="quiSommeNous.php">Qui sommes nous ?</a>
            <a href="condition.php">Conditions Générales</a>
        </div>
        <div class="icones-reseaux-sociaux">
            <a href="https://www.facebook.com/"><img src="imagesMesOffresPro/facebook.svg" alt="Icon facebook" style="vertical-align: middle;"></a>
            <a href="https://x.com/TripEnArvorPACT"><img src="imagesMesOffresPro/twitter.svg" alt="Icon X" style="vertical-align: middle;"></a>
            <a href="https://www.instagram.com/pactlannion/"><img src="imagesMesOffresPro/instagram.svg" alt="Icon instagram" style="vertical-align: middle;"></i></a>
        </div>
        <p>© 2024 PACT, Inc.</p>

        <!-- Lien pour remonter en haut de la page avec une icône -->
        <a href="#entete" class="remonter-page"><i class="fas fa-arrow-up"></i></a>

    </footer>






</html>