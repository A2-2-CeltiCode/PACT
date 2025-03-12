<?php
use \composants\Select\Select;
use \composants\CheckboxSelect\CheckboxSelect;
use \composants\Checkbox\Checkbox;
use controlleurs\Offre\Offre;
use composants\Input\Input;
use composants\Button\Button;
use composants\InputRangeDouble\InputRangeDouble;
use composants\Button\ButtonType;

use composants\InputRange\InputRange;
require_once $_SERVER['DOCUMENT_ROOT'] . '/controlleurs/Offre/Offre.php';
require_once $_SERVER["DOCUMENT_ROOT"] .  "/composants/Select/Select.php";
require_once  $_SERVER["DOCUMENT_ROOT"] . '/composants/CheckboxSelect/CheckboxSelect.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/composants/Checkbox/Checkbox.php';
require_once '../../../trie/fonctionTrie.php';
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Input/Input.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/composants/Button/Button.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/trie/fonctionTrie.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/composants/InputRangeDouble/InputRangeDouble.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/composants/InputRange/InputRange.php';

class Trie {
    public static function render($sort, $titre, $localisation, $minPrix, $maxPrix, $ouverture, $fermeture, $nomcategories,$status=null) {
        
        echo '<link rel="stylesheet" type="text/css" href="/trie/style.css">';

        $optionsCategorie = [
            'Spectacle' => 'Spectacle',
            'Activite' => 'Activite',
            'Restaurant' => 'Restaurant',
            'Parc d\'attractions' => 'Parc d\'attractions',
            'Visite' => 'Visite'
        ];
        $optionsEtat = [
            'ouvertetferme'=> 'Ouvert et fermé',
            'ouvert'=> 'Ouvert',
            'ferme'=> 'Fermé'
        ];
        $optionsTrie = [
            "valprix ASC" => "Tri par prix croissant",
            "valprix DESC" => "Tri par prix décroissant",
            "idoffre ASC" => "Tri par date croissante",
            "idoffre DESC" => "Tri par date décroissante",
            "moynotes DESC" => "Tri par note décroissante",
            "moynotes ASC" => "Tri par note croissante"
        ];

        $optionGamme = [
            "1" => "€ (-25€)",
            "2"=> "€€ (25-40€)",
            "3"=> "€€€ (+40€)"
        ];
        
        
        echo '<form id="searchForm" method="GET" action="">';
        
        echo '<div class="triviennois">';
        echo '<div class="tri-somie">';
        echo '<img class="btnfiltres" id="toggleBarretrieButton" src="/ressources/icone/filteron.svg" alt="filtre"  />';
        Input::render(name:"titre",class:'styletitre', type:"text", placeholder:'Titre*', value: htmlspecialchars($titre));

        Input::render(name:"localisation",class:'styletitre', type:"text", placeholder:'localisation', value: htmlspecialchars($localisation));
        Select::render('custom-class', 'select-trie', 'trie', false, $optionsTrie, isset($_GET['etat']) ? $_GET['etat'] : 'tout');
        echo '<div id="nombreFiltresActifs">Nombre de filtres actifs : 0</div>';
        echo '</div>';
        
        echo '</div>';

        echo '<input type="hidden" id="sortInput" name="sort" value="' . htmlspecialchars($sort) . '">';
        echo '<div class="input">';
        echo '<div style="display: grid; gap: 1px;">';
        echo '</div>';
        
        
     
        

        echo '<div class="agencement">';
        echo '<div class="categorie" id="styleShadow">';
        foreach ($optionsCategorie as $value => $label) {
            Checkbox::render(
            $class = 'custom-class',
            $id = 'checkbox-' . htmlspecialchars($value),
            $name = 'nomcategorie[]',
            $value = htmlspecialchars($value),
            $text = $label,
            $required = false,
            $checked = in_array($value, $nomcategories)
            );
        }
        echo '</div>';
        echo '<br>';
        echo'<div id="styleShadow">';

        foreach ($optionGamme as $value => $label) {
            Checkbox::render(
            $class = 'custom-class',
            $id = 'checkbox-' . htmlspecialchars($value),
            $name = 'option[]',
            $value = htmlspecialchars($value),
            $text = $label,
            $required = false,
            $checked = in_array($value, $optionGamme)
            );
        }
        echo'</div>';
        echo '<div class="prix" id="styleShadow">';
        
        

        echo '<label for="rangeSlider">Prix</label>';
        InputRangeDouble::render(
            $class = "custom-slider",
            $id = "rangeSlider",
            $name = "range",
            $required = true,
            $min = 0,
            $max = 100,
            $from = 0,
            $to = 100
        );
        echo '</div>';
        echo '<div class="note" id="styleShadow">';
        echo '<label for="rangeSlider">Note</label>';
        InputRange::render(
            $class = "monoslider",
            $id = "rangeSlider",
            $name = "note",
            $required = true,
            $min = 0,
            $max = 5,
            $value = 0
        );
        echo '</div>';
        
        echo '<div class="heure" id="styleShadow">';
        echo '<div class="aligne"><label for="ouverture">Heure d\'ouverture</label>';
        Input::render(name:"ouverture", type:"time", value: htmlspecialchars($ouverture));
        echo '</div><div class="aligne"><label for="fermeture">Heure de fermeture</label>';
        Input::render(name:"fermeture", type:"time", placeholder:'Heure de fermeture', value: htmlspecialchars($fermeture));
        echo '</div>';
        echo'<br>';
        Select::render('custom-class', 'select-etat', 'etat', false, $optionsEtat, isset($_GET['etat']) ? $_GET['etat'] : 'tout');
        echo '</div>';
        echo  '</div>';


        if($status !== null){
            echo '<input type="hidden" id="status" name="status" value="' . htmlspecialchars($status) . '">';
        }
        echo '</div></form>';
        
        
    }
}
