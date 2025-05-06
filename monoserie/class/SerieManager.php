<?php

require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/Serie.php';
// Classe SerieManager permettant de gérer la récupération des séries (toutes, par nom ou par ID)

class SerieManager extends Connexion{
    // Fonction permettant de récupérer toutes les séries avec leurs catégories associées
    public function getTous() {
        $req = $this->access->prepare("
            SELECT
                s.*,
                GROUP_CONCAT(c.nom SEPARATOR ', ') AS categories
            FROM series s
            LEFT JOIN series_categories sc ON s.id = sc.series_id
            LEFT JOIN categories c ON sc.categorie_id = c.id
            GROUP BY s.id
            ORDER BY s.id DESC
        ");
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $data;
    }
    // Fonction permettant de récupérer l'identifiant d'une série en fonction de son nom
    public function getSerieParNom($nom) {
        $req = $this->access->prepare("SELECT id FROM series WHERE nom = :nom LIMIT 1");
        $req->bindParam(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        $data = $req->fetch(PDO::FETCH_OBJ);
        $req->closeCursor();
        return ($data && isset($data->id)) ? $data->id : null;
    }
    // Fonction permettant de récupérer une série spécifique en fonction de son ID, avec ses catégories
    public function getSerieParId($id){
        if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
             error_log("Tentative de récupération de série avec un ID invalide: " . $id);
             return null;
        }
        try {
            $req = $this->access->prepare("
                SELECT
                    s.*,
                    GROUP_CONCAT(c.nom SEPARATOR ' , ') AS categories
                FROM series s
                LEFT JOIN series_categories sc ON s.id = sc.series_id
                LEFT JOIN categories c ON sc.categorie_id = c.id
                WHERE s.id = :id
                GROUP BY s.id
            ");
            $req->bindParam(':id', $id, PDO::PARAM_INT);
            $req->execute();
            $data = $req->fetch(PDO::FETCH_OBJ);

            $req->closeCursor();

            if ($data) {
                return new Serie($data->id, $data->image, $data->nom, $data->dure, $data->description, $data->video, $data->episode, $data->categories ?? null);
            } else {
                return null;
            }
        } catch (PDOException $e) {
             error_log("Erreur PDO getSerieParId pour ID $id : " . $e->getMessage());
             echo "Erreur lors de la récupération de la série.";
             return null;
        }
    }
}


?>