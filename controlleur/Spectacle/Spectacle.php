<?php

class Spectacle {
    private $idOffre;
    private $categorie; // Instance de Categorie
    private $duree; // Instance de Duree
    private $prix; // Instance de Prix
    private $capacite;
    private $offre; // Instance de Offre

    public function __construct($idOffre, Categorie $categorie, Duree $duree, Prix $prix, $capacite, Offre $offre) {
        $this->idOffre = $idOffre;
        $this->categorie = $categorie;
        $this->duree = $duree;
        $this->prix = $prix;
        $this->capacite = $capacite;
        $this->offre = $offre;
    }
}
?>
