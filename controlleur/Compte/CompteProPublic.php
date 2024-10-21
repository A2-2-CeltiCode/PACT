<?php

/**
 * Class CompteProPublic
 * 
 * Représente un compte professionnel public avec un identifiant de compte et un compte associé.
 */
class CompteProPublic {
    private $idCompte;
    private $comptePro;

    /**
     * Constructeur de la classe CompteProPublic.
     *
     * @param int $idCompte
     * @param ComptePro $comptePro
     */
    public function __construct($idCompte, ComptePro $comptePro) {
        $this->idCompte = $idCompte;
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
