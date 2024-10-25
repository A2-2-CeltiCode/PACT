<?php

/**
 * Types de Toast.
 */
class ToastType {
    const SUCCESS = 'success';  
    const ERROR = 'error'; 
    const WARNING = 'warning';      
}

/**
 * Classe pour gérer les toasts.
 */
class Toast
{
    private static $cssIncluded = false; 
    private static $jsIncluded = false;

    /**
     * Rendre un toast HTML.
     *
     * @param string $message Le message à afficher.
     * @param string $type Le type de toast (success, error, warning).
     */
    public static function render($message, $type = ToastType::SUCCESS)
    {
        // Inclure CSS une seule fois
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="./components/Toast/Toast.css">';
            self::$cssIncluded = true;
        }

        // Inclure JavaScript une seule fois
        if (!self::$jsIncluded) {
            echo '<script src="./components/Toast/Toast.js"></script>';
            self::$jsIncluded = true;
        }

        // Rendre le toast avec le message et le type approprié
        echo "<div class=\"toast {$type}\">{$message}</div>";
    }
}
?>
