<?php

/**
 * Class Forfait
 * 
 * Représente un forfait avec un nom.
 */
class Forfait {
    private $nomForfait;

    /**
     * Constructeur de la classe Forfait.
     *
     * @param string $nomForfait
     */
    public function __construct($nomForfait) {
        $this->nomForfait = $nomForfait;
    }

    /**
     * @return string
     */
    public function getNomForfait() {
        return $this->nomForfait;
    }

    /**
     * @param string $nomForfait
     */
    public function setNomForfait($nomForfait) {
        $this->nomForfait = $nomForfait;
    }
}
?>
