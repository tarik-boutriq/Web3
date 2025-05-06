<?php
session_start(); // Démarrage de la session pour gérer les données utilisateur

session_unset(); // Suppression de toutes les variables de session en cours
session_destroy(); // Destruction complète de la session pour garantir la déconnexion

header("Location: ../"); // Redirection de l'utilisateur vers la page principale après la déconnexion
exit();
?>
