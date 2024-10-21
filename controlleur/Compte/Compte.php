<?php

class Compte {
    private $idCompte;
    private $login;
    private $mdp;
    private $email;
    private $adresse; // Instance d'Adresse

    public function __construct($idCompte, $login, $mdp, $email, Adresse $adresse) {
        $this->idCompte = $idCompte;
        $this->login = $login;
        $this->mdp = $mdp;
        $this->email = $email;
        $this->adresse = $adresse;
    }
}
?>
