<?php

class ProposeRestaurant {
    private $idOffre; // Instance de Restaurant
    private $nomRepas; // Instance de Repas

    public function __construct(Restaurant $idOffre, Repas $nomRepas) {
        $this->idOffre = $idOffre;
        $this->nomRepas = $nomRepas;
    }
}
?>
