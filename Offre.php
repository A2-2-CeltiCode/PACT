<?php

class Offre
{
    private string $nom;
    private string $typeO;
    private string $ville;
    private string $imageO;
    private float $note;
    private ?int $duree;
    private string $nomProprietaire;

    /**
     * @param string   $nom
     * @param string   $typeO  type de l'offre
     * @param string   $ville
     * @param string   $imageO nom du fichier image
     * @param string   $note
     * @param string   $nomProprietaire
     * @param int|null $duree  durée de l'offre en minute
     */
    public function __construct(string $nom,
                                string $typeO,
                                string $ville,
                                string $imageO,
                                string $note,
                                string $nomProprietaire,
                                ?int   $duree) {
        $this->nom = $nom;
        $this->typeO = $typeO;
        $this->ville = $ville;
        $this->imageO = $imageO;
        $this->note = $note;
        $this->nomProprietaire = $nomProprietaire;
        $this->duree = $duree;
    }

    public function __toString(): string {
        $svgIcon = file_get_contents("assets/icon/$this->typeO.svg");
        $svgNote = file_get_contents("assets/icon/note.svg");
        $svgProprio = file_get_contents("assets/icon/user.svg");
        $svgClock = file_get_contents("assets/icon/clock.svg");
        $svgPin = file_get_contents("assets/icon/mapPin.svg");
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
        return <<<STRING
<div class="offre">
    <img alt="" src="assets/img/offres/$this->imageO.png">
    <div>
        <div>
            $svgIcon
            <p>$this->nom</p>
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
    </div>
</div>
STRING;

    }
}