<?php

/**
 * Class Categorie
 * 
 * Représente une catégorie avec un nom.
 */
class Categorie {
    private $nomCategorie;

    /**
     * Constructeur de la classe Categorie.
     *
     * @param string $nomCategorie
     */
    public function __construct($nomCategorie) {
        $this->nomCategorie = $nomCategorie;
    }

    /**
     * @return string
     */
    public function getNomCategorie() {
        return $this->nomCategorie;
    }

    /**
     * @param string $nomCategorie
     */
    public function setNomCategorie($nomCategorie) {
        $this->nomCategorie = $nomCategorie;
    }
}
?>
