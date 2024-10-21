<?php

class PossedeVisite {
    private $idOffre; // Instance de Visite
    private $nomTag; // Instance de TagAutre

    public function __construct(Visite $idOffre, TagAutre $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
    }
}
?>
