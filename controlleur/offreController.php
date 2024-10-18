<?php
// offre.php

class Offre
{
    private $idOffre;
    private $titre;
    private $description;
    private $descriptionDetaillee;
    private $siteInternet;
    private $nomOption;
    private $nomForfait;

    // Constructeur
    public function __construct($idOffre, $titre, $description, $descriptionDetaillee, $siteInternet, $nomOption, $nomForfait)
    {
        $this->idOffre = $idOffre;
        $this->titre = $titre;
        $this->description = $description;
        $this->descriptionDetaillee = $descriptionDetaillee;
        $this->siteInternet = $siteInternet;
        $this->nomOption = $nomOption;
        $this->nomForfait = $nomForfait;
    }

    // Méthode pour récupérer une offre en fonction de son ID
    public static function getOfferById($pdo, $idOffre)
    {
        $stmt = $pdo->prepare("SELECT * FROM _offre WHERE idOffre = :idOffre");
        $stmt->execute(['idOffre' => $idOffre]);
        $row = $stmt->fetch();

        if ($row) {
            return new Offre(
                $row['idOffre'],
                $row['titre'],
                $row['description'],
                $row['descriptionDetaillee'],
                $row['siteInternet'],
                $row['nomOption'],
                $row['nomForfait']
            );
        } else {
            return null;
        }
    }

    // Méthodes pour accéder aux propriétés de l'offre (getters)
    public function getIdOffre() { return $this->idOffre; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getDescriptionDetaillee() { return $this->descriptionDetaillee; }
    public function getSiteInternet() { return $this->siteInternet; }
    public function getNomOption() { return $this->nomOption; }
    public function getNomForfait() { return $this->nomForfait; }
}