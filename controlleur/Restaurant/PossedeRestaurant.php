<?php

class PossedeRestaurant {
    private $idOffre; // Instance de Restaurant
    private $nomTag; // Instance de TagRestaurant

    public function __construct(Restaurant $idOffre, TagRestaurant $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
    }
}
?>
