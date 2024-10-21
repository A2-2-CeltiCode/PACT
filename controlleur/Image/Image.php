<?php

/**
 * Class Image
 * 
 * Représente une image associée à une offre.
 */
class Image {
    private $idOffre;
    private $idImage;
    private $nomImage;
    private $offre;

    /**
     * Constructeur de la classe Image.
     *
     * @param int $idImage
     * @param Offre $offre
     * @param string $nomImage
     */
    public function __construct($idImage, Offre $offre, $nomImage) {
        $this->idImage = $idImage;
        $this->offre = $offre;
        $this->nomImage = $nomImage;
    }

    /**
     * @return int
     */
    public function getIdImage() {
        return $this->idImage;
    }

    /**
     * @param int $idImage
     */
    public function setIdImage($idImage) {
        $this->idImage = $idImage;
    }

    /**
     * @return Offre
     */
    public function getOffre() {
        return $this->offre;
    }

    /**
     * @param Offre $offre
     */
    public function setOffre(Offre $offre) {
        $this->offre = $offre;
    }

    /**
     * @return string
     */
    public function getNomImage() {
        return $this->nomImage;
    }

    /**
     * @param string $nomImage
     */
    public function setNomImage($nomImage) {
        $this->nomImage = $nomImage;
    }
}
?>
