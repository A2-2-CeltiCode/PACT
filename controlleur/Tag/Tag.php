<?php

/**
 * Class Tag
 * 
 * Représente un tag avec un nom.
 */
class Tag {
    private $nomTag;

    /**
     * Constructeur de la classe Tag.
     *
     * @param string $nomTag
     */
    public function __construct($nomTag) {
        $this->nomTag = $nomTag;
    }

    /**
     * @return string
     */
    public function getNomTag() {
        return $this->nomTag;
    }

    /**
     * @param string $nomTag
     */
    public function setNomTag($nomTag) {
        $this->nomTag = $nomTag;
    }
}
?>
