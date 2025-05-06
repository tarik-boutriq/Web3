<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_id'])) {
     echo "Accès refusé.";
     exit;
}

require_once '../class/SerieManager.php';
require_once '../class/Serie.php';
// Vérification et nettoyage des données du formulaire avant la modification de la série
if (
    isset($_POST['serie_id'], $_POST['nom'], $_POST['image'], $_POST['desc'], $_POST['dure'], $_POST['video'], $_POST['episode']) &&
    !empty($_POST['serie_id']) && !empty(trim($_POST['nom'])) && !empty(trim($_POST['image'])) &&
    !empty(trim($_POST['desc'])) && !empty($_POST['dure']) && !empty(trim($_POST['video'])) &&
    !empty($_POST['episode'])
    ) {

    $id      = intval($_POST['serie_id']);
    $image   = htmlspecialchars(strip_tags($_POST['image']), ENT_QUOTES, 'UTF-8');
    $video   = htmlspecialchars(strip_tags($_POST['video']), ENT_QUOTES, 'UTF-8');
    $nom     = htmlspecialchars(strip_tags(trim($_POST['nom'])), ENT_QUOTES, 'UTF-8');
    $desc    = trim(strip_tags($_POST['desc']));    $dure    = intval($_POST['dure']);
    $episode = intval($_POST['episode']);
    // Vérification et filtrage des catégories si présentes
    $categories = [];
    if (isset($_POST['categories']) && is_array($_POST['categories'])) {
        $categories = array_map('intval', $_POST['categories']);
        $categories = array_filter($categories, function($val) { return $val > 0; });
    }
    // Vérification finale des données avant la modification
    if ($dure <= 0 || $episode <= 0 || empty($image) || empty($video) || empty($nom) || empty($desc)) {
         echo "Données invalides (champs obligatoires manquants ou invalides après nettoyage).";
         exit;
    }

    try {
        // Récupération de la série par ID via SerieManager
        $serieManager = new SerieManager();
        $serie = $serieManager->getSerieParId($id);
        // Vérification que la série existe avant de tenter la modification
        if ($serie) {
            $serie->modifier($image, $nom, $dure, $desc, $video, $episode, $categories);
            // Redirection vers la page d'administration avec un message de succès
            header("Location: ../pages/admin.php?message=serie_modifiee");
            exit();
        } else {
            echo "Erreur : Série non trouvée avec l'ID " . htmlspecialchars($id);
            exit;
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la modification de la série ID $id: " . $e->getMessage());
        echo "Une erreur technique est survenue lors de la modification.";
        exit;
    }
} else {
     echo "Erreur : Toutes les informations requises ne sont pas fournies ou sont vides.";
     exit;
}
?>