<?php

/**
 * Class ParcAttraction
 * 
 * Représente un parc d'attractions avec des informations sur sa catégorie, sa durée, son prix, et d'autres détails.
 */
class ParcAttraction {
    private $idOffre;
    private $categorie;
    private $duree;
    private $prix;
    private $planParc;
    private $nbAttractions;
    private $ageMin;
    private $offre;

    /**
     * Constructeur de la classe ParcAttraction.
     *
     * @param int $idOffre
     * @param Categorie $categorie
     * @param Duree $duree
     * @param Prix $prix
     * @param string $planParc
     * @param int $nbAttractions
     * @param int $ageMin
     * @param Offre $offre
     */
    public function __construct($idOffre, Categorie $categorie, Duree $duree, Prix $prix, $planParc, $nbAttractions, $ageMin, Offre $offre) {
        $this->idOffre = $idOffre;
        $this->categorie = $categorie;
        $this->duree = $duree;
        $this->prix = $prix;
        $this->planParc = $planParc;
        $this->nbAttractions = $nbAttractions;
        $this->ageMin = $ageMin;
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
     * @return string
     */
    public function getPlanParc() {
        return $this->planParc;
    }

    /**
     * @param string $planParc
     */
    public function setPlanParc($planParc) {
        $this->planParc = $planParc;
    }

    /**
     * @return int
     */
    public function getNbAttractions() {
        return $this->nbAttractions;
    }

    /**
     * @param int $nbAttractions
     */
    public function setNbAttractions($nbAttractions) {
        $this->nbAttractions = $nbAttractions;
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
