<?php

/**
 * Types de boutons.
 */
class ButtonType
{
    const Guest = 'guest';
    const Member = 'member';
    const Pro = 'pro';
    const Cancel = 'cancel';
    const Valid = 'valid';
}

/**
 * Classe pour gérer les boutons.
 */
/**
 * Classe pour gérer les boutons.
 */
class Button
{
    private static $cssIncluded = false;

    /**
     * Affiche un bouton HTML.
     *
     * @param string $class Classes CSS supplémentaires.
     * @param string $id ID du bouton.
     * @param string $text Texte du bouton.
     * @param string $type Type de bouton (guest, member, pro, cancel, valid).
     * @param string $onClick Fonction JavaScript à appeler.
     * @param bool $submit Bouton de soumission ?
     * @param string $path Chemin de redirection (facultatif).
     */
    public static function render(
        $class = "",
        $id = "",
        $text = "",
        $type = "",
        $onClick = "",
        $submit = false,
        $path = ""  // Nouveau paramètre pour la redirection
    ) {
        $isSubmit = $submit ? 'submit' : 'button';

        // Définir la couleur de fond selon le type de bouton
        $backgroundColorClass = '';
        switch ($type) {
            case ButtonType::Guest:
                $backgroundColorClass = 'bg-guest';
                break;
            case ButtonType::Member:
                $backgroundColorClass = 'bg-member';
                break;
            case ButtonType::Pro:
                $backgroundColorClass = 'bg-pro';
                break;
            case ButtonType::Cancel:
                $backgroundColorClass = 'bg-cancel';
                break;
            case ButtonType::Valid:
                $backgroundColorClass = 'bg-valid';
                break;
        }

        // Inclure CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="../../../composants/Button/Button.css">';
            self::$cssIncluded = true;
        }

        // Si un chemin est fourni, on fait un lien avec un bouton à l'intérieur
        if (!empty($path)) {
            echo "<a href=\"{$path}\" class=\"button-link\">";  // Le lien autour du bouton
        }
        else {
            echo "<button type=\"{$isSubmit}\" class=\"button {$class} {$backgroundColorClass}\" id=\"{$id}\" onclick=\"{$onClick}\";>{$text}</button>";
        }

        // Affiche le bouton
        echo "<button type=\"{$isSubmit}\" class=\"button {$class} {$backgroundColorClass}\" id=\"{$id}\" onclick=\"{$onClick}\";>{$text}</button>";

        // Ferme la balise du lien si un chemin est fourni
        if (!empty($path)) {
            echo "</a>";
        }
    }
}
