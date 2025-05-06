<?php
require("../config/commandes.php");
session_start();

// Gestion de la session si l'administrateur est déjà connecté
if (isset($_SESSION['admin_id'])) {
    header("Location: /Web3/monoserie/pages/admin.php");
    exit();
}

$error_message = '';

// Vérification et traitement des données envoyées via le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['motdepasse'])) {
        $email = htmlspecialchars(strip_tags($_POST['email']));
        $motdepasse = $_POST['motdepasse'];

        $admin = getAdmin($email, $motdepasse);

        // Authentification de l'administrateur et stockage de l'identifiant en session
        if ($admin) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: /Web3/monoserie/pages/admin.php");
            exit();
        } else {
            $error_message = 'Identifiants incorrects !';
        }
    } else {
        $error_message = 'Veuillez remplir tous les champs.';
    }

} else {
    header("Location: /Web3/monoserie/pages/login.php");
    exit();
}
?>
