<?php

/**
 * Class TagRestaurant
 * 
 * Représente un tag spécifique pour un restaurant, qui est une instance de la classe Tag.
 */
class TagRestaurant {
    private $nomTag;

    /**
     * Constructeur de la classe TagRestaurant.
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
