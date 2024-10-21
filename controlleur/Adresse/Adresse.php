<?php

/**
 * Class Adresse
 * 
 * Représente une adresse avec des informations de localisation et de contact.
 */
class Adresse {
    private $codePostal;
    private $ville;
    private $nomRue;
    private $numRue;
    private $numTel;

    /**
     * Constructeur de la classe Adresse.
     *
     * @param string $codePostal
     * @param string $ville
     * @param string $nomRue
     * @param string|null $numRue
     * @param string|null $numTel
     */
    public function __construct($codePostal, $ville, $nomRue, $numRue = null, $numTel = null) {
        $this->codePostal = $codePostal;
        $this->ville = $ville;
        $this->nomRue = $nomRue;
        $this->numRue = $numRue;
        $this->numTel = $numTel;
    }

    /**
     * @return string
     */
    public function getCodePostal() {
        return $this->codePostal;
    }

    /**
     * @param string $codePostal
     */
    public function setCodePostal($codePostal) {
        $this->codePostal = $codePostal;
    }

    /**
     * @return string
     */
    public function getVille() {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville) {
        $this->ville = $ville;
    }

    /**
     * @return string
     */
    public function getNomRue() {
        return $this->nomRue;
    }

    /**
     * @param string $nomRue
     */
    public function setNomRue($nomRue) {
        $this->nomRue = $nomRue;
    }

    /**
     * @return string|null
     */
    public function getNumRue() {
        return $this->numRue;
    }

    /**
     * @param string|null $numRue
     */
    public function setNumRue($numRue) {
        $this->numRue = $numRue;
    }

    /**
     * @return array
     * 
     * Retourne toute l'adresse.
     */
     public function getAdresseEntier() {
        return $this->numRue . ' ' . $this->nomRue . ', ' . $this->codePostal . ' ' . $this->ville;
    }
    

    /**
     * @return string|null
     */
    public function getNumTel() {
        return $this->numTel;
    }

    /**
     * @param string|null $numTel
     */
    public function setNumTel($numTel) {
        $this->numTel = $numTel;
    }
}
?>
