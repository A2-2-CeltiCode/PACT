<?php

namespace controlleurs\Offre;
use DateTime;

class Offre
{
    private string $nom;
    private string $typeO;
    private string $ville;
    private string $imageO;
    private ?float $note;
    private ?string $duree;
    private string $nomProprietaire;
    private string $idoffre;
    private string $option;
    private string $ouverture;
    private string $fermeture;

    private string $prix;
    private string $gamme;


    /**
     * @param string $nom
     * @param string $typeO type de l'offre
     * @param string $ville
     * @param string $imageO nom du fichier image
     * @param string $nomProprietaire
     * @param string $idoffre
     * @param string|null $duree durée de l'offre en minute
     * @param string $note
     * @param string $option
     * @param string $ouverture
     * @param string $fermeture
     * @param string $prix
     * @param string $gamme
     */
    public function __construct(string  $nom,
                                string  $typeO,
                                string  $ville,
                                string  $imageO,
                                string  $nomProprietaire,
                                string  $idoffre,
                                ?string $duree,
                                string  $note,
                                string  $option,
                                string  $ouverture,
                                string  $fermeture,
                                string  $prix,
                                string  $gamme) {
        $this->nom = $nom;
        $this->typeO = str_replace([" ", "'"], '_', strtolower($typeO));
        $this->ville = $ville;
        $this->imageO = $imageO;
        $this->note = round(floatval($note), 2);
        $this->nomProprietaire = $nomProprietaire;
        $this->idoffre = $idoffre;
        $this->duree = is_null($duree) ? null : intval($duree);
        $this->option = $option;
        $this->ouverture = date("H:i", strtotime($ouverture));
        $this->fermeture = date("H:i", strtotime($fermeture));
        $this->prix = $prix;
        $this->gamme = $gamme;

    }

    public function getId() {
        return $this->idoffre;
    }

    public function __toString(): string {
        $texte=null;
        $argent=null;
        $currentTime = new DateTime('now', new \DateTimeZone('Europe/Paris'));
        $closingTime = new DateTime($this->fermeture);
        $interval = $currentTime->diff($closingTime);
        $svgIcon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/$this->typeO.svg");
        $svgNote = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/note.svg");
        $svgProprio = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/user.svg");
        $svgClock = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/clock.svg");
        $svgPin = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/mapPin.svg");
        $d = '';
        
        if ($interval->invert == 1 || ($interval->h == 0 && $interval->i > 0)) {
            $texte = "Fermé";
            $id = "ferme";
        }else {
            if ($interval->h < 1 && $interval->invert == 0) {
                $id = "bientot";
                $texte="Bientôt fermé";
            } else{
                $id = "ouvert";
                $texte = "Ouvert";
            }
        }
        
        if($this->typeO != "restaurant"){
            $argent = "$this->prix €";
        }else{
            $argent = $this->gamme;
        }

        if (!is_null($this->duree)) {
            if ($this->duree < 60) {
                $dureeText = "$this->duree minutes";
            } else {
                $dureeText = $this->duree % 60 == 0 ? intdiv($this->duree, 60) . ' heures' : (intdiv($this->duree,
                        60) . "h " . ($this->duree % 60 != 0 ? $this->duree % 60 : ''));
            }
            $d = <<<STRING
<div>
    $svgClock
    <p>$dureeText</p>
</div>
STRING;

        }
        $image = "/ressources/$this->idoffre/images/$this->imageO";
        $image = file_exists($_SERVER['DOCUMENT_ROOT'] . $image) ? $image : "https://placehold.co/512/png?text=image\\nmanquante";

        $nom = strlen($this->nom) >= 32 ? substr($this->nom, 0, 29) . "..." : $this->nom;
        $class = "";
        $star = "";
        $gold = "";

        if ($this->option == "A la une") {
            $class = "une";
            $star = "<img class='star-overlay' alt='relief' src='/ressources/img/relief.png'>";
            $gold = "class='gold'";
        } elseif ($this->option == "En relief") {
            $class = "relief";
            $star = "<img class='star-overlay' alt='relief' src='/ressources/img/relief.png'>";
            $gold = "class='gold'";
        }

        return <<<STRING
        <link rel="stylesheet" href="/controlleurs/Offre/offre.css">
<div class="offre $class">
    
    <a href="../detailsOffre/detailsOffre.php?id=$this->idoffre">
        
        <div class="image-container" style="background-image: url('$image'); background-size: cover; background-position: center;">
        </div>
        <div $gold>
            <div>
                $svgIcon
                <p>$nom</p>
            </div>
            <div>
                <p>$this->note</p>
                $svgNote
            </div>
        </div>
        <div $gold>
            <div>
                $svgPin
                <p>$this->ville</p>
            </div>
            <div>
                $svgProprio
                <p>$this->nomProprietaire</p>
            </div>
            $d
        </div>
        <div $gold>
            <div>
                $svgClock
                <p>$this->ouverture -</p>
                <p>$this->fermeture</p>
            </div>
            <div>
                <p id=$id>$texte</p>
            </div>
            <p>$argent</p>
            
        </div>
        
    </a>
    
</div>
STRING;
    }
}