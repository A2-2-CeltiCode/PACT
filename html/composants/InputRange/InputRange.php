<?php

namespace composants\InputRange;

class InputRange
{
    private static $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.

    /**
     * @brief Affiche un élément select HTML avec un slider.
     *
     * @param string $class Classes CSS supplémentaires pour le select.
     * @param string $id ID HTML pour le select.
     * @param string $name Nom du select.
     * @param bool $required Indique si le champ est requis.
     * @param int $min Valeur minimale du slider.
     * @param int $max Valeur maximale du slider.
     * @param int $value Valeur initiale du slider.
     */
    public static function render(
        $class = "",
        $id = "",
        $name = "",
        $required = false,
        $min = 0,
        $max = 100,
        $value = 50
    ) {
        // Inclure le CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" type="text/css" href="/composants/InputRange/InputRange.css">';
            self::$cssIncluded = true;
        }

        // Préparer les attributs du select
        $attrs = [
            'class' => $class,
            'id' => $id,
            'name' => $name,
            'required' => $required ? 'required' : ''
        ];

        // Générer le slider
        echo '<div class="slider-select-wrapper">';
        echo '<div class="sliders_control">';
        echo '<input name="' . $name . '" id="' . $id . '" type="range" value="' . $value . '" min="' . $min . '" max="' . $max . '" step="0.5" oninput="syncInputValue(this)"/>';
        echo '</div>';
        echo '<div class="form_control">';
        echo '<div class="form_control_container">';
        echo '<input name="' . 'inputnote'. 'Value" class="form_control_container__time__input" type="number" id="' . $id . 'Input" value="' . $value . '" min="' . $min . '" max="' . $max . '" step="0.5" oninput="syncSliderValue(this)"/>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // Ajouter le script JavaScript pour synchroniser le slider et l'input
        echo '<script src="/composants/InputRange/InputRange.js"></script>';
    }
}