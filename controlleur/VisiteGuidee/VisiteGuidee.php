<?php

class VisiteGuidee {
    private $idOffre; // Instance de Visite
    private $nomLangage; // Instance de Langage
    private $visite;

    public function __construct(Visite $idOffre, Langage $nomLangage) {
        $this->idOffre = $idOffre;
        $this->nomLangage = $nomLangage;
    }
}
?>
