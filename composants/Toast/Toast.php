<?php

/**
 * Class ToastType
 * Cette classe contient les types de toast disponibles (SUCCESS, ERROR, WARNING).
 */
class ToastType {
    /** @var string Type pour un message de succès */
    const SUCCESS = 'success';  

    /** @var string Type pour un message d'erreur */
    const ERROR = 'error'; 

    /** @var string Type pour un message d'avertissement */
    const WARNING = 'warning';      
}

/**
 * Class Toast
 * Gère l'affichage des notifications (toasts) avec des options de style et de script.
 */
class Toast
{
    /** @var bool Indique si les styles CSS ont été inclus */
    private static $cssIncluded = false; 

    /** @var bool Indique si le script JavaScript a été inclus */
    private static $jsIncluded = false;

    /**
     * Affiche un toast avec un message et un type spécifique.
     * 
     * @param string $message Le message à afficher dans le toast.
     * @param string $type Le type de toast (par défaut ToastType::SUCCESS).
     */
    public static function render($message, $type = ToastType::SUCCESS)
    {
        // Inclusion du fichier CSS si nécessaire
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="../../../composants/Toast/Toast.css">';
            self::$cssIncluded = true;
        }

        // Inclusion du fichier JS si nécessaire
        if (!self::$jsIncluded) {
            echo '<script src="../../../composants/Toast/Toast.js"></script>';
            self::$jsIncluded = true;
        }

        // Affichage du conteneur de toast
        echo "<div id=\"toast-container\" data-message=\"{$message}\" data-type=\"{$type}\" style=\"display:none;\"></div>";
    }
}
?>

