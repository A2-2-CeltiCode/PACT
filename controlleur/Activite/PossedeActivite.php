<?php

class PossedeActivite {
    private $idOffre; // Instance de Activite
    private $nomTag; // Instance de TagAutre

    public function __construct(Activite $idOffre, TagAutre $nomTag) {
        $this->idOffre = $idOffre;
        $this->nomTag = $nomTag;
    }
}
?>
