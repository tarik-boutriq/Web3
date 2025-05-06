<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
     echo "Accès refusé.";
     exit();
}

require_once '../class/Serie.php';

if (
    isset($_POST['nom'], $_POST['image'], $_POST['desc'], $_POST['dure'], $_POST['video'], $_POST['episode']) &&
    !empty($_POST['nom']) && !empty($_POST['image']) && !empty($_POST['desc']) &&
    !empty($_POST['dure']) && !empty($_POST['video']) && !empty($_POST['episode'])
    ) {

    $image   = htmlspecialchars(strip_tags($_POST['image']), ENT_QUOTES, 'UTF-8');
    $video   = htmlspecialchars(strip_tags($_POST['video']), ENT_QUOTES, 'UTF-8');
    $nom     = htmlspecialchars(strip_tags($_POST['nom']), ENT_QUOTES, 'UTF-8');
    $desc    = trim(strip_tags($_POST['desc']));    $dure    = intval($_POST['dure']);
    $episode = intval($_POST['episode']);

    $categories = [];
    if (isset($_POST['categories']) && is_array($_POST['categories'])) {
        $categories = array_map('intval', $_POST['categories']);
        $categories = array_filter($categories, function($val) { return $val > 0; });
    }

    if ($dure <= 0 || $episode <= 0 || empty($image) || empty($video) || empty($nom) || empty($desc)) {
         echo "Données invalides (champs obligatoires manquants ou durée/épisodes invalides).";
         exit;
    }


    try {
        $nouvelleSerie = new Serie(
            null,
            $image,
            $nom,
            $dure,
            $desc,
            $video,
            $episode,
            $categories
        );

        $nouvelleSerie->ajouter();

        header("Location: ../pages/admin.php?message=serie_ajoutee");
        exit();

    } catch (Exception $e) {
        error_log("Erreur lors de l'ajout de la série: " . $e->getMessage());
        echo "Une erreur technique est survenue lors de l'ajout de la série.";
    }

} else {
     echo "Erreur : Informations manquantes ou incorrectes pour ajouter la série.";
}
?>