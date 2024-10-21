<?php

/**
 * Class Prix
 * 
 * Représente un prix avec une valeur.
 */
class Prix {
    private $valPrix;

    /**
     * Constructeur de la classe Prix.
     *
     * @param float $valPrix
     */
    public function __construct($valPrix) {
        $this->valPrix = $valPrix;
    }

    /**
     * @return float
     */
    public function getValPrix() {
        return $this->valPrix;
    }

    /**
     * @param float $valPrix
     */
    public function setValPrix($valPrix) {
        $this->valPrix = $valPrix;
    }
}
?>
