<?php

class CompteProPublic {
    private $idCompte;
    private $comptePro; // Instance de ComptePro

    public function __construct($idCompte, ComptePro $comptePro) {
        $this->idCompte = $idCompte;
        $this->comptePro = $comptePro;
    }
}
?>
