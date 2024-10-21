<?php

class Header
{
    private static $cssIncluded = false;
    private static $jsIncluded = false;

    public static function render()
    {
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="../../../composants/Header/Header.css">';
            self::$cssIncluded = true;
        }

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
        </header>';

        // Script should only be included once
        if (!self::$jsIncluded) {
            echo '<script src="../../../composants/Header/Header.js"></script>';
            self::$jsIncluded = true;
        }
    }
}
