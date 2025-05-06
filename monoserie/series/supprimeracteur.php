<?php
// Web3/monoserie/series/supprimeracteur.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo "Accès refusé.";
    exit();
}

require_once("../class/ActeurManager.php");
require_once("../class/Acteur.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if($id && $id > 0){
    try {
        $acteurManager = new ActeurManager();
        $acteur = $acteurManager->getActeurParId($id);

        if($acteur){
            if ($acteur->supprimer()) {
                header("Location: ../pages/admin.php?message=acteur_supprime");
                exit();
            } else {
                 echo "Erreur lors de la suppression de l'acteur (méthode supprimer a retourné faux).";
            }
        } else {
            echo "Acteur non trouvé avec l'ID " . htmlspecialchars($id);
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la suppression de l'acteur ID $id: " . $e->getMessage());
        echo "Erreur technique lors de la suppression: " . $e->getMessage();
    }
} else {
    echo "ID d'acteur manquant ou invalide.";
}
?>