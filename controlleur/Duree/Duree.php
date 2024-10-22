<?php

/**
 * Class Duree
 * 
 * Représente une durée en minutes.
 */
class Duree {
    private $tempsEnMinutes;

    /**
     * Constructeur de la classe Duree.
     *
     * @param int $tempsEnMinutes
     */
    public function __construct($tempsEnMinutes) {
        $this->tempsEnMinutes = $tempsEnMinutes;
    }

    /**
     * @return int
     */
    public function getTempsEnMinutes() {
        return $this->tempsEnMinutes;
    }

    /**
     * @param int $tempsEnMinutes
     */
    public function setTempsEnMinutes($tempsEnMinutes) {
        $this->tempsEnMinutes = $tempsEnMinutes;
    }
}
?>
