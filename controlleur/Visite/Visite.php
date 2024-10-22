<?php

/**
 * Class Visite
 * 
 * Représente une visite avec des informations sur le prix, la durée, la date/heure et l'offre associée.
 */
class Visite {
    private $idOffre;
    private $prix;
    private $duree;
    private $dateEtHeure;
    private $offre;

    /**
     * Constructeur de la classe Visite.
     *
     * @param int $idOffre
     * @param Prix $prix
     * @param Duree $duree
     * @param string $dateEtHeure
     * @param Offre $offre
     */
    public function __construct($idOffre, Prix $prix, Duree $duree, $dateEtHeure, Offre $offre) {
        $this->idOffre = $idOffre;
        $this->prix = $prix;
        $this->duree = $duree;
        $this->dateEtHeure = $dateEtHeure;
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
     * @return string
     */
    public function getDateEtHeure() {
        return $this->dateEtHeure;
    }

    /**
     * @param string $dateEtHeure
     */
    public function setDateEtHeure($dateEtHeure) {
        $this->dateEtHeure = $dateEtHeure;
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
