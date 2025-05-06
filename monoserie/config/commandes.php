<?php
// Fonction permettant de récupérer un administrateur en fonction de son email et mot de passe
function getAdmin($email, $motdepasse) {
    $req = null;
    try {
        require("connexion.php");
        $cnx = new Connexion();
        $access = $cnx->access;

        $req = $access->prepare("SELECT id, email, motdepasse FROM admin WHERE email = :email");
        $req->bindParam(':email', $email);
        $req->execute();
        $admin = $req->fetch(PDO::FETCH_ASSOC);
        // Vérification de l'existence de l'administrateur et validation du mot de passe
        if ($admin) {

            if ($motdepasse === $admin['motdepasse']) {
                return $admin; 
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    } catch (PDOException $e) {
        error_log("Erreur lors de la requête : " . $e->getMessage());
        return false;
    } finally {
        if ($req !== null) {
            $req->closeCursor();
        }

    }
}

?>