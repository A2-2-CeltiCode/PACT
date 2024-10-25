<?php

class Restaurant {
    private $idOffre;
    private $nomCategorie; // Instance de Categorie
    private $carteRestaurant;
    private $gammeRestaurant;
    private $offre; // Instance de Offre

    public function __construct($idOffre, Categorie $nomCategorie, $carteRestaurant, $gammeRestaurant, Offre $offre) {
        $this->idOffre = $idOffre;
        $this->nomCategorie = $nomCategorie;
        $this->carteRestaurant = $carteRestaurant;
        $this->gammeRestaurant = $gammeRestaurant;
        $this->offre = $offre;
    }
}
?>
