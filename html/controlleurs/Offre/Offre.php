<?php

namespace controlleurs\Offre;
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
     */
    public function __construct(string  $nom,
                                string  $typeO,
                                string  $ville,
                                string  $imageO,
                                string  $nomProprietaire,
                                string  $idoffre,
                                ?string $duree,
                                string  $note,
                                string  $option) {
        $this->nom = $nom;
        $this->typeO = str_replace([" ", "'"], '_', strtolower($typeO));
        $this->ville = $ville;
        $this->imageO = $imageO;
        $this->note = round(floatval($note), 2);
        $this->nomProprietaire = $nomProprietaire;
        $this->idoffre = $idoffre;
        $this->duree = is_null($duree) ? null : intval($duree);
        $this->option = $option;
    }

    public function __toString(): string {
        $svgIcon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/$this->typeO.svg");
        $svgNote = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/note.svg");
        $svgProprio = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/user.svg");
        $svgClock = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/clock.svg");
        $svgPin = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/ressources/icone/mapPin.svg");
        $d = '';
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
        if ($this->option == "A la une") {
            $class = "une";
        } else if ($this->option == "En relief") {
            $class = "relief";
        }

        return <<<STRING
<div class="offre"><a href="../detailsOffre/detailsOffre.php?id=$this->idoffre">
    <img alt="" height="300px" width="300px" src="$image">
    <div>
        <div>
            $svgIcon
            <p>$nom</p>
        </div>
        <div>
            <p>$this->note</p>
            $svgNote
        </div>
    </div>
    <div>
        <div>
            $svgPin
            <p>$this->ville</p>
        </div>
        <div>
            $svgProprio
            <p>$this->nomProprietaire</p>
        </div>
        $d
    </div></a>
</div>
STRING;

    }
}