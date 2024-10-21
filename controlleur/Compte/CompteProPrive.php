<?php

/**
 * Class CompteProPrive
 * 
 * Représente un compte professionnel privé avec des informations d'identification et un compte associé.
 */
class CompteProPrive {
    private $idCompte;
    private $numSiren;
    private $comptePro;

    /**
     * Constructeur de la classe CompteProPrive.
     *
     * @param int $idCompte
     * @param string $numSiren
     * @param ComptePro $comptePro
     */
    public function __construct($idCompte, $numSiren, ComptePro $comptePro) {
        $this->idCompte = $idCompte;
        $this->numSiren = $numSiren;
        $this->comptePro = $comptePro;
    }

    /**
     * @return int
     */
    public function getIdCompte() {
        return $this->idCompte;
    }

    /**
     * @param int $idCompte
     */
    public function setIdCompte($idCompte) {
        $this->idCompte = $idCompte;
    }

    /**
     * @return string
     */
    public function getNumSiren() {
        return $this->numSiren;
    }

    /**
     * @param string $numSiren
     */
    public function setNumSiren($numSiren) {
        $this->numSiren = $numSiren;
    }

    /**
     * @return ComptePro
     */
    public function getComptePro() {
        return $this->comptePro;
    }

    /**
     * @param ComptePro $comptePro
     */
    public function setComptePro(ComptePro $comptePro) {
        $this->comptePro = $comptePro;
    }
}
?>
