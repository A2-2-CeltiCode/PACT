<?php

/**
 * Class CompteMembre
 * 
 * Représente un membre avec des informations personnelles et un compte utilisateur associé.
 */
class CompteMembre {
    private $idCompte;
    private $prenom;
    private $nom;
    private $compte;

    /**
     * Constructeur de la classe CompteMembre.
     *
     * @param int $idCompte
     * @param string $prenom
     * @param string $nom
     * @param Compte $compte
     */
    public function __construct($idCompte, $prenom, $nom, Compte $compte) {
        $this->idCompte = $idCompte;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->compte = $compte;
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
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom) {
        $this->nom = $nom;
    }

    /**
     * @return Compte
     */
    public function getCompte() {
        return $this->compte;
    }

    /**
     * @param Compte $compte
     */
    public function setCompte(Compte $compte) {
        $this->compte = $compte;
    }
}
?>
