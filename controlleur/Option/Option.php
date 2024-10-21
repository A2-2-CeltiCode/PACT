<?php

/**
 * Class Option
 * 
 * Représente une option avec un nom.
 */
class Option {
    private $nomOption;

    /**
     * Constructeur de la classe Option.
     *
     * @param string $nomOption
     */
    public function __construct($nomOption) {
        $this->nomOption = $nomOption;
    }

    /**
     * @return string
     */
    public function getNomOption() {
        return $this->nomOption;
    }

    /**
     * @param string $nomOption
     */
    public function setNomOption($nomOption) {
        $this->nomOption = $nomOption;
    }
}
?>
