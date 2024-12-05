<?php
use \composants\Select\Select;
use \composants\CheckboxSelect\CheckboxSelect;
use \composants\Checkbox\Checkbox;
use controlleurs\Offre\Offre;
use composants\Input\Input;
use composants\Button\Button;
use composants\InputRange\InputRange;
use composants\InputRangeDouble\InputRangeDouble;

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
    public static function render($sort, $titre, $localisation, $minPrix, $maxPrix, $ouverture, $fermeture, $nomcategories,$status=null,$note) {
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
        

        echo '<form id="searchForm" method="GET" action="">';
        Select::render('custom-class', 'select-trie', 'trie', false, $optionsTrie, isset($_GET['etat']) ? $_GET['etat'] : 'tout');
        Select::render('custom-class', 'select-etat', 'etat', false, $optionsEtat, isset($_GET['etat']) ? $_GET['etat'] : 'tout');
        ?>
        <hr>
        <?php
        echo '<input type="hidden" id="sortInput" name="sort" value="' . htmlspecialchars($sort) . '">';
        echo '<div id="input" class="input">';
        echo '<div style="display: grid; gap: 1px;">';
        Input::render(name:"titre",class:'test', type:"text", placeholder:'Titre*', value: htmlspecialchars($titre));
        ?>
        <br>
        <?php
        Input::render(name:"localisation",class:'test', type:"text", placeholder:'localisation', value: htmlspecialchars($localisation));
        echo '</div>';
        ?>
        <hr>
        <?php
        echo '<div class="heure">';
        echo '<div class="aligne"><label for="ouverture">Heure d\'ouverture</label>';
        Input::render(name:"ouverture", type:"time", value: htmlspecialchars($ouverture));
        echo '</div><div class="aligne"><label for="fermeture">Heure de fermeture</label>';
        Input::render(name:"fermeture", type:"time", placeholder:'Heure de fermeture', value: htmlspecialchars($fermeture));
        echo '</div>';
        echo '</div>';
        ?>
        <hr>
        <?php
        echo'<div id="styleShadow">';
        
        
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
        echo'</div>';

        ?>
        <hr>
        <p>Prix :</p>
        <?php


        InputRangeDouble::render(
            $class = "duoslider",
            $id = "rangeSlider",
            $name = "range",
            $required = true,
            $min = 0,
            $max = 100,
            $from = 0,
            $to = 100
        );

        InputRange::render(
            $class = "monoslider",
            $id = "rangeSlider",
            $name = "note",
            $required = true,
            $min = 0,
            $max = 5,
            $value = 0
        );

            echo '<input type="hidden" id="status" name="status" value="' . htmlspecialchars($status) . '">';
        
        echo '</div>';
        echo '</div>';
        echo '</form>';
    }
}
