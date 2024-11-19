<?php

/**
 * @brief Classe pour gérer les éléments label HTML avec une icône SVG.
 *
 * Cette classe fournit une méthode statique pour rendre des éléments label HTML,
 * avec des options pour personnaliser les attributs, l'apparence et inclure une icône.
 */
namespace composants\Label;
class Label
{
    private static $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.

    /**
     * @brief Affiche un label HTML avec une icône SVG.
     *
     * @param string $class Classes CSS supplémentaires pour le label.
     * @param string $id ID HTML pour le label.
     * @param string $for ID de l'input associé.
     * @param string $text Texte à afficher dans le label.
     * @param string $icon Chemin vers l'icône SVG à afficher à gauche du texte.
     */
    public static function render(
        $class = "",
        $id = "",
        $for = "",
        $text = "",
        $icon = ""
    ) {
        // Inclure CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="../../../composants/Label/Label.css">';
            self::$cssIncluded = true;
        }

        // Préparer les attributs du label
        $attrs = [
            'class' => $class,
            'id' => $id,
            'for' => $for
        ];

        // Vérifier si l'icône est un fichier SVG
        if ($icon && file_exists($icon)) {
            $svgContent = file_get_contents($icon);

            // Ajouter la classe CSS pour que le style soit appliqué
            echo "<label " . self::renderAttributes($attrs) . ">
                    <div class='label-icon'>
                        <div class='svg-inline'>{$svgContent}</div>
                    </div>{$text}
                </label>";
        } else {
            echo "<label " . self::renderAttributes($attrs) . ">{$text}</label>";
        }
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