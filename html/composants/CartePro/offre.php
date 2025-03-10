<?php
use composants\Input\Input;
use composants\Button\Button;
use composants\Button\ButtonType;
use composants\Label\Label;
use composants\Select\Select;
use composants\CheckboxSelect\CheckboxSelect;

require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Label/Label.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Header/Header.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Footer/Footer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Select/Select.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/CheckboxSelect/CheckboxSelect.php";
class OffreHandler
{
    private $dbh;
    private $offres;
    private $offresMessage;

    public function __construct($dbh, $offres, $offresMessage)
    {
        $this->dbh = $dbh;
        $this->offres = $offres;
        $this->offresMessage = $offresMessage;
    }

    public function getOffreHtml($offre)
    {
        ob_start();
        $this->displayOffre($offre);
        return ob_get_clean();
    }

    public function displayOffres()
    {
        echo '<div class="liste-offres">';
        if (!empty($this->offresMessage)) {
            echo '<p>' . $this->offresMessage . '</p>';
        } else {
            foreach ($this->offres as $offre) {
                $this->displayOffre($offre);
            }
        }
        echo '</div>';
    }

    private function displayOffre($offre)
    {
        $idoffre = $offre['idoffre'];
        $typeOffre = $offre['nomcategorie'];
        $typeOffre = str_replace(' ', '_', strtolower(str_replace("'", '', $typeOffre)));
        if ($typeOffre === 'parc_dattractions') {
            $typeOffre = 'parc_attractions';
        }
        $raisonSociete = $this->getRaisonSociete($idoffre);
        $adresseTotale = $offre['ville'] . ', ' . $offre['codepostal'];
        $images = $offre['nomimage'];
        $fermeture = $offre['heurefermeture'];
        $currentTime = new DateTime();
        $closingTime = new DateTime($fermeture);
        $interval = $currentTime->diff($closingTime);

        echo '<div class="carte-offre" onclick="document.getElementById(\'form-' . $idoffre . '\').submit();">';
        echo '<form id="form-' . $idoffre . '" action="../detailsOffre/detailsOffre.php" method="POST">';
        echo '<input type="hidden" name="idOffre" value="' . $idoffre . '">';
        echo '</form>';
        echo '<div class="image-offre">';
        if (empty($images)) {
            echo '<div class="image-offre"><svg xmlns="http://www.w3.org/2000/svg" height="10em" viewBox="0 -960 960 960" width="10em" fill="#000000">
                <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z" />
                </svg></div>';
        } else {
            $path_img = "../../../ressources/" . $idoffre . "/images/" . $images;
            if (file_exists($path_img)) {
                echo '<img src="' . htmlspecialchars($path_img) . '" class="image-carte" alt="imgOffre">';
            } else {
                echo '<div class="image-offre"><svg xmlns="http://www.w3.org/2000/svg" height="10em" viewBox="0 -960 960 960" width="10em" fill="#000000">
                    <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z" />
                    </svg></div>';
            }
        }
        echo '</div>';
        echo '<div class="details-offre">';
        echo '<div class="donnees-offre">';
        echo '<div class="titre">';
        if ($typeOffre === 'parc_d_attractions') {
            Label::render(
                'details-offre',
                '',
                '',
                'Parc d\'attraction',
                "../../../ressources/icone/parc_d_attraction.svg"
            );
        } else {
            Label::render(
                'details-offre',
                '',
                '',
                ucfirst(htmlspecialchars($typeOffre)),
                "../../../ressources/icone/$typeOffre.svg"
            );
        }
        echo '</div>';
        Label::render('details-offre .titre', '', '', htmlspecialchars($offre['titre']));
        echo '<div class="infos-offre">';
        Label::render('', '', '', $raisonSociete['raisonsocialepro']);
        Label::render(
            '',
            '',
            '',
            $adresseTotale,
            "../../../ressources/icone/localisateur.svg"
        );
        if ($interval->h < 1 && $interval->invert == 0) {
            Label::render('', '', '', 'Bientôt fermé ');
        }
        echo '</div>';
        echo '</div>';
        echo '<div class="prix-offre">';
        Label::render('prix', '', '', htmlspecialchars($offre['nomforfait']));
        Label::render('', '', '', 'Option : ' . htmlspecialchars($offre['nomoption']));
        Label::render('', '', '', 'Note : ' . number_format((float) $offre['moynotes'], 1));
        if ($offre["nomcategorie"] == "Restaurant") {
            $string = $offre['nomgamme'];
            $start = strpos($string, '(') + 1;
            $end = strpos($string, ')');
            $gamme = substr($string, $start, $end - $start);
            Label::render("", "", "", "Gamme : " . $gamme, "../../../ressources/icone/gamme.svg");
        } else {
            Label::render('', '', '', 'Prix : ' . htmlspecialchars($offre['valprix'] . ' €'));
        }
        echo '</div>';
        echo '</div>';
        echo '<div class="button-container">';
        echo '<form action="../listeFacture/listeFacture.php" method="POST class="buttonCarte">';
        echo '<input type="hidden" name="idOffre" value="' . $idoffre . '">';
        Button::render("button-facture", "", "Facture", ButtonType::Pro, "", true);
        echo '</form>';
        echo '<form action="../modifierOffre/modifierOffre.php" method="POST" class="buttonCarte">';
        echo '<input type="hidden" name="idOffre" value="' . $idoffre . '">';
        Button::render("button-modif", "", "Modifier", ButtonType::Pro, "", true);
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }



    private function getRaisonSociete($idoffre)
    {
        return $this->dbh->query('SELECT cp.raisonsocialepro FROM pact._offre o JOIN pact._comptePro cp ON o.idCompte = cp.idCompte WHERE o.idoffre = ' . $idoffre)
            ->fetch();
    }




}


