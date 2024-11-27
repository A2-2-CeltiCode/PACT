<?php

/**
 * @brief Classe pour gérer les éléments de sélection avec cases à cocher HTML.
 *
 * Cette classe fournit une méthode statique pour rendre des menus déroulants
 * HTML avec des cases à cocher pour personnaliser les attributs et l'apparence.
 */
namespace composants\CheckboxSelect;

class CheckboxSelect
{
    private static $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.

    /**
     * @brief Affiche un élément select HTML avec des cases à cocher.
     *
     * @param string $class Classes CSS supplémentaires pour le select.
     * @param string $id ID HTML pour le select.
     * @param string $name Nom du select.
     * @param bool $required Indique si le champ est requis.
     * @param array $options Tableau associatif des options (clé => valeur).
     * @param array $selected Valeurs sélectionnées par défaut.
     */
    public static function render(
        $class = "",
        $id = "",
        $name = "",
        $required = false,
        $options = [],
        $selected = []
    ) {
        // Inclure le CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<style>
                .checkbox-select-wrapper {
                    position: relative;
                    display: inline-block;
                }
                .checkbox-select {
                    display: none;
                    position: absolute;
                    background-color: #f9f9f9;
                    min-width: 160px;
                    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                    z-index: 1;
                }
                .checkbox-select.show {
                    display: block;
                }
                .checkbox-select label {
                    display: block;
                    padding: 8px 16px;
                    cursor: pointer;
                }
                .checkbox-select label:hover {
                    background-color: #f1f1f1;
                }
            </style>';
            self::$cssIncluded = true;
        }

        // Préparer les attributs du select
        $attrs = [
            'class' => $class,
            'id' => $id,
            'name' => $name . '[]',
            'required' => $required ? 'required' : ''
        ];

        // Générer le bouton pour afficher la liste déroulante
        echo '<div class="checkbox-select-wrapper">';
        echo '<button type="button" onclick="toggleCheckboxSelect(\'' . $id . '\')">Sélectionner</button>';
        echo '<div id="' . $id . '" class="checkbox-select">';

        // Générer les options avec cases à cocher
        foreach ($options as $value => $label) {
            $isChecked = in_array($value, $selected) ? 'checked' : '';
            echo '<label>';
            echo '<input type="checkbox" name="' . $name . '[]" value="' . htmlspecialchars($value) . '" ' . $isChecked . '>';
            echo htmlspecialchars($label);
            echo '</label>';
        }

        echo '</div>';
        echo '</div>';

        // Ajouter le script JavaScript pour gérer l'affichage de la liste déroulante
        echo '<script>
            function toggleCheckboxSelect(id) {
                var element = document.getElementById(id);
                if (element.classList.contains("show")) {
                    element.classList.remove("show");
                } else {
                    element.classList.add("show");
                }
            }

            document.addEventListener("click", function(event) {
                var dropdowns = document.getElementsByClassName("checkbox-select");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (!openDropdown.contains(event.target) && !event.target.matches("button")) {
                        openDropdown.classList.remove("show");
                    }
                }
            });
        </script>';
    }
}