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

    /**
     * @param string      $nom
     * @param string      $typeO  type de l'offre
     * @param string      $ville
     * @param string      $imageO nom du fichier image
     * @param string      $nomProprietaire
     * @param string      $idoffre
     * @param string|null $duree  durée de l'offre en minute
     * @param string|null $note
     */
    public function __construct(string  $nom,
                                string  $typeO,
                                string  $ville,
                                string  $imageO,
                                string  $nomProprietaire,
                                string  $idoffre,
                                ?string $duree,
                                ?string $note = null) {
        $this->nom = $nom;
        $this->typeO = str_replace([" ", "'"], '_', strtolower($typeO));
        $this->ville = $ville;
        $this->imageO = $imageO;
        $this->note = floatval($note);
        $this->nomProprietaire = $nomProprietaire;
        $this->idoffre = $idoffre;
        $this->duree = is_null($duree) ? null : intval($duree);
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
        $image = "/ressources/img/offres/$this->idoffre/$this->imageO";
        $image = file_exists($_SERVER['DOCUMENT_ROOT'] . $image) ? $image : "https://placehold.co/512/png?text=image\\nmanquante";

        $nom = strlen($this->nom) >= 32 ? substr($this->nom, 0, 29) . "..." : $this->nom;

        return <<<STRING
<div class="offre"><a href="/pages/visiteur/listeOffres/listeOffres.php?id=$this->idoffre">
    <img alt="" src="$image">
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