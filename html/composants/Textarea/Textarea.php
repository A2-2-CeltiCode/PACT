<?php

/**
 * @brief Classe pour gérer les éléments de zone de texte HTML.
 *
 * Cette classe fournit une méthode statique pour rendre des zones de texte
 * HTML avec des options pour personnaliser les attributs et l'apparence.
 */
namespace composants\Textarea;
class Textarea
{
    private static $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.

    /**
     * @brief Affiche un textarea HTML.
     *
     * @param string $class Classes CSS supplémentaires pour la zone de texte.
     * @param string $id ID HTML pour la zone de texte.
     * @param string $name Nom de la zone de texte.
     * @param bool $required Indique si la zone de texte est requise.
     * @param string $value Valeur initiale de la zone de texte.
     * @param int|null $rows Nombre de lignes visibles de la zone de texte.
     * @param int|null $cols Nombre de colonnes visibles de la zone de texte.
     * @param string $placeholder Texte indicatif à afficher quand la zone est vide.
     */
    public static function render(
        $class = "",
        $id = "",
        $name = "",
        $required = false,
        $value = "",
        $rows = 1   ,
        $cols = 1,
        $placeholder = ""
    ) {
        // Préparer les attributs du textarea
        $attrs = [
            'class' => $class,
            'id' => $id,
            'name' => $name,
            'rows' => $rows,
            'cols' => $cols,
            'placeholder' => $placeholder,
            'required' => $required ? 'required' : ''
        ];

        // Inclure CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="/composants/Textarea/Textarea.css">';
            self::$cssIncluded = true;
        }

        // Rendre le textarea
        echo "<div class='textarea-wrapper'><textarea " . self::renderAttributes($attrs) . ">$value</textarea></div>";
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
