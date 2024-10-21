<?php

class Offre {
    private $idOffre;
    private $comptePro; // Instance de ComptePro
    private $titre;
    private $description;
    private $descriptionDetaillee;
    private $siteInternet;
    private $option; // Instance de Option
    private $forfait; // Instance de Forfait

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
}
?>
