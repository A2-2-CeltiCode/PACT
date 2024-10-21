<?php

class CheckBox {
    public static function render($class = "", $id = "", $name = '', $required = false, $checked = false, $text = '') {
        $requiredAttribute = $required ? ' required' : '';
        $checkedAttribute = $checked ? ' checked' : '';

        echo "<input type=\"checkbox\" class=\"button {$class}\" id=\"{$id}\" name=\"{$name}\"{$requiredAttribute}{$checkedAttribute}>";
        echo "<label for=\"{$id}\">{$text}</label>";
    }
}
?>
