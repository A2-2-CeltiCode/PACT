<?php

use composants\Button\Button;
use composants\Button\ButtonType;

require_once $_SERVER['DOCUMENT_ROOT'] . '/composants/Button/Button.php';

/**
 * Class FooterType
 * Définit les différents types d'utilisateurs pour le composant Footer.
 */
class FooterType
{
    /**
     * Utilisateur invité.
     *
     * @var string
     */
    const Guest = 'guest';

    /**
     * Utilisateur membre.
     *
     * @var string
     */
    const Member = 'member';

    /**
     * Utilisateur professionnel.
     *
     * @var string
     */
    const Pro = 'pro';
}

/**
 * Class Footer
 * Gère le rendu du pied de page de la page, avec des options de personnalisation en fonction du type d'utilisateur.
 */
class Footer
{
    private static bool $cssIncluded = false;

    public static function render(string $type = FooterType::Guest): void {
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="/composants/Footer/Footer.css">';
            self::$cssIncluded = true;
        }

        // Script pour gérer la position du footer
        echo <<<EOF
        <script>
            window.onload = function() {
                const footer = document.getElementById('footer');
                if (document.body.scrollHeight > window.innerHeight) {
                    footer.classList.add('footer-relative');
                } else {
                    footer.classList.add('footer-fixed');
                }
            };
        </script>
EOF;

        echo '<footer id="footer" class="' . $type . '">';

        $buttonType = ButtonType::Guest;
        $buttonText = 'DEVENIR MEMBRE';

        if ($type === FooterType::Member) {
            $buttonType = ButtonType::Member;
        } elseif ($type === FooterType::Pro) {
            $buttonType = ButtonType::Pro;
            $buttonText = "ACCÉDER A L'ESPACE PROFESSIONNEL";
        }

        Button::render('lien-bouton', 'footer-button', $buttonText, $buttonType,
            "window.location.href='/pages/pro/creationComptePro/creationComptePro.php'");

        // Liens importants
        echo <<<EOF
            <div class="liens-importants">
                <a href="mentions.php">Mentions Légales</a>
                <a href="quiSommeNous.php">Qui sommes nous ?</a>
                <a href="condition.php">Conditions Générales</a>
            </div>
EOF;

        // Icônes réseaux sociaux
        echo <<<EOF
            <div class="icones-reseaux-sociaux">
                <a href="#"><img src="/ressources/icone/facebook.svg" alt="Icon facebook"></a>
                <a href="https://x.com/TripEnArvorPACT"><img src="/ressources/icone/twitter.svg" alt="Icon X"></a>
                <a href="https://www.instagram.com/pactlannion/"><img src="/ressources/icone/instagram.svg" alt="Icon instagram"></a>
            </div>
EOF;

        echo '<p>© 2024 PACT, Inc.</p>';

        self::renderScrollUpIcon($type);

        echo '</footer>';
    }

    private static function renderScrollUpIcon($type): void {
        $scrollUpClass = 'scroll-up-' . $type;

        echo '
        <a href="#" class="remonte-page ' . $scrollUpClass . '">
            <img src="/ressources/icone/arrow_upward.svg" alt="Remonter en haut">
        </a>';
    }
}
