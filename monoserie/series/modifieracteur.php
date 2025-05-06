<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
     echo "Accès refusé.";
     exit();
}

require_once '../class/ActeurManager.php';
require_once '../class/Acteur.php';

if (
    isset($_POST['acteur_id'], $_POST['nom'], $_POST['photo'], $_POST['role']) AND
    !empty($_POST['acteur_id']) AND !empty($_POST['nom']) AND
    !empty($_POST['photo']) AND !empty($_POST['role'])
) {
    $id     = intval($_POST['acteur_id']);
    $nom    = htmlspecialchars(strip_tags($_POST['nom']), ENT_QUOTES, 'UTF-8');
    $photo  = htmlspecialchars(strip_tags($_POST['photo']), ENT_QUOTES, 'UTF-8');
    $role   = htmlspecialchars(strip_tags($_POST['role']), ENT_QUOTES, 'UTF-8');


    if (empty($nom) || empty($photo) || empty($role)) {
        echo "Données invalides (nom, photo ou rôle vide).";
        exit;
    }

    try {
        $acteurManager = new ActeurManager();
        $acteur = $acteurManager->getActeurParId($id);

        if ($acteur) {
            if ($acteur->modifier($nom, $photo, $role)) {
                header("Location: ../pages/admin.php?message=acteur_modifie");
                exit();
            } else {
                echo "Erreur lors de la modification de l'acteur (méthode modifier a retourné faux).";
            }
        } else {
            echo "Erreur : Acteur non trouvé avec l'ID " . htmlspecialchars($id);
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la modification de l'acteur ID $id: " . $e->getMessage());
        echo "Une erreur technique est survenue lors de la modification.";
    }
} else {
     echo "Erreur : Toutes les informations requises ne sont pas fournies ou sont vides.";
}
?>