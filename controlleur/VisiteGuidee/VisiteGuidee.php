<?php

/**
 * Class VisiteGuidee
 * 
 * Représente une visite guidée associée à une langue spécifique.
 */
class VisiteGuidee {
    private $idOffre;
    private $nomLangage;
    private $visite;

    /**
     * Constructeur de la classe VisiteGuidee.
     *
     * @param Visite $idOffre
     * @param Langage $nomLangage
     */
    public function __construct(Visite $idOffre, Langage $nomLangage) {
        $this->idOffre = $idOffre;
        $this->nomLangage = $nomLangage;
    }

    /**
     * @return Visite
     */
    public function getIdOffre() {
        return $this->idOffre;
    }

    /**
     * @param Visite $idOffre
     */
    public function setIdOffre(Visite $idOffre) {
        $this->idOffre = $idOffre;
    }

    /**
     * @return Langage
     */
    public function getNomLangage() {
        return $this->nomLangage;
    }

    /**
     * @param Langage $nomLangage
     */
    public function setNomLangage(Langage $nomLangage) {
        $this->nomLangage = $nomLangage;
    }

    /**
     * @return Visite
     */
    public function getVisite() {
        return $this->visite;
    }

    /**
     * @param Visite $visite
     */
    public function setVisite(Visite $visite) {
        $this->visite = $visite;
    }
}
?>
