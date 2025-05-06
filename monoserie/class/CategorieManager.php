<?php 
	
	require_once __DIR__ . '/../config/connexion.php';
	// Classe CategorieManager pour gérer la récupération des catégories depuis la base de données
	class CategorieManager extends Connexion{
	    // Fonction permettant de récupérer toutes les catégories triées par ordre alphabétique
		public function getTous() {
            try {
				$req = $this->access->prepare("SELECT id, nom FROM categories ORDER BY nom ASC"); 
				$req->execute();
				$categories = $req->fetchAll(PDO::FETCH_OBJ);
				$req->closeCursor();
				return $categories;

            } catch (PDOException $e) {
                echo "Erreur lors de la récupération des catégories.";
                return [];
            }
		}
	}
	
?>