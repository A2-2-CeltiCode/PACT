<?php

/**
 * @brief Classe pour gérer l'insertion d'une zone de dépôt d'image.
 *
 * Cette classe fournit une méthode statique pour rendre une zone où l'on peut
 * glisser-déposer des images, avec des options pour personnaliser l'apparence
 * et les comportements.
 */
namespace composants\InsererImage;
class InsererImage
{
    private static $cssIncluded = false; ///< Indicateur pour l'inclusion CSS.
    private static $jsIncluded = false; ///< Indicateur pour l'inclusion JavaScript.

    /**
     * @brief Affiche la zone de dépôt d'image.
     *
     * @param string $id ID HTML pour la zone de dépôt.
     * @param string $message Message affiché dans la zone de dépôt.
     * @param int $maxFiles Nombre maximum de fichiers autorisés.
     * @param bool $multiple Permet de déposer plusieurs fichiers.
     * @param bool $required Spécifie si l'entrée est obligatoire ou non.
     * @param array $acceptedExtensions Extensions d'image acceptées (ex: ['jpg', 'png']).
     */
    public static function render($id = "drop-zone", $message = "Déposez une image ou cliquez ici", $maxFiles = 5, $multiple = true, $required = false, $acceptedExtensions = [])
    {
        // Inclure le CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="/composants/InsererImage/InsererImage.css">';
            self::$cssIncluded = true;
        }
    
        // Inclure le JavaScript une seule fois
        if (!self::$jsIncluded) {
            echo '<script src="/composants/InsererImage/InsererImage.js" defer></script>';
            self::$jsIncluded = true;
        }

        // Définir l'attribut multiple si $multiple est vrai
        $multipleAttr = $multiple ? 'multiple' : '';
        
        // Définir l'attribut required si $required est vrai
        $requiredAttr = $required ? 'required' : '';

        // Définir l'attribut accept en fonction des extensions fournies
        $acceptStr = !empty($acceptedExtensions) ? 'accept="' . implode(',', array_map(function($ext) {
            return '.' . $ext;
        }, $acceptedExtensions)) . '"' : '';
    
        // Rendre la zone de dépôt d'image avec les paramètres dynamiques
        echo "
        <div class='drop-zone' id='{$id}' data-id='{$id}' data-max-files='{$maxFiles}'>{$message}</div>
        <input type='file' id='fileInput-{$id}' name='{$id}' {$acceptStr} {$multipleAttr} {$requiredAttr} style='display: none;'>
        <div id='successMessage-{$id}' style='display:none; color: green;'>Image ajoutée avec succès !</div><br>
        ";
    }
}
