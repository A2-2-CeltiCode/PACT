<?php

class Activite {
    private $idOffre;
    private $categorie; // Instance de Categorie
    private $duree; // Instance de Duree
    private $prix; // Instance de Prix
    private $ageMin;
    private $prestation;
    private $offre; // Instance de Offre

    public function __construct($idOffre, Categorie $categorie, Duree $duree, Prix $prix, $ageMin, $prestation, Offre $offre) {
        $this->idOffre = $idOffre;
        $this->categorie = $categorie;
        $this->duree = $duree;
        $this->prix = $prix;
        $this->ageMin = $ageMin;
        $this->prestation = $prestation;
        $this->offre = $offre;
    }
}
?>
