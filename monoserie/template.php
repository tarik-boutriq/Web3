<?php

class Template {

    public static function render(string $content, string $title = 'MonoSerie') : void {
        ?>
        <!doctype html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $title; ?></title>
            <link rel="stylesheet" href="/Web3/monoserie/style/style.css">
            <link rel="stylesheet" href="/Web3/monoserie/style/ajoute.css">
            <link rel="stylesheet" href="/Web3/monoserie/style/list.css">


        </head>
        <body>
            <?php include "header.php"; ?>

            <div id="main-content">
                <?php echo $content; ?>
            </div>

            <?php include "footer.php"; ?>

            <script src="/Web3/monoserie/script/javascript.js"></script>
        </body>
        </html>
        <?php
    }
}

?>