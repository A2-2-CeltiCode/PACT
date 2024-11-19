<?php

/**
 * @brief Classe pour gérer les éléments de sélection HTML.
 *
 * Cette classe fournit une méthode statique pour rendre des menus déroulants
 * HTML avec des options pour personnaliser les attributs et l'apparence.
 */
namespace composants\Select;
class Select
{
    private static $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.

    /**
     * @brief Affiche un élément select HTML.
     *
     * @param string $class Classes CSS supplémentaires pour le select.
     * @param string $id ID HTML pour le select.
     * @param string $name Nom du select.
     * @param bool $required Indique si le champ est requis.
     * @param array $options Tableau associatif des options (clé => valeur).
     * @param string $selected Valeur sélectionnée par défaut.
     */
    public static function render(
        $class = "",
        $id = "",
        $name = "",
        $required = false,
        $options = [],
        $selected = ""
    ) {
        // Préparer les attributs du select
        $attrs = [
            'class' => $class,
            'id' => $id,
            'name' => $name,
            'required' => $required ? 'required' : ''
        ];

        // Inclure CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="/composants/Select/Select.css">';
            self::$cssIncluded = true;
        }

        // Rendre l'élément select
        echo "<div class='select-wrapper'><select " . self::renderAttributes($attrs) . ">";

        // Ajouter les options
        foreach ($options as $value => $label) {
            $isSelected = $value === $selected ? "selected" : "";
            echo "<option value='{$value}' {$isSelected}>{$label}</option>";
        }

        echo "</select></div>";
    }

    /**
     * @brief Retourne les attributs d'un élément sous forme de chaîne.
     *
     * @param array $attrs Tableau d'attributs à rendre.
     * @return string Attributs rendus sous forme de chaîne.
     */
    private static function renderAttributes($attrs)
    {
        $result = '';

        foreach ($attrs as $key => $value) {
            if ($value !== null && $value !== '') {
                $result .= "{$key}='{$value}' ";
            }
        }

        return trim($result);
    }
}
