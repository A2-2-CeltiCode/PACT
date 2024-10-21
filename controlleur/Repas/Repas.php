<?php

/**
 * Class Repas
 * 
 * Représente un repas avec un nom et un prix.
 */
class Repas {
    private $nomRepas;
    private $valPrix;

    /**
     * Constructeur de la classe Repas.
     *
     * @param string $nomRepas
     * @param Prix $valPrix
     */
    public function __construct($nomRepas, Prix $valPrix) {
        $this->nomRepas = $nomRepas;
        $this->valPrix = $valPrix;
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

    /**
     * @return Prix
     */
    public function getValPrix() {
        return $this->valPrix;
    }

    /**
     * @param Prix $valPrix
     */
    public function setValPrix(Prix $valPrix) {
        $this->valPrix = $valPrix;
    }
}
?>
