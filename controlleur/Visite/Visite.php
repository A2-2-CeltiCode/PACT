<?php

class Visite {
    private $idOffre;
    private $prix; // Instance de Prix
    private $duree; // Instance de Duree
    private $dateEtHeure;
    private $offre; // Instance de Offre

    public function __construct($idOffre, Prix $prix, Duree $duree, $dateEtHeure, Offre $offre) {
        $this->idOffre = $idOffre;
        $this->prix = $prix;
        $this->duree = $duree;
        $this->dateEtHeure = $dateEtHeure;
        $this->offre = $offre;
    }
}
?>
