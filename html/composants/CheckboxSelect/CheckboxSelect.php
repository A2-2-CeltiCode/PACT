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
     * @param string $buttonText Texte affiché sur le bouton.
     */
    public static function render(
        $class = "",
        $id = "",
        $name = "",
        $required = false,
        $options = [],
        $selected = [],
        $buttonText = "Sélectionner"
    ) {
        // Inclure le CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="/composants/CheckboxSelect/style.css">';
            
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
        
        echo '<button type="button" onclick="toggleCheckboxSelect(\'' . $id . '\')">' . htmlspecialchars($buttonText) . '</button>';
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