<?php

class ParcAttraction {
    private $idOffre;
    private $categorie; // Instance de Categorie
    private $duree; // Instance de Duree
    private $prix; // Instance de Prix
    private $planParc;
    private $nbAttractions;
    private $ageMin;
    private $offre; // Instance de Offre

    public function __construct($idOffre, Categorie $categorie, Duree $duree, Prix $prix, $planParc, $nbAttractions, $ageMin, Offre $offre) {
        $this->idOffre = $idOffre;
        $this->categorie = $categorie;
        $this->duree = $duree;
        $this->prix = $prix;
        $this->planParc = $planParc;
        $this->nbAttractions = $nbAttractions;
        $this->ageMin = $ageMin;
        $this->offre = $offre;
    }
}
?>
