<?php

/**
 * Class Repas
 * 
 * Représente un repas avec un nom et un prix.
 */
class Repas {
    private $nomRepas;

    /**
     * Constructeur de la classe Repas.
     *
     * @param string $nomRepas
     * @param Prix $valPrix
     */
    public function __construct($nomRepas) {
        $this->nomRepas = $nomRepas;
    }

    /**
     * @return string
     */
    public function getNomRepas() {
        return $this->nomRepas;
    }

    /**
     * @param string $nomRepas
     */
    public function setNomRepas($nomRepas) {
        $this->nomRepas = $nomRepas;
    }
}
?>
