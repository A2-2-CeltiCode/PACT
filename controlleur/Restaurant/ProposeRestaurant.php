<?php

/**
 * Class ProposeRestaurant
 * 
 * Représente une association entre un restaurant et un repas proposé.
 */
class ProposeRestaurant {
    private $idOffre;
    private $nomRepas;

    /**
     * Constructeur de la classe ProposeRestaurant.
     *
     * @param Restaurant $idOffre
     * @param Repas $nomRepas
     */
    public function __construct(Restaurant $idOffre, Repas $nomRepas) {
        $this->idOffre = $idOffre;
        $this->nomRepas = $nomRepas;
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
     * @return Repas
     */
    public function getNomRepas() {
        return $this->nomRepas;
    }

    /**
     * @param Repas $nomRepas
     */
    public function setNomRepas(Repas $nomRepas) {
        $this->nomRepas = $nomRepas;
    }
}
?>
