<?php
/**
 * @class Input
 * @brief Classe utilitaire pour générer des champs de formulaire HTML.
 */
class Input
{
    /**
     * @var bool $cssIncluded
     * @brief Indicateur pour savoir si la CSS a déjà été incluse.
     */
    private static $cssIncluded = false;

    /**
     * @brief Génère un champ de formulaire HTML.
     * 
     * @param string $class       Classe CSS du champ (optionnel).
     * @param string $id          ID du champ (optionnel).
     * @param string $type        Type du champ (ex: "text", "email", "password").
     * @param string $name        Nom du champ (optionnel).
     * @param bool $required      Si le champ est requis ou non (optionnel).
     * @param string $value       Valeur initiale (optionnel).
     * @param int|null $minLength Longueur minimale (optionnel).
     * @param int|null $maxLength Longueur maximale (optionnel).
     * @param string $pattern     Modèle regex à respecter (optionnel).
     * @param string $placeholder Texte indicatif (optionnel).
     * @param string $icon        Chemin de l'icône SVG (optionnel).
     * @param string $tooltip     Infobulle (optionnel).
     * 
     * @return void
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
        $placeholder = "",
        $icon = "",
        $tooltip = ""
    ) {
        switch ($type) {
            case 'email':
                $pattern = $pattern ?: '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$';
                break;
            case 'password':
                $pattern = $pattern ?: '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}';
                break;
        }

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
            'pattern' => $pattern,
            'title' => $tooltip
        ];

        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="../../../composants/Input/Input.css">';
            self::$cssIncluded = true;
        }

        $input = "<input " . self::renderAttributes($attrs) . " />";
        if ($icon) {
            $svgContent = self::cleanSvgContent(file_get_contents($icon));
            echo "<div class='input-wrapper'><div class='input-icon'>{$svgContent}</div>$input</div>";
        } else {
            echo "<div class='input-wrapper'>$input</div>";
        }
    }

    /**
     * @brief Nettoie le contenu SVG.
     * 
     * @param string $svgContent Contenu SVG brut.
     * @return string Contenu SVG nettoyé.
     */
    private static function cleanSvgContent($svgContent)
    {
        return preg_replace(['/viewBox="[^"]*"/', '/<text[^>]*>.*?<\/text>/s'], ['viewBox="0 0 100 100"', ''], $svgContent);
    }

    /**
     * @brief Génère une chaîne d'attributs HTML à partir d'un tableau.
     * 
     * @param array $attrs Attributs HTML.
     * @return string Chaîne d'attributs HTML.
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
