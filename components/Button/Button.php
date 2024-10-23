<?php

/**
 * Types de boutons.
 */
class ButtonType {
    const Guest = 'guest';  
    const Member = 'member'; 
    const Pro = 'pro';      
}

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
     * @param string $type Type de bouton (guest, member, pro).
     * @param string $onClick Fonction JavaScript à appeler.
     * @param bool $submit Bouton de soumission ?
     */
    public static function render(
        $class = "",
        $id = "",
        $text = "",
        $type = "",
        $onClick = "",
        $submit = false
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
        }

        // Inclure CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="./components/Button/Button.css">';
            self::$cssIncluded = true;
        }

        // Affiche le bouton
        echo "<button type=\"{$isSubmit}\" class=\"button {$class} {$backgroundColorClass}\" id=\"{$id}\" onclick=\"{$onClick}\";>{$text}</button>";
    }
}
