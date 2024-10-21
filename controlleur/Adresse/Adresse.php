<?php

class Adresse {
    private $codePostal;
    private $ville;
    private $nomRue;
    private $numRue;
    private $numTel;

    public function __construct($codePostal, $ville, $nomRue, $numRue = null, $numTel = null) {
        $this->codePostal = $codePostal;
        $this->ville = $ville;
        $this->nomRue = $nomRue;
        $this->numRue = $numRue;
        $this->numTel = $numTel;
    }
}
?>
