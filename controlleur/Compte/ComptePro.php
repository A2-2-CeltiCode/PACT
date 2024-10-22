<?php

/**
 * Class ComptePro
 * 
 * Représente un compte professionnel avec des informations légales et bancaires associées.
 */
class ComptePro {
    private $idCompte;
    private $denominationSociale;
    private $raisonSocialePro;
    private $banqueRib;
    private $compte;

    /**
     * Constructeur de la classe ComptePro.
     *
     * @param int $idCompte
     * @param string $denominationSociale
     * @param string $raisonSocialePro
     * @param string $banqueRib
     * @param Compte $compte
     */
    public function __construct($idCompte, $denominationSociale, $raisonSocialePro, $banqueRib, Compte $compte) {
        $this->idCompte = $idCompte;
        $this->denominationSociale = $denominationSociale;
        $this->raisonSocialePro = $raisonSocialePro;
        $this->banqueRib = $banqueRib;
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
    public function getDenominationSociale() {
        return $this->denominationSociale;
    }

    /**
     * @param string $denominationSociale
     */
    public function setDenominationSociale($denominationSociale) {
        $this->denominationSociale = $denominationSociale;
    }

    /**
     * @return string
     */
    public function getRaisonSocialePro() {
        return $this->raisonSocialePro;
    }

    /**
     * @param string $raisonSocialePro
     */
    public function setRaisonSocialePro($raisonSocialePro) {
        $this->raisonSocialePro = $raisonSocialePro;
    }

    /**
     * @return string
     */
    public function getBanqueRib() {
        return $this->banqueRib;
    }

    /**
     * @param string $banqueRib
     */
    public function setBanqueRib($banqueRib) {
        $this->banqueRib = $banqueRib;
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
