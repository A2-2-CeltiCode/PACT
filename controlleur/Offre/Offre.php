<?php

/**
 * Class Offre
 * 
 * Représente une offre associée à un compte professionnel, avec un titre, description, et des options.
 */
class Offre {
    private $idOffre;
    private $comptePro;
    private $titre;
    private $description;
    private $descriptionDetaillee;
    private $siteInternet;
    private $option;
    private $forfait;

    /**
     * Constructeur de la classe Offre.
     *
     * @param int $idOffre
     * @param ComptePro $comptePro
     * @param string $titre
     * @param string $description
     * @param string $descriptionDetaillee
     * @param string $siteInternet
     * @param Option $option
     * @param Forfait $forfait
     */
    public function __construct($idOffre, ComptePro $comptePro, $titre, $description, $descriptionDetaillee, $siteInternet, Option $option, Forfait $forfait) {
        $this->idOffre = $idOffre;
        $this->comptePro = $comptePro;
        $this->titre = $titre;
        $this->description = $description;
        $this->descriptionDetaillee = $descriptionDetaillee;
        $this->siteInternet = $siteInternet;
        $this->option = $option;
        $this->forfait = $forfait;
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
     * @return ComptePro
     */
    public function getComptePro() {
        return $this->comptePro;
    }

    /**
     * @param ComptePro $comptePro
     */
    public function setComptePro(ComptePro $comptePro) {
        $this->comptePro = $comptePro;
    }

    /**
     * @return string
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre) {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescriptionDetaillee() {
        return $this->descriptionDetaillee;
    }

    /**
     * @param string $descriptionDetaillee
     */
    public function setDescriptionDetaillee($descriptionDetaillee) {
        $this->descriptionDetaillee = $descriptionDetaillee;
    }

    /**
     * @return string
     */
    public function getSiteInternet() {
        return $this->siteInternet;
    }

    /**
     * @param string $siteInternet
     */
    public function setSiteInternet($siteInternet) {
        $this->siteInternet = $siteInternet;
    }

    /**
     * @return Option
     */
    public function getOption() {
        return $this->option;
    }

    /**
     * @param Option $option
     */
    public function setOption(Option $option) {
        $this->option = $option;
    }

    /**
     * @return Forfait
     */
    public function getForfait() {
        return $this->forfait;
    }

    /**
     * @param Forfait $forfait
     */
    public function setForfait(Forfait $forfait) {
        $this->forfait = $forfait;
    }
}
?>
