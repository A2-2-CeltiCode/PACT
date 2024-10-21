<?php

/**
 * Class PossedeParcAttraction
 * 
 * Représente une association entre un parc d'attractions et un tag.
 */
class PossedeParcAttraction {
    private $idOffre;
    private $nomTag;

    /**
     * Constructeur de la classe PossedeParcAttraction.
     *
     * @param ParcAttraction $idOffre
     * @param TagAutre $nomTag
     */
    public function __construct(ParcAttraction $idOffre, TagAutre $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
    }

    /**
     * @return ParcAttraction
     */
    public function getIdOffre() {
        return $this->idOffre;
    }

    /**
     * @param ParcAttraction $idOffre
     */
    public function setIdOffre(ParcAttraction $idOffre) {
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
