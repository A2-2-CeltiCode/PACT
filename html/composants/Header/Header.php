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
        /*Input::render(class: "barre_recherche", placeholder: "Recherche activité, restaurant, lieu...",
            icon: "/ressources/icone/recherche.svg");
        echo '
            <div>';
        Input::render(placeholder: 'Entrez une localisation...', icon: "/ressources/icone/test.svg");
        echo '
            </div>';*/
            
        self::renderNav($type);
        //self::renderLanguageSelector();
        self::renderAccountSection($type);
        self::renderBurger($type);

        echo '<div class="popup" id="popup-repondre">
                <div class="popup-content">
                    <span class="close">&times;</span>
                    <form action="/pages/pro/detailsOffre/envoyerReponse.php" method="POST">
                        <input type="hidden" name="idAvis" id="popup-idAvis">
                        <textarea name="reponse" placeholder="Votre réponse..." required></textarea>
                        <button type="submit">Envoyer</button>
                    </form>
                </div>
              </div>';

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
            echo '<a href="/pages/visiteur/listeOffres/listeOffres.php">Rechercher</a>';
        } elseif ($type == HeaderType::Member) {
            echo '<a href="/pages/membre/accueil/accueil.php">Accueil</a>';
            echo '<a href="/pages/membre/listeOffres/listeOffres.php">Rechercher</a>';
            //echo '<a href="offre.php">Favoris</a>';
        } elseif ($type == HeaderType::Pro) {
            echo '<a href="/pages/pro/listeOffres/listeOffres.php">Mes Offres</a>';
            echo '<a href="/pages/pro/creerOffre/creerOffre.php">Créer une Offre</a>';
        }
        echo '</nav>';
    }

    /**
     * Rend le sélecteur de langue pour l'en-tête.
     */
    /*private static function renderLanguageSelector(): void {
        echo '
        <div class="entete-langue">
            <img class="logo-langue" src="/ressources/icone/logofr.svg" alt="Français">
            <label for="selecteur-langue"></label>
            <select class="selecteur-langue">
                <option value="fr">Français</option>
                <option value="en">English</option>
            </select>
        </div>';
    }*/

    /**
     * Rend le sélecteur de langue pour l'en-tête.
     */
    private static function renderProfileOptionSelector(string $profileClass): void {
        
        if ($profileClass == "profil-member") {
            $chemin ="/pages/membre/consulterCompteMembre/consulterCompteMembre.php";
        } else if ($profileClass == "profil-pro"){
            $chemin ="/pages/pro/consulterComptePro/consulterComptePro.php";
        }
        $profile = '
        <div class="entete-profil">
            <label for="selecteur-profil"></label>
            <select class="selecteur-profil ' . $profileClass . '" id="selecteur-profil" onchange="window.location.href =this.value;" onclick="toggleArrow()">
                <option value="default" hidden id="profile-option">Mon compte ▼</option>
                <option value="' . $chemin . '">Accéder à mon Espace</option>';
        if($profileClass == "profil-member"){
            $profile = $profile . '<option value="' . "/pages/membre/listeAvis/listeAvis.php" . '">Voir mes Avis</option>';
        } 
        $profile = $profile . '<option value="/deconnexion.php">Déconnexion</option>
            </select>
        </div>';
        echo $profile;

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
                $onClick = "window.location.href='/pages/membre/connexionCompteMembre/connexionCompteMembre.php'");
        } elseif ($type == HeaderType::Member) {
            self::renderProfileOptionSelector('profil-member');
            self::renderNotificationIcon();
        } elseif ($type == HeaderType::Pro) {
            self::renderProfileOptionSelector('profil-pro');
            self::renderNotificationIcon();
        }
        echo '</div>';
    }

    private static function renderNotificationIcon(): void {
        $server = 'localhost';
        $driver = 'pgsql';
        $dbname = 'pact';
        $dbuser = 'postgres';
        $dbpass = 'derfDERF29';
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $idCompte = $_SESSION['idCompte'];
        $query = "SELECT a.*, o.* FROM pact._avis a
              JOIN pact._offre o ON a.idOffre = o.idOffre
              WHERE o.idCompte = :idCompte AND a.estvu = false";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();
        $unreadReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $unreadCount = count($unreadReviews);
        echo '<div class="notification-icon">';
        echo '<img src="/ressources/icone/notification.svg" alt="Notifications" onclick="toggleDropdown()">';
        if ($unreadCount > 0) {
            echo '<span class="notification-count">' . $unreadCount . '</span>';
        }
        echo '<div class="dropdown-content" id="notification-dropdown">';
        foreach ($unreadReviews as $review) {
            echo '<div class="review" data-id="' . $review['idavis'] . '">';
            echo '<strong>' . $review['titre'] . '</strong>';
            echo '<p>' . $review['commentaire'] . '</p>';
            echo '<button class="btn-repondre" onclick="openReplyPopup(' . $review['idavis'] . ')">Répondre</button>';
            echo '<button class="btn-mark-seen" data-id="' . $review['idavis'] . '">Marqué comme vu</button>';
            echo '</div>';
        }
        echo '</div>';
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
        //self::renderLanguageSelector();
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
?>
