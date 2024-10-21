<!doctype html>
<html lang="fr-FR">
<head>
    <title>PACT</title>
    <link rel="stylesheet" href="style.css">
    <?php
    require_once 'composants/Input/Input.php';
    require_once 'Offre.php';
    ?>
</head>

<?php
$ofr = new Offre($nom = 'Spectacle de magie', $typeO = 'spectacle', $ville = 'Plouezec', $imageO = '7', $note = 4.2,
    $nomProprietaire = "Evan Beats", $duree = '120');

$offreProches = [];

for ($i = 0; $i < 16; $i++) {
    $offreProches[] = $ofr;
}
?>

<body>
<header>
    <div>
        <img src="./assets/icon/logo.svg" alt="Logo PACT">
        <span>PACT</span>
    </div>
    <div>
        <?php Input::render(placeholder: 'Entrez une localisation...') ?>
    </div>
    <nav>
        <!-- Liens de navigation pour la page d'accueil et les offres -->
        <a href="index.php">Accueil</a>
        <a href="offre.php">Offres</a>
    </nav>
    <div class="entete-langue">
        <img id="logo-langue" src="assets/icon/logofr.svg" alt="Français">
        <label for="selecteur-langue"></label>
        <select id="selecteur-langue">
            <option value="fr">Français</option>
            <option value="en">English</option>
        </select>
    </div>
    <div>
        <a href="connexionComptePro.php" class="lien-bouton">
            <button>S'inscrire / Se Connecter</button>
        </a>
    </div>
    <script src="script-header.js"></script>
</header>
<div>
    <?php Input::render(class: "barre_recherche", placeholder: "  Recherche activitées, restaurants, lieux ...",
        icon: "assets/icon/recherche.svg") ?>
</div>
<main>
    <div>
        <h2>Autour de vous</h2>
        <div>
            <?php
            foreach ($offreProches as $item) {
                echo $item;
            }
            ?>
        </div>
    </div>
    <div>
        <h2>Les plus recommandées</h2>
        <div>
            <?php
            foreach ($offreProches as $item) {
                echo $item;
            }
            ?>
        </div>
    </div>
    <div>
        <h2>Les mieux noté!</h2>
        <div>
            <?php
            foreach ($offreProches as $item) {
                echo $item;
            }
            ?>
        </div>
    </div>
</main>
<footer>
    <a href="creationComptePro.php" class="lien-bouton">
        <button>DEVENIR MEMBRE</button>
    </a>
    <div class="liens">
        <a href="mentions.php">Mentions Légales</a>
        <a href="quiSommeNous.php">Qui sommes nous ?</a>
        <a href="condition.php">Conditions Générales</a>
    </div>
    <div class="icones-reseaux">
        <div><a href="#"><?= file_get_contents('assets/icon/facebook.svg') ?></a></div>
        <div><a href="https://www.instagram.com/pactlannion/"><?= file_get_contents('assets/icon/twitter.svg') ?></a>
        </div>
        <div><a href="https://x.com/TripEnArvorPACT"><?= file_get_contents('assets/icon/instagram.svg') ?></a></div>
    </div>
    <p>© 2024 PACT, Inc.</p>
    <a href="#" class="remonte-page"><?= file_get_contents('assets/icon/arrow_upward.svg') ?></a>
</footer>
</body>
</html>

