<?php
session_start();
// Vérification de l'authentification : l'accès est refusé si l'utilisateur n'est pas connecté
if (!isset($_SESSION['admin_id'])) {
    echo "Accès refusé.";
    exit();
}

require_once '../class/Acteur.php';
require_once '../config/connexion.php'; 
// Vérification et filtrage des données du formulaire avant insertion dans la base de données

if (
    isset($_POST['serie_id'], $_POST['nom'], $_POST['photo'], $_POST['role']) AND !empty($_POST['serie_id']) AND
    is_array($_POST['nom']) AND !empty($_POST['nom']) AND
    is_array($_POST['photo']) AND !empty($_POST['photo']) AND
    is_array($_POST['role']) AND !empty($_POST['role'])
) {

    $serie_id = filter_input(INPUT_POST, 'serie_id', FILTER_VALIDATE_INT);

    if (count($_POST['nom']) !== count($_POST['photo']) OR count($_POST['nom']) !== count($_POST['role'])) {
        echo "Erreur : Le nombre d'éléments pour nom, photo et rôle doit être identique.";
        exit;
    }

    $noms = array_map(function($v) {
        return htmlspecialchars(strip_tags(trim($v)), ENT_QUOTES, 'UTF-8');
    }, $_POST['nom']);

    $photos = array_map(function($v) {
        return htmlspecialchars(strip_tags(trim($v)), ENT_QUOTES, 'UTF-8');
    }, $_POST['photo']);

    $roles = array_map(function($v) {
        return htmlspecialchars(strip_tags(trim($v)), ENT_QUOTES, 'UTF-8');
    }, $_POST['role']);

    $noms_filtres = [];
    $photos_filtres = [];
    $roles_filtres = [];
    for ($i = 0; $i < count($noms); $i++) {
        if (!empty($noms[$i]) AND !empty($photos[$i]) AND !empty($roles[$i])) {
            $noms_filtres[] = $noms[$i];
            $photos_filtres[] = $photos[$i];
            $roles_filtres[] = $roles[$i];
        }
    }

    if (!$serie_id OR $serie_id <= 0 OR empty($noms_filtres)) {
          echo "Données invalides (ID série ou informations acteur manquantes après filtrage).";
          exit;
    }


    try {
        $acteurInstancePourAppel = new Acteur(null, '', '', '');

        $success = $acteurInstancePourAppel->ajouter($serie_id, $noms_filtres, $photos_filtres, $roles_filtres);

        if($success) {
             header("Location: ../pages/admin.php?message=acteurs_ajoutes"); 
             exit();
        } else {
             echo "Une erreur est survenue lors de l'ajout des acteurs (méthode ajouter a retourné faux).";
        }

    } catch (Exception $e) {
        error_log("Erreur lors de l'ajout des acteurs: " . $e->getMessage());
        echo "Une erreur technique est survenue lors de l'ajout des acteurs.";
    }

} else {
     echo "Erreur : Informations manquantes ou incorrectes pour ajouter les acteurs.";
}
?>