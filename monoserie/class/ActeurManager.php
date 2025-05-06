<?php

require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/Acteur.php';
// Classe ActeurManager permettant de gérer les acteurs dans la base de données (récupération globale ou par ID)
class ActeurManager extends Connexion {
    // Fonction permettant de récupérer tous les acteurs et les renvoyer sous forme d'objets Acteur
    public function getTous() {
        try {
            $req = $this->access->prepare("SELECT id, nom, photo, role FROM acteurs ORDER BY nom ASC");
            $req->execute();
            $acteursData = $req->fetchAll(PDO::FETCH_OBJ);
            $req->closeCursor();

            $acteurs = [];
            foreach ($acteursData as $data) {
                 $acteurs[] = new Acteur($data->id, $data->nom, $data->photo, $data->role);
            }
            return $acteurs;

        } catch (PDOException $e) {
            error_log("Erreur PDO getTous ActeurManager : " . $e->getMessage());
            echo "Erreur lors de la récupération des acteurs.";
            return [];
        }
    }
    // Fonction permettant de récupérer un acteur spécifique en fonction de son ID
    public function getActeurParId($id){
        if(!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
             error_log("Tentative de récupération d'acteur avec un ID invalide: " . $id);
             return null;
        }
        try {
            $req = $this->access->prepare("SELECT id, nom, photo, role FROM acteurs WHERE id = :id");
            $req->bindParam(':id', $id, PDO::PARAM_INT);
            $req->execute();
            $data = $req->fetch(PDO::FETCH_OBJ);
            $req->closeCursor();

            if ($data) {
                return new Acteur($data->id, $data->nom, $data->photo, $data->role);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Erreur PDO getActeurParId pour ID $id : " . $e->getMessage());
            echo "Erreur lors de la récupération de l'acteur.";
            return null;
        }
    }
}
?>