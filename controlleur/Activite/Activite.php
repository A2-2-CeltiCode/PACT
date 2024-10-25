<?php

/**
 * Class Activite
 * 
 * Représente une activité avec des informations détaillées telles que l'offre, la catégorie, la durée, le prix, l'âge minimum, la prestation et l'offre associée.
 */
class Activite {
    private $idOffre;
    private $categorie;
    private $duree;
    private $prix;
    private $ageMin;
    private $prestation;
    private $offre;

    /**
     * Constructeur de la classe Activite.
     *
     * @param int $idOffre
     * @param Categorie $categorie
     * @param Duree $duree
     * @param Prix $prix
     * @param int $ageMin
     * @param string $prestation
     * @param Offre $offre
     */
    public function __construct($idOffre, Categorie $categorie, Duree $duree, Prix $prix, $ageMin, $prestation, Offre $offre) {
        $this->idOffre = $idOffre;
        $this->categorie = $categorie;
        $this->duree = $duree;
        $this->prix = $prix;
        $this->ageMin = $ageMin;
        $this->prestation = $prestation;
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
    public function getAgeMin() {
        return $this->ageMin;
    }

    /**
     * @param int $ageMin
     */
    public function setAgeMin($ageMin) {
        $this->ageMin = $ageMin;
    }

    /**
     * @return string
     */
    public function getPrestation() {
        return $this->prestation;
    }

    /**
     * @param string $prestation
     */
    public function setPrestation($prestation) {
        $this->prestation = $prestation;
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
