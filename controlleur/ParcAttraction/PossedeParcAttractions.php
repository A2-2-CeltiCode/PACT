<?php

class PossedeParcAttraction {
    private $idOffre; // Instance de ParcAttraction
    private $nomTag; // Instance de TagAutre

    public function __construct(ParcAttraction $idOffre, TagAutre $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
    }
}
?>
