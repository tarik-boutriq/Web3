<?php
require_once __DIR__ . '/../config/connexion.php';
// Classe Acteur permettant de gérer les acteurs d'une série (ajout, modification, suppression)
class Acteur extends Connexion {
    public $id;
    public $nom;
    public $photo;
    public $role;

    public function __construct($id, $nom, $photo, $role) {
        parent::__construct();
        $this->id = $id;
        $this->nom = $nom;
        $this->photo = $photo;
        $this->role = $role;
    }
    // Fonction pour ajouter un ou plusieurs acteurs à une série
    public function ajouter($serie_id, $noms, $photos, $roles) {
        if ($this->access === null) {
            error_log("Erreur de connexion à la base de données dans Acteur::ajouter.");
            return false;
        }

        try {
            if (!is_array($noms)) { $noms = array($noms); }
            if (!is_array($photos)) { $photos = array($photos); }
            if (!is_array($roles)) { $roles = array($roles); }

            if (count($noms) !== count($photos) || count($noms) !== count($roles)) {
                throw new Exception("Les données des acteurs (noms, photos, rôles) sont incohérentes en nombre.");
            }

            $this->access->beginTransaction();

            $stmtActeur = $this->access->prepare("INSERT INTO acteurs (nom, photo, role) VALUES (:nom, :photo, :role)");
            $stmtLink = $this->access->prepare("INSERT INTO series_acteurs (series_id, acteur_id) VALUES (:series_id, :acteur_id)");

            for ($i = 0; $i < count($noms); $i++) {
                 if(empty(trim($noms[$i])) || empty(trim($photos[$i])) || empty(trim($roles[$i]))){
                     throw new Exception("Données d'acteur vides détectées à l'index $i.");
                 }
                $stmtActeur->execute([
                    ':nom'   => trim($noms[$i]),
                    ':photo' => trim($photos[$i]),
                    ':role'  => trim($roles[$i])
                ]);

                $acteur_id = $this->access->lastInsertId();

                $stmtLink->execute([
                    ':series_id' => $serie_id,
                    ':acteur_id' => $acteur_id
                ]);
            }
            $this->access->commit();

            $stmtActeur->closeCursor();
            $stmtLink->closeCursor();

            return true;

        } catch (PDOException $e) {
            if ($this->access->inTransaction()) { $this->access->rollBack(); }
            error_log("Erreur PDO lors de l'ajout des acteurs : " . $e->getMessage());
            return false;
        } catch (Exception $e) {
             if ($this->access->inTransaction()) { $this->access->rollBack(); }
             error_log("Erreur lors de l'ajout des acteurs : " . $e->getMessage());
             return false;
        }
    }
    // Fonction pour modifier les informations d'un acteur existant
    public function modifier($nom, $photo, $role) {
        if ($this->access === null || $this->id === null || $this->id <= 0) {
            error_log("Tentative de modification sans connexion ou ID d'acteur invalide.");
            return false;
        }
        try {
            $req = $this->access->prepare("UPDATE acteurs SET nom = :nom, photo = :photo, role = :role WHERE id = :id");

            $req->bindParam(':id', $this->id, PDO::PARAM_INT);
            $req->bindParam(':nom', $nom, PDO::PARAM_STR);
            $req->bindParam(':photo', $photo, PDO::PARAM_STR);
            $req->bindParam(':role', $role, PDO::PARAM_STR);

            $success = $req->execute();
            $req->closeCursor();

            if ($success) {
                $this->nom = $nom;
                $this->photo = $photo;
                $this->role = $role;
                return true;
            } else {
                error_log("Erreur lors de l'exécution de la requête UPDATE pour l'acteur ID {$this->id}.");
                return false;
            }

        } catch (PDOException $e) {
            error_log("Erreur PDO lors de la modification de l'acteur ID {$this->id} : " . $e->getMessage());
            return false;
        }
    }
    // Fonction pour supprimer un acteur et ses liens avec les séries
    public function supprimer() {
        if ($this->access === null || $this->id === null || $this->id <= 0) {
            error_log("Tentative de suppression sans connexion ou ID d'acteur invalide.");
            return false;
        }
        try {
            $this->access->beginTransaction();

            $stmtLink = $this->access->prepare("DELETE FROM series_acteurs WHERE acteur_id = :acteur_id");
            $stmtLink->bindParam(':acteur_id', $this->id, PDO::PARAM_INT);
            $stmtLink->execute();
            $stmtLink->closeCursor();

            $stmtActeur = $this->access->prepare("DELETE FROM acteurs WHERE id = :id");
            $stmtActeur->bindParam(':id', $this->id, PDO::PARAM_INT);
            $success = $stmtActeur->execute();
            $rowCount = $stmtActeur->rowCount();
            $stmtActeur->closeCursor();


            if ($success && $rowCount > 0) {
                $this->access->commit();
                return true;
            } else {
                 error_log("La suppression de l'acteur ID {$this->id} a échoué (rowCount: {$rowCount}) ou l'acteur n'existait pas. Rollback.");
                 $this->access->rollBack();
                 return false;
            }

        } catch (PDOException $e) {
            if ($this->access->inTransaction()) {
                $this->access->rollBack();
            }
            error_log("Erreur PDO lors de la suppression de l'acteur ID {$this->id} : " . $e->getMessage());
            return false;
        }
    }
}
?>