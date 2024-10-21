<?php

class Repas {
    private $nomRepas;
    private $valPrix; // Instance de Prix

    public function __construct($nomRepas, Prix $valPrix) {
        $this->nomRepas = $nomRepas;
        $this->valPrix = $valPrix;
    }
}
?>
