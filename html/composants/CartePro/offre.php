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
        $status = $offre['status']; // Assurez-vous que le statut de l'offre est récupéré correctement

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
                echo '<img src="' . htmlspecialchars($path_img) . '" class="image-carte">';
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
        echo '<form action="../listeFacture/listeFacture.php" method="GET" class="buttonCarte" onsubmit="event.stopPropagation();">';
        echo '<input type="hidden" name="idOffre" value="' . $idoffre . '">';
        echo '<button type="submit" class="button-facture" style="background: none; border: none;"><img src="../../../ressources/icone/facture.svg" alt="facture" class="icon-button"></button>';
        echo '</form>';
        echo '<form action="../modifierOffre/modifierOffre.php" method="POST" class="buttonCarte" onsubmit="event.stopPropagation();">';
        echo '<input type="hidden" name="idOffre" value="' . $idoffre . '">';
        echo '<button type="submit" class="button-modif" style="background: none; border: none;"><img src="../../../ressources/icone/edit.svg" alt="modifier" class="icon-button"></button>';
        echo '</form>';
        if ($status === 'horsligne'): ?>
            <form action="../supprimerOffre/supprimerOffre.php" method="POST" class="buttonCarte" onsubmit="event.stopPropagation();">
                <input type="hidden" name="idOffre" value="<?php echo $idoffre; ?>">
                <button type="button" class="button-suppr" style="background: none; border: none;" onclick="showDeletePopup(event, <?php echo $idoffre; ?>, '<?php echo htmlspecialchars($offre['titre']); ?>', '<?php echo htmlspecialchars($offre['valprix'] . ' €'); ?>', '<?php echo htmlspecialchars($offre['nomoption']); ?>')"><img src="../../../ressources/icone/delete.svg" alt="supprimer" class="icon-button"></button>
            </form>
        <?php endif;
        echo '</div>';
        echo '</div>';

        // CSS
        echo '<style>
        .icon-button:hover {
            background-color: #d0d0d0;
            border-radius: 4px;
        }
        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .popup-content {
            border-radius: 10px;
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 1000px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        #delete-buttons {
            margin-top: 20px;
        }
        #delete-confirm {
            background-color: #4CAF50; 
            color: white;
            border: none;
            padding: 10px 20px;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        #delete-confirm:hover {
            background-color: #45a049;
        }
        #delete-decline {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }
        #delete-decline:hover {
            background-color: #e53935;
        }
        </style>';

        // HTML du popup
        echo '<div class="popup" id="popup-delete">
            <div class="popup-content">
            <span class="close" onclick="closeDeletePopup()">&times;</span>
            <form action="../../pro/listeOffres/supprimerOffre.php" method="POST">
            <input type="hidden" name="idOffre" id="popup-idOffre">
            <h3 style="text-align: left;">Êtes-vous sûr de vouloir supprimer cette offre ?</h4>
            <br><p style="text-align: left; font-size: 1.1em">Avant de supprimer l\'offre, vérifiez bien que vous avez téléchargé toutes les factures associées</p>
            <p style="color: red; text-align: left; font-size: 1.1em">Vous ne pourrez pas revenir en arrière. Cette action est irréversible</p>
            <br><hr><br>
            <p style="font-size: 1.2em;">Nom de l\'offre :</p>
            <p id="popup-offre-titre" style="font-size: 1.2em;"></p>
            <div id="delete-buttons"><br>
            <button id="delete-confirm" type="submit" title="Confirmation de la suppression">Oui</button>
            <button id="delete-decline" type="button" onclick="closeDeletePopup()" title="Retour en arrière">Non</button>
            </div>
            </form>
            </div>
        </div>';

        // JavaScript du popup
        echo '<script>
        function showDeletePopup(event, idOffre, offreTitre) {
            document.getElementById("popup-offre-titre").innerText = offreTitre; 
            event.stopPropagation();
            document.getElementById("popup-idOffre").value = idOffre;
            document.getElementById("popup-delete").style.display = "block";
        }

        function closeDeletePopup() {
            document.getElementById("popup-delete").style.display = "none";
        }
        </script>';
    }

    private function getRaisonSociete($idoffre)
    {
        return $this->dbh->query('SELECT cp.raisonsocialepro FROM pact._offre o JOIN pact._comptePro cp ON o.idCompte = cp.idCompte WHERE o.idoffre = ' . $idoffre)
            ->fetch();
    }




}


