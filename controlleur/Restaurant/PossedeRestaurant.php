<?php

/**
 * Class PossedeRestaurant
 * 
 * Représente une association entre un restaurant et un tag spécifique.
 */
class PossedeRestaurant {
    private $idOffre;
    private $nomTag;

    /**
     * Constructeur de la classe PossedeRestaurant.
     *
     * @param Restaurant $idOffre
     * @param TagRestaurant $nomTag
     */
    public function __construct(Restaurant $idOffre, TagRestaurant $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
    }

    /**
     * @return Restaurant
     */
    public function getIdOffre() {
        return $this->idOffre;
    }

    /**
     * @param Restaurant $idOffre
     */
    public function setIdOffre(Restaurant $idOffre) {
        $this->idOffre = $idOffre;
    }

    /**
     * @return TagRestaurant
     */
    public function getNomTag() {
        return $this->nomTag;
    }

    /**
     * @param TagRestaurant $nomTag
     */
    public function setNomTag(TagRestaurant $nomTag) {
        $this->nomTag = $nomTag;
    }
}
?>
