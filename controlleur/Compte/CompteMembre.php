<?php

class CompteMembre {
    private $idCompte;
    private $prenom;
    private $nom;
    private $compte; // Instance de Compte

    public function __construct($idCompte, $prenom, $nom, Compte $compte) {
        $this->idCompte = $idCompte;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->compte = $compte;
    }
}
?>
