<?php

/**
 * Class TagAutre
 * 
 * Représente un tag spécifique, qui est une instance de la classe Tag.
 */
class TagAutre {
    private $nomTag;

    /**
     * Constructeur de la classe TagAutre.
     *
     * @param Tag $nomTag
     */
    public function __construct(Tag $nomTag) {
        $this->nomTag = $nomTag;
    }

    /**
     * @return Tag
     */
    public function getNomTag() {
        return $this->nomTag;
    }

    /**
     * @param Tag $nomTag
     */
    public function setNomTag(Tag $nomTag) {
        $this->nomTag = $nomTag;
    }
}
?>
