<?php

/**
 * Class Spectacle
 * 
 * Représente un spectacle avec des informations sur la catégorie, la durée, le prix, la capacité et l'offre associée.
 */
class Spectacle {
    private $idOffre;
    private $categorie;
    private $duree;
    private $prix;
    private $capacite;
    private $offre;

    /**
     * Constructeur de la classe Spectacle.
     *
     * @param int $idOffre
     * @param Categorie $categorie
     * @param Duree $duree
     * @param Prix $prix
     * @param int $capacite
     * @param Offre $offre
     */
    public function __construct($idOffre, Categorie $categorie, Duree $duree, Prix $prix, $capacite, Offre $offre) {
        $this->idOffre = $idOffre;
        $this->categorie = $categorie;
        $this->duree = $duree;
        $this->prix = $prix;
        $this->capacite = $capacite;
        $this->offre = $offre;
    }

    /**
     * @return int
     */
    public function getIdOffre() {
        return $this->idOffre;
    }

    /**
     * @param int $idOffre
     */
    public function setIdOffre($idOffre) {
        $this->idOffre = $idOffre;
    }

    /**
     * @return Categorie
     */
    public function getCategorie() {
        return $this->categorie;
    }

    /**
     * @param Categorie $categorie
     */
    public function setCategorie(Categorie $categorie) {
        $this->categorie = $categorie;
    }

    /**
     * @return Duree
     */
    public function getDuree() {
        return $this->duree;
    }

    /**
     * @param Duree $duree
     */
    public function setDuree(Duree $duree) {
        $this->duree = $duree;
    }

    /**
     * @return Prix
     */
    public function getPrix() {
        return $this->prix;
    }

    /**
     * @param Prix $prix
     */
    public function setPrix(Prix $prix) {
        $this->prix = $prix;
    }

    /**
     * @return int
     */
    public function getCapacite() {
        return $this->capacite;
    }

    /**
     * @param int $capacite
     */
    public function setCapacite($capacite) {
        $this->capacite = $capacite;
    }

    /**
     * @return Offre
     */
    public function getOffre() {
        return $this->offre;
    }

    /**
     * @param Offre $offre
     */
    public function setOffre(Offre $offre) {
        $this->offre = $offre;
    }
}
?>
