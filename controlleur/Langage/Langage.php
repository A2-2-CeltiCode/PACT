<?php

/**
 * Class Langage
 * 
 * Représente un langage avec un nom.
 */
class Langage {
    private $nomLangage;

    /**
     * Constructeur de la classe Langage.
     *
     * @param string $nomLangage
     */
    public function __construct($nomLangage) {
        $this->nomLangage = $nomLangage;
    }

    /**
     * @return string
     */
    public function getNomLangage() {
        return $this->nomLangage;
    }

    /**
     * @param string $nomLangage
     */
    public function setNomLangage($nomLangage) {
        $this->nomLangage = $nomLangage;
    }
}
?>
