<?php

class CompteProPrive {
    private $idCompte;
    private $numSiren;
    private $comptePro; // Instance de ComptePro

    public function __construct($idCompte, $numSiren, ComptePro $comptePro) {
        $this->idCompte = $idCompte;
        $this->numSiren = $numSiren;
        $this->comptePro = $comptePro;
    }
}
?>
