<?php

namespace composants\Input;
/**
 * @brief Classe pour gérer les éléments de saisie HTML.
 *
 * Cette classe fournit une méthode statique pour rendre divers types d'éléments de saisie
 * HTML, avec des options pour personnaliser les attributs et l'apparence.
 */
class Input
{
    private static bool $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.

    /**
     * @brief Affiche un input HTML.
     *
     * @param string   $class     Classes CSS supplémentaires pour le champ de saisie.
     * @param string   $id        ID HTML pour le champ de saisie.
     * @param string   $type      Type d'input (text, email, password, etc.).
     * @param string   $name      Nom du champ de saisie.
     * @param bool     $required  Indique si le champ est requis.
     * @param string   $value     Valeur initiale du champ.
     * @param int|null $minLength Longueur minimale de la saisie.
     * @param int|null $maxLength Longueur maximale de la saisie.
     * @param string   $pattern   Modèle de validation pour le champ.
     * @param string   $placeholder Placeholder pour le champ.
     * @param string   $icon      Chemin vers l'icône SVG à afficher à gauche du champ.
     * @param int|null $min       Valeur minimale pour le champ.
     * @param int|null $max       Valeur maximale pour le champ.
     */
    public static function render(string $class = "",
                                  string $id = "",
                                  string $type = "text",
                                  string $name = "",
                                  bool   $required = false,
                                  string $value = "",
                                  ?int   $minLength = null,
                                  ?int   $maxLength = null,
                                  string $pattern = "",
                                  string $placeholder = "",
                                  string $icon = "",
                                  ?int   $min = null,
                                  ?int   $max = null): void {
        // Définir le modèle par défaut selon le type
        switch ($type) {
            case 'email':
                $pattern = $pattern ?: '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$';
                break;
            case 'password':
                $pattern = $pattern ?: '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}';
                break;
            default:
                break;
        }

        // Préparer les attributs de l'input
        $attrs = ['type' => $type, 'class' => $class, 'id' => $id, 'name' => $name, 'value' => $value,
            'placeholder' => $placeholder, 'required' => $required ? 'required' : '', 'minlength' => $minLength,
            'maxlength' => $maxLength, 'pattern' => $pattern, 'min' => $min, 'max' => $max];

        // Inclure CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="/composants/Input/Input.css">';
            self::$cssIncluded = true;
        }

        // Rendre l'input
        $input = "<input " . self::renderAttributes($attrs) . " />";
        if ($icon) {
            $svgContent = self::cleanSvgContent(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $icon));
            echo "<div class='input-wrapper'><div class='input-icone'>{$svgContent}</div>$input</div>";
        } else {
            echo "<div class='input-wrapper'>$input</div>";
        }
    }

    /**
     * @brief Retourne les attributs d'un élément sous forme de chaîne.
     *
     * @param array $attrs Tableau d'attributs à rendre.
     *
     * @return string Attributs rendus sous forme de chaîne.
     */
    private static function renderAttributes(array $attrs): string {
        $result = '';

        foreach ($attrs as $key => $value) {
            if ($value !== null && $value !== '') {
                $result .= "{$key}='{$value}' ";
            }
        }

        return trim($result);
    }

    /**
     * @brief Nettoyer le contenu SVG pour l'affichage.
     *
     * @param string $svgContent Contenu SVG à nettoyer.
     *
     * @return string Contenu SVG nettoyé.
     */
    private static function cleanSvgContent(string $svgContent): string {
        return preg_replace(['/viewBox="[^"]*"/', '/<text[^>]*>.*?<\/text>/s'], ['viewBox="0 0 100 100"', ''],
            $svgContent);
    }
}
