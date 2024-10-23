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

    /**
     * @param string      $nom
     * @param string      $typeO  type de l'offre
     * @param string      $ville
     * @param string      $imageO nom du fichier image
     * @param string      $nomProprietaire
     * @param string|null $duree  durée de l'offre en minute
     * @param string|null $note
     */
    public function __construct(string  $nom,
                                string  $typeO,
                                string  $ville,
                                string  $imageO,
                                string  $nomProprietaire,
                                ?string $duree,
                                ?string $note = null) {
        //TODO: fix type name
        $this->nom = $nom;
        $this->typeO = str_replace([" ", "'"], '_', strtolower($typeO));
        $this->ville = $ville;
        $this->imageO = $imageO;
        $this->note = floatval($note);
        $this->nomProprietaire = $nomProprietaire;
        $this->duree = is_null($duree) ? null : intval($duree);
    }

    public function __toString(): string {
        $svgIcon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/icon/$this->typeO.svg");
        $svgNote = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/icon/note.svg");
        $svgProprio = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/icon/user.svg");
        $svgClock = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/icon/clock.svg");
        $svgPin = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/icon/mapPin.svg");
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
    <img alt="" src="/assets/img/offres/$this->imageO.png">
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