<?php

/**
 * @class Header
 * @brief Classe utilitaire pour générer l'entête (header) HTML du site.
 */
class Header
{

    /**
     * @brief Génère l'entête HTML.
     * 
     * @return void
     */
    public static function render()
    {
        echo '<link rel="stylesheet" href="../../../composants/Header/Header.css">';

        echo '
        <header>
    <div>
        <img src="../../../ressources/icone/logo.svg" alt="Logo PACT">
        <span>PACT</span>
    </div>
    <div>';
        Input::render(placeholder: 'Entrez une localisation...');
        echo '
    </div>
    <nav>
        <!-- Liens de navigation pour la page d\'accueil et les offres -->
        <a href="index.php">Accueil</a>
        <a href="offre.php">Offres</a>
    </nav>
    <div class="entete-langue">
        <img id="logo-langue" src="../../../ressources/icone/logofr.svg" alt="Français">
        <label for="selecteur-langue"></label>
        <select id="selecteur-langue">
            <option value="fr">Français</option>
            <option value="en">English</option>
        </select>
    </div>
    <div>
        <a href="connexionComptePro.php" class="lien-bouton">
            <button>S\'inscrire / Se Connecter</button>
        </a>
    </div>
    <script src="../../../composants/Header/Header.js"></script>
</header>
        <script src="../../../composants/Header/Header.js"></script>';
    }
}
