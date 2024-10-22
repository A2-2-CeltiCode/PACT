<?php

/**
 * Class PossedeVisite
 * 
 * Représente une association entre une visite et un tag spécifique.
 */
class PossedeVisite {
    private $idOffre;
    private $nomTag;

    /**
     * Constructeur de la classe PossedeVisite.
     *
     * @param Visite $idOffre
     * @param TagAutre $nomTag
     */
    public function __construct(Visite $idOffre, TagAutre $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
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
     * @return TagAutre
     */
    public function getNomTag() {
        return $this->nomTag;
    }

    /**
     * @param TagAutre $nomTag
     */
    public function setNomTag(TagAutre $nomTag) {
        $this->nomTag = $nomTag;
    }
}
?>
