<?php

/**
 * Class PossedeActivite
 * 
 * Représente la relation entre une activité et un tag associé.
 */
class PossedeActivite {
    private $idOffre;
    private $nomTag;

    /**
     * Constructeur de la classe PossedeActivite.
     *
     * @param Activite $idOffre
     * @param TagAutre $nomTag
     */
    public function __construct(Activite $idOffre, TagAutre $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
    }

    /**
     * @return Activite
     */
    public function getIdOffre() {
        return $this->idOffre;
    }

    /**
     * @param Activite $idOffre
     */
    public function setIdOffre(Activite $idOffre) {
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
