<?php

class ComptePro {
    private $idCompte;
    private $denominationSociale;
    private $raisonSocialePro;
    private $banqueRib;
    private $compte; // Instance de Compte

    public function __construct($idCompte, $denominationSociale, $raisonSocialePro, $banqueRib, Compte $compte) {
        $this->idCompte = $idCompte;
        $this->denominationSociale = $denominationSociale;
        $this->raisonSocialePro = $raisonSocialePro;
        $this->banqueRib = $banqueRib;
        $this->compte = $compte;
    }
}
?>
