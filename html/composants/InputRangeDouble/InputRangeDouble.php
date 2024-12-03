<?php

/**
 * @brief Classe pour gérer les éléments de sélection avec des sliders HTML.
 *
 * Cette classe fournit une méthode statique pour rendre des éléments de sélection
 * HTML avec des sliders pour personnaliser les attributs et l'apparence.
 */
namespace composants\InputRangeDouble;

class InputRangeDouble
{
    private static $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.

    /**
     * @brief Affiche un élément select HTML avec des sliders.
     *
     * @param string $class Classes CSS supplémentaires pour le select.
     * @param string $id ID HTML pour le select.
     * @param string $name Nom du select.
     * @param bool $required Indique si le champ est requis.
     * @param int $min Valeur minimale du slider.
     * @param int $max Valeur maximale du slider.
     * @param int $from Valeur initiale du slider "from".
     * @param int $to Valeur initiale du slider "to".
     */
    public static function render(
        $class = "",
        $id = "",
        $name = "",
        $required = false,
        $min = 0,
        $max = 100,
        $from = 10,
        $to = 40
    ) {
        // Inclure le CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" type="text/css" href="/composants/InputRangeDouble/InputRangeDouble.css">';
            self::$cssIncluded = true;
        }

        // Préparer les attributs du select
        $attrs = [
            'class' => $class,
            'id' => $id,
            'name' => $name,
            'required' => $required ? 'required' : ''
        ];

        // Générer les sliders
        echo '<div class="slider-select-wrapper">';
        echo '<div class="sliders_control">';
        echo '<input name="minPrix" id="fromSlider" type="range" value="' . $from . '" min="' . $min . '" max="' . $max . '"/>';
        echo '<input name="maxPrix" id="toSlider" type="range" value="' . $to . '" min="' . $min . '" max="' . $max . '"/>';
        echo '</div>';
        echo '<div class="form_control">';
        echo '<div class="form_control_container">';
        echo '<div class="form_control_container__time">Min</div>';
        echo '<input name="prixMin" class="form_control_container__time__input" type="number" id="fromInput" value="' . $from . '" min="' . $min . '" max="' . $max . '"/>';
        echo '</div>';
        echo '<div class="form_control_container">';
        echo '<div class="form_control_container__time">Max</div>';
        echo '<input name="prixMax"class="form_control_container__time__input" type="number" id="toInput" value="' . $to . '" min="' . $min . '" max="' . $max . '"/>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // Ajouter le script JavaScript pour synchroniser les sliders et les inputs
        echo '<script src="/composants/InputRangeDouble/InputRangeDouble.js"></script>';
    }
}

