<?php

use composants\Button\Button;
use composants\Button\ButtonType;
use composants\Input\Input;

/**
 * Class HeaderType
 * Définit les différents types d'utilisateurs pour le composant Header.
 */
class HeaderType
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
 * Class Header
 * Gère le rendu de l'en-tête de la page avec des options de personnalisation en fonction du type d'utilisateur.
 */
class Header
{
    /**
     * Indique si le CSS a été inclus.
     *
     * @var bool
     */
    private static bool $cssIncluded = false;

    /**
     * Indique si le JS a été inclus.
     *
     * @var bool
     */
    private static bool $jsIncluded = false;

    /**
     * Rend l'en-tête avec les éléments nécessaires (CSS, JavaScript, etc.) pour un utilisateur donné.
     *
     * @param string $type Le type d'utilisateur (Guest, Member, Pro). Par défaut, 'guest'.
     */
    public static function render(string $type = HeaderType::Guest): void {
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet"
                        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back_ios_new,arrow_forward_ios,close,menu" />';
            echo '<link rel="stylesheet" href="/composants/Header/Header.css">';
            self::$cssIncluded = true;
        }

        $spanClass = 'span-pact-' . $type;

        echo '
        <header class="' . $type . '">
            <a href="https://celticode.ventsdouest.dev">
                <img src="/ressources/icone/logo.svg" alt="Logo PACT">
                <span class="' . $spanClass . '">PACT</span>
            </a>';
        Input::render(class: "barre_recherche", placeholder: "Recherche activité, restaurant, lieu...",
            icon: "/ressources/icone/recherche.svg");
        echo '
            <div>';
        Input::render(placeholder: 'Entrez une localisation...', icon: "/ressources/icone/test.svg");
        echo '
            </div>';

        self::renderNav($type);
        self::renderLanguageSelector();
        self::renderAccountSection($type);
        self::renderBurger($type);

        if (!self::$jsIncluded) {
            echo '<script src="/composants/Header/Header.js"></script>';
            self::$jsIncluded = true;
        }

        echo '</header>';
    }

    /**
     * Rend la navigation principale en fonction du type d'utilisateur.
     *
     * @param string $type Le type d'utilisateur (Guest, Member, Pro).
     */
    private static function renderNav(string $type): void {
        echo '<nav>';
        if ($type == HeaderType::Guest) {
            echo '<a href="/pages/visiteur/accueil/accueil.php">Accueil</a>';
            echo '<a href="offre.php">Offres</a>';
        } elseif ($type == HeaderType::Member) {
            echo '<a href="/pages/visiteur/accueil/accueil.php">Accueil</a>';
            echo '<a href="offre.php">Offres</a>';
            echo '<a href="offre.php">Favoris</a>';
        } elseif ($type == HeaderType::Pro) {
            echo '<a href="/pages/pro/listeOffre/listeOffre.php">Mes Offres</a>';
            echo '<a href="/pages/pro/creerOffre/creerOffre.php">Créer une Offre</a>';
        }
        echo '</nav>';
    }

    /**
     * Rend le sélecteur de langue pour l'en-tête.
     */
    private static function renderLanguageSelector(): void {
        echo '
        <div class="entete-langue">
            <img class="logo-langue" src="/ressources/icone/logofr.svg" alt="Français">
            <label for="selecteur-langue"></label>
            <select class="selecteur-langue">
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
    private static function renderAccountSection(string $type): void {
        echo '<div>';
        if ($type == HeaderType::Guest) {
            Button::render($class = '', $id = 'guest-button', $text = 'S\'inscrire / Se Connecter',
                $type = ButtonType::Guest,
                $onClick = "window.location.href='/pages/pro/connexionComptePro/connexionComptePro.php'");
        } elseif ($type == HeaderType::Member) {
            Button::render($class = '', $id = 'member-button', $text = 'Mon Compte', $type = ButtonType::Member,
                $onClick = "window.location.href='monCompte.php'");
        } elseif ($type == HeaderType::Pro) {
            Button::render($class = '', $id = 'pro-button', $text = 'Se deconnecter', $type = ButtonType::Pro,
                $onClick = "window.location.href='../deconnexion.php'");
        }
        echo '</div>';
    }

    /**
     * render le menu burger
     *
     * @param string $type
     *
     * @return void
     */
    private static function renderBurger(string $type): void {
        echo '<div class="menu">';
        Input::render(placeholder: 'Entrez une localisation...', icon: "/ressources/icone/test.svg");
        if ($type == HeaderType::Guest) {
            echo '<a href="/pages/visiteur/accueil/accueil.php">Accueil</a>';
            echo '<a href="offre.php">Offres</a>';
        } elseif ($type == HeaderType::Member) {
            echo '<a href="/pages/visiteur/accueil/accueil.php">Accueil</a>';
            echo '<a href="offre.php">Offres</a>';
            echo '<a href="offre.php">Favoris</a>';
        } elseif ($type == HeaderType::Pro) {
            echo '<a href="dashboardPro.php">Mes Offres</a>';
            echo '<a href="publierOffre.php">Créer une Offre</a>';
        }
        self::renderLanguageSelector();
        self::renderAccountSection($type);
        echo <<<EOF
 </div>
 <button class="hamburger">
    <!-- material icons https://material.io/resources/icons/ -->
    <span class="menuIcon material-symbols-outlined">menu</span>
    <span class="closeIcon material-symbols-outlined">close</span>
  </button>
  <div id="overlay"></div>
EOF;

    }
}
