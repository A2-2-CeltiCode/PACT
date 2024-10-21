<?php

/**
 * Class PossedeSpectacle
 * 
 * Représente une association entre un spectacle et un tag spécifique.
 */
class PossedeSpectacle {
    private $idOffre;
    private $nomTag;

    /**
     * Constructeur de la classe PossedeSpectacle.
     *
     * @param Spectacle $idOffre
     * @param TagAutre $nomTag
     */
    public function __construct(Spectacle $idOffre, TagAutre $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
    }

    /**
     * @return Spectacle
     */
    public function getIdOffre() {
        return $this->idOffre;
    }

    /**
     * @param Spectacle $idOffre
     */
    public function setIdOffre(Spectacle $idOffre) {
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
