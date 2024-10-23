<?php

/**
 * Class HeaderType
 * Définit les différents types d'utilisateurs pour le composant Header.
 */
class HeaderType
{
    /**
     * Utilisateur invité.
     * @var string
     */
    const Guest = 'guest';

    /**
     * Utilisateur membre.
     * @var string
     */
    const Member = 'member';

    /**
     * Utilisateur professionnel.
     * @var string
     */
    const Pro = 'pro';
}

/**
 * Class Header
 * Gère le rendu de l'en-tête de la page avec des options de personnalisation en fonction du type d'utilisateur.
 */
class Header
{
    /**
     * Indique si le CSS a été inclus.
     * @var bool
     */
    private static $cssIncluded = false;

    /**
     * Indique si le JS a été inclus.
     * @var bool
     */
    private static $jsIncluded = false;

    /**
     * Rend l'en-tête avec les éléments nécessaires (CSS, JavaScript, etc.) pour un utilisateur donné.
     * 
     * @param string $type Le type d'utilisateur (Guest, Member, Pro). Par défaut, 'guest'.
     */
    public static function render($type = HeaderType::Guest)
    {
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="../../../composants/Header/Header.css">';
            self::$cssIncluded = true;
        }

        $spanClass = 'span-pact-' . $type;

        echo '
        <header class="' . $type . '">
            <div>
                <img src="../../../ressources/icone/logo.svg" alt="Logo PACT">
                <span class="' . $spanClass . '">PACT</span>
            </div>
            <div>';
        Input::render(placeholder: 'Entrez une localisation...', icon: "../../../ressources/icone/test.svg");
        echo '
            </div>';

        self::renderNav($type);
        self::renderLanguageSelector();
        self::renderAccountSection($type);

        echo '</header>';

        if (!self::$jsIncluded) {
            echo '<script src="../../../composants/Header/Header.js"></script>';
            self::$jsIncluded = true;
        }
    }

    /**
     * Rend la navigation principale en fonction du type d'utilisateur.
     * 
     * @param string $type Le type d'utilisateur (Guest, Member, Pro).
     */
    private static function renderNav($type)
    {
        echo '<nav>';
        if ($type == HeaderType::Guest) {
            echo '<a href="index.php">Accueil</a>';
            echo '<a href="offre.php">Offres</a>';
        } elseif ($type == HeaderType::Member) {
            echo '<a href="index.php">Accueil</a>';
            echo '<a href="offre.php">Offres</a>';
            echo '<a href="offre.php">Favoris</a>';
        } elseif ($type == HeaderType::Pro) {
            echo '<a href="dashboardPro.php">Mes Offres</a>';
            echo '<a href="publierOffre.php">Créer une Offre</a>';
        }
        echo '</nav>';
    }

    /**
     * Rend le sélecteur de langue pour l'en-tête.
     */
    private static function renderLanguageSelector()
    {
        echo '
        <div class="entete-langue">
            <img id="logo-langue" src="../../../ressources/icone/logofr.svg" alt="Français">
            <label for="selecteur-langue"></label>
            <select id="selecteur-langue">
                <option value="fr">Français</option>
                <option value="en">English</option>
            </select>
        </div>';
    }

    /**
     * Rend la section du compte en fonction du type d'utilisateur.
     * 
     * @param string $type Le type d'utilisateur (Guest, Member, Pro).
     */
    private static function renderAccountSection($type)
    {
        echo '<div>';
        if ($type == HeaderType::Guest) {
            Button::render(
                $class = '',
                $id = 'guest-button',
                $text = 'S\'inscrire / Se Connecter',
                $type = ButtonType::Guest,
                $onClick = "window.location.href='connexionComptePro.php'"
            );
        } elseif ($type == HeaderType::Member) {
            Button::render(
                $class = '',
                $id = 'member-button',
                $text = 'Mon Compte',
                $type = ButtonType::Member,
                $onClick = "window.location.href='monCompte.php'"
            );
        } elseif ($type == HeaderType::Pro) {
            Button::render(
                $class = '',
                $id = 'pro-button',
                $text = 'Mon Espace Pro',
                $type = ButtonType::Pro,
                $onClick = "window.location.href='espacePro.php'"
            );
        }
        echo '</div>';
    }
}
