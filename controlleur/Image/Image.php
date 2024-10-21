<?php

class Image {
    private $idOffre;
    private $idImage;
    private $nomImage;
    private $offre; // Instance de Offre

    public function __construct($idImage, Offre $offre, $nomImage) {
        $this->idImage = $idImage;
        $this->offre = $offre;
        $this->nomImage = $nomImage;
    }
}
?>
