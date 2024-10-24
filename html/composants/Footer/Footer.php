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
    /**
     * Indique si le CSS a été inclus.
     *
     * @var bool
     */
    private static bool $cssIncluded = false;

    /**
     * Rend le pied de page avec les éléments nécessaires (CSS, boutons, etc.) pour un utilisateur donné.
     *
     * @param string $type Le type d'utilisateur (Guest, Member, Pro). Par défaut, 'guest'.
     */
    public static function render(string $type = FooterType::Guest): void {
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="/composants/Footer/Footer.css">';
            self::$cssIncluded = true;
        }

        echo '<footer class="' . $type . '">';

        $buttonType = ButtonType::Guest;
        $buttonText = 'DEVENIR MEMBRE';

        if ($type === FooterType::Member) {
            $buttonType = ButtonType::Member;
        } elseif ($type === FooterType::Pro) {
            $buttonType = ButtonType::Pro;
            $buttonText = "ACCÉDER A L'ESPACE PROFESSIONNEL";
        }

        Button::render($class = 'lien-bouton', $id = 'footer-button', $text = $buttonText, $type = $buttonType,
            $onClick = "window.location.href='/pages/pro/creationComptePro/creationComptePro.php'");

        echo <<<EOF
            <div class="liens-importants">
                <a href="mentions.php">Mentions Légales</a>
                <a href="quiSommeNous.php">Qui sommes nous ?</a>
                <a href="condition.php">Conditions Générales</a>
            </div>
EOF;

        echo <<<EOF
            <div class="icones-reseaux-sociaux">
                <a href="#">
                    <img src="/ressources/icone/facebook.svg" alt="Icon facebook">
                </a>
                <a href="https://x.com/TripEnArvorPACT">
                    <img src="/ressources/icone/twitter.svg" alt="Icon X">
                </a>
                <a href="https://www.instagram.com/pactlannion/">
                    <img src="/ressources/icone/instagram.svg" alt="Icon instagram">
                </a>
            </div>
EOF;

        echo '<p>© 2024 PACT, Inc.</p>';

        self::renderScrollUpIcon($type);

        echo '</footer>';
    }

    /**
     * Rend l'icône de retour en haut de la page, personnalisée en fonction du type d'utilisateur.
     *
     * @param string $type Le type d'utilisateur (Guest, Member, Pro).
     */
    private static function renderScrollUpIcon($type): void {
        $scrollUpClass = 'scroll-up-' . $type;

        echo '
        <a href="#" class="remonte-page ' . $scrollUpClass . '">
            <img src="/ressources/icone/arrow_upward.svg" alt="Remonter en haut">
        </a>';
    }
}
