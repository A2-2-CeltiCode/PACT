<?php
/**
 * @class Footer
 * @brief Classe utilitaire pour générer le footer HTML du site.
 */
class Footer
{

    /**
     * @brief Génère le footer HTML.
     * 
     * @return void
     */
    public static function render()
    {
            echo '<link rel="stylesheet" href="../../../composants/Footer/Footer.css">';

        echo '
        <footer>
            <a href="creationComptePro.php" class="lien-bouton">
                <button>DEVENIR MEMBRE</button>
            </a>
            <div class="liens-importants">
                <a href="mentions.php">Mentions Légales</a>
                <a href="quiSommeNous.php">Qui sommes nous ?</a>
                <a href="condition.php">Conditions Générales</a>
            </div>
            <div class="icones-reseaux-sociaux">
                <a href="https://www.facebook.com/">
                    <img src="../../../ressources/icone/facebook.svg" alt="Icon facebook" style="vertical-align: middle;">
                </a>
                <a href="https://x.com/TripEnArvorPACT">
                    <img src="../../../ressources/icone/twitter.svg" alt="Icon X" style="vertical-align: middle;">
                </a>
                <a href="https://www.instagram.com/pactlannion/">
                    <img src="../../../ressources/icone/instagram.svg" alt="Icon instagram" style="vertical-align: middle;">
                </a>
            </div>
            <p>© 2024 PACT, Inc.</p>
            <a href="#entete" class="remonter-page">
                <i class="fas fa-arrow-up"></i>
            </a>
        </footer>';
    }
}
