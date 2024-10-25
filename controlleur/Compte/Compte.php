<?php

/**
 * Class Compte
 * 
 * Représente un compte utilisateur avec des informations de connexion et une adresse associée.
 */
class Compte {
    private $idCompte;
    private $login;
    private $mdp;
    private $email;
    private $adresse;

    /**
     * Constructeur de la classe Compte.
     *
     * @param int $idCompte
     * @param string $login
     * @param string $mdp
     * @param string $email
     * @param Adresse $adresse
     */
    public function __construct($idCompte, $login, $mdp, $email, Adresse $adresse) {
        $this->idCompte = $idCompte;
        $this->login = $login;
        $this->mdp = $mdp;
        $this->email = $email;
        $this->adresse = $adresse;
    }

    /**
     * @return int
     */
    public function getIdCompte() {
        return $this->idCompte;
    }

    /**
     * @param int $idCompte
     */
    public function setIdCompte($idCompte) {
        $this->idCompte = $idCompte;
    }

    /**
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login) {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getMdp() {
        return $this->mdp;
    }

    /**
     * @param string $mdp
     */
    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return Adresse
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * @param Adresse $adresse
     */
    public function setAdresse(Adresse $adresse) {
        $this->adresse = $adresse;
    }
}
?>
