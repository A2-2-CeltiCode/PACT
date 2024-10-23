<?php

/**
 * @brief Classe pour gérer les éléments de saisie HTML.
 *
 * Cette classe fournit une méthode statique pour rendre divers types d'éléments de saisie
 * HTML, avec des options pour personnaliser les attributs et l'apparence.
 */
class Input
{
    private static $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.

    /**
     * @brief Affiche un input HTML.
     *
     * @param string $class Classes CSS supplémentaires pour le champ de saisie.
     * @param string $id ID HTML pour le champ de saisie.
     * @param string $type Type d'input (text, email, password, etc.).
     * @param string $name Nom du champ de saisie.
     * @param bool $required Indique si le champ est requis.
     * @param string $value Valeur initiale du champ.
     * @param int|null $minLength Longueur minimale de la saisie.
     * @param int|null $maxLength Longueur maximale de la saisie.
     * @param string $pattern Modèle de validation pour le champ.
     * @param string $icon Chemin vers l'icône SVG à afficher à gauche du champ.
     */
    public static function render(
        $class = "",
        $id = "",
        $type = "text",
        $name = "",
        $required = false,
        $value = "",
        $minLength = null,
        $maxLength = null,
        $pattern = "",
        $placeholder= "",
        $icon = ""
    ) {
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
        $attrs = [
            'type' => $type,
            'class' => $class,
            'id' => $id,
            'name' => $name,
            'value' => $value,
            'placeholder' => $placeholder,
            'required' => $required ? 'required' : '',
            'minlength' => $minLength,
            'maxlength' => $maxLength,
            'pattern' => $pattern
        ];
    
        // Inclure CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="./components/Input/Input.css">';
            self::$cssIncluded = true;
        }
    
        // Rendre l'input
        $input = "<input " . self::renderAttributes($attrs) . " />";
        if ($icon) {
            $svgContent = self::cleanSvgContent(file_get_contents($icon));
            echo "<div class='input-wrapper'><div class='input-icon'>{$svgContent}</div>$input</div>";
        } else {
            echo "<div class='input-wrapper'>$input</div>";
        }
    }

    /**
     * @brief Nettoyer le contenu SVG pour l'affichage.
     *
     * @param string $svgContent Contenu SVG à nettoyer.
     * @return string Contenu SVG nettoyé.
     */
    private static function cleanSvgContent($svgContent)
    {
        return preg_replace(['/viewBox="[^"]*"/', '/<text[^>]*>.*?<\/text>/s'], ['viewBox="0 0 100 100"', ''], $svgContent);
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
