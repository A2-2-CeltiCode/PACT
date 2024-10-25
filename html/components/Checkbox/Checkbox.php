<?php

/**
 * @brief Classe pour gérer les cases à cocher HTML.
 *
 * Cette classe fournit une méthode statique pour rendre des cases à cocher
 * HTML avec des options pour personnaliser les attributs et l'apparence.
 */
class Checkbox
{
    private static $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.

    /**
     * @brief Affiche une case à cocher HTML.
     *
     * @param string $class Classes CSS supplémentaires pour la checkbox.
     * @param string $id ID HTML pour la checkbox.
     * @param string $name Nom de la checkbox.
     * @param string $value Valeur de la checkbox.
     * @param string $text Texte à afficher à côté de la checkbox.
     * @param bool $required Indique si le champ est requis.
     * @param bool $checked Indique si la case est cochée par défaut.
     */
    public static function render(
        $class = "",
        $id = "",
        $name = "",
        $value = "",
        $text = "", // Ajouter le paramètre text ici
        $required = false,
        $checked = false
    ) {
        // Préparer les attributs de la checkbox
        $attrs = [
            'class' => $class,
            'id' => $id,
            'name' => $name,
            'value' => $value, // Ajouter la valeur ici
            'required' => $required ? 'required' : '',
            'checked' => $checked ? 'checked' : ''
        ];

        // Inclure CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="/components/Checkbox/Checkbox.css">';
            self::$cssIncluded = true;
        }

        // Rendre l'élément checkbox
        echo "<div class='checkbox-wrapper'>";
        echo "<input type='checkbox' " . self::renderAttributes($attrs) . ">";
        echo "<label for='{$id}'>" . htmlspecialchars($text) . "</label>"; // Utiliser le texte ici
        echo "</div>";
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
