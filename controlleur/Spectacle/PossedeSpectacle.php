<?php

class PossedeSpectacle {
    private $idOffre; // Instance de Spectacle
    private $nomTag; // Instance de TagAutre

    public function __construct(Spectacle $idOffre, TagAutre $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
    }
}
?>
