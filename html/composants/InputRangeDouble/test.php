
<?php

require_once '/home/raphael/Documents/PACT/html/composants/InputRangeDouble/InputRangeDouble.php';
use composants\InputRangeDouble\InputRangeDouble;


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test InputRangeDouble</title>
</head>
<body>
    <form action="#" method="get">
        <?php
            InputRangeDouble::render(
                "custom-class",
                "rangeSelect",
                "rangeSelect",
                true,
                0,
                100,
                20,
                80
            );
        ?>
        <button type="submit">Submit</button>
    </form>
</body>
</html>