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
        <header id="entete" class="entete">
            <div class="entete-logo">
                <!-- Logo de l\'entreprise avec texte -->
                <img src="../../../ressources/icone/logo.svg" alt="Logo PACT">
                <span class="texte-logo">PACT</span>
            </div>
            <div class="entete-recherche">
                <!-- Composant d\'entrée pour la recherche -->
        ';
        Input::render(
            class: 'champ-recherche', 
            type: 'text', 
            placeholder: 'Entrez une localisation...'
        );

        echo '
            </div>
            <nav class="entete-navigation">
                <!-- Liens de navigation pour la page d\'accueil et les offres -->
                <a href="index.php">Accueil</a>
                <a href="offre.php">Offres</a>
            </nav>
            <div class="entete-langue">
                <img id="logo-langue" src="../../../ressources/icone/logofr.svg" alt="Français">
                <select id="selecteur-langue">
                    <option value="fr">Français</option>
                    <option value="en">English</option>
                </select>
            </div>
            <div class="entete-connexion">
                <a href="connexionComptePro.php" class="lien-bouton">
                    <button>S\'inscrire / Se Connecter</button>
                </a>
            </div>
        </header>
        <script src="../../../composants/Header/Header.js"></script>';
    }
}
