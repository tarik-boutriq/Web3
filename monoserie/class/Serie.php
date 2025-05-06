<?php

	require_once __DIR__ . '/../config/connexion.php';
	// Classe Serie permettant de gérer les séries (ajout, modification, suppression, récupération des acteurs)
	

	class Serie extends Connexion{
		public $id;
		public $image;
		public $nom;
		public $dure;
		public $description;
		public $video;
		public $episode;
		public $categories;

		public function __construct($id, $image, $nom, $dure, $description, $video, $episode, $categories) {
			parent::__construct();
			$this->id = $id;
			$this->image = $image;
			$this->nom = $nom;
			$this->dure = $dure;
			$this->description = $description;
			$this->video = $video;
			$this->episode = $episode;
			$this->categories = $categories;
		}

		// Ajoute une nouvelle série dans la base de données, avec ses catégories associées
		public function ajouter() {
			
			try {
				$req = $this->access->prepare("INSERT INTO series (image, nom, dure, description, video, episode) VALUES (:image, :nom, :dure, :description, :video, :episode)");
				$req->bindParam(':image', $this->image);
				$req->bindParam(':nom', $this->nom);
				$req->bindParam(':dure', $this->dure, PDO::PARAM_INT);
				$req->bindParam(':description', $this->description);
				$req->bindParam(':video', $this->video);
				$req->bindParam(':episode', $this->episode, PDO::PARAM_INT);
				$req->execute();

				$newSeriesId = $this->access->lastInsertId();
				$req->closeCursor();

				if (!empty($this->categories) && is_array($this->categories)) {
					$stmt = $this->access->prepare("INSERT INTO series_categories (series_id, categorie_id) VALUES (:series_id, :categorie_id)");
					foreach ($this->categories as $categorieId) {
						$stmt->bindParam(':series_id', $newSeriesId, PDO::PARAM_INT);
						$stmt->bindParam(':categorie_id', $categorieId, PDO::PARAM_INT);
						$stmt->execute();
					}
					$stmt->closeCursor();
				}
			} catch (PDOException $e) {
                error_log("PDOException dans Serie::ajouter : " . $e->getMessage());
                echo "Une erreur est survenue lors de l'ajout de la série.";
			}
		}
   		 // Supprime une série ainsi que les liens avec ses catégories
		public function supprimer() {
			try {
				$stmt = $this->access->prepare("DELETE FROM series_categories WHERE series_id = ?");
				$stmt->execute(array($this->id));
				$stmt->closeCursor();

				$req = $this->access->prepare("DELETE FROM series WHERE id = ?");
				$req->execute(array($this->id));
				$req->closeCursor();
			} catch (PDOException $e) {
                error_log("PDOException dans Serie::supprimer pour ID {$this->id}: " . $e->getMessage());
				echo "Une erreur est survenue lors de la suppression.";
			}
		}

    	// Modifie les informations d'une série, y compris ses catégories
		public function modifier($image = null, $nom = null, $dure = null, $description = null, $video = null, $episode = null, $categories = null) {
			try {
				$this->access->beginTransaction();
				// Préparation de la requête SQL en fonction des paramètres fournis

				$sql = "UPDATE series SET ";
				$fieldsToUpdate = [];
				$bindParams = [':id' => $this->id];

				if ($image !== null) {
					$fieldsToUpdate[] = "image = :image";
					$bindParams[':image'] = $image;
				}
				if ($nom !== null) {
					$fieldsToUpdate[] = "nom = :nom";
					$bindParams[':nom'] = $nom;
				}
				if ($dure !== null) {
					$fieldsToUpdate[] = "dure = :dure";
					$bindParams[':dure'] = intval($dure); 
				}
				if ($description !== null) {
					$fieldsToUpdate[] = "description = :description";
					$bindParams[':description'] = $description;
				}
				if ($video !== null) {
					$fieldsToUpdate[] = "video = :video";
					$bindParams[':video'] = $video;
				}
				if ($episode !== null) {
					$fieldsToUpdate[] = "episode = :episode";
					$bindParams[':episode'] = intval($episode); 
				}

				if (!empty($fieldsToUpdate)) {
					$sql .= implode(", ", $fieldsToUpdate);
					$sql .= " WHERE id = :id";

					$req = $this->access->prepare($sql);

                    foreach ($bindParams as $key => $value) {
                         if (is_int($value)) {
                             $req->bindValue($key, $value, PDO::PARAM_INT);
                         } else {
                             $req->bindValue($key, $value, PDO::PARAM_STR);
                         }
                     }

					$req->execute(); 
					$req->closeCursor();
				}
				// Mise à jour des catégories associées
				if ($categories !== null && is_array($categories)) {
					$stmtDel = $this->access->prepare("DELETE FROM series_categories WHERE series_id = :series_id");
					$stmtDel->bindValue(':series_id', $this->id, PDO::PARAM_INT);
					$stmtDel->execute();
					$stmtDel->closeCursor();

					if (!empty($categories)) {
						$stmtIns = $this->access->prepare("INSERT INTO series_categories (series_id, categorie_id) VALUES (:series_id, :categorie_id)");

						foreach ($categories as $categorie_id) {
                            if (is_int($categorie_id) && $categorie_id > 0) { 
							    $stmtIns->bindValue(':series_id', $this->id, PDO::PARAM_INT);
							    $stmtIns->bindValue(':categorie_id', $categorie_id, PDO::PARAM_INT);
							    $stmtIns->execute();
                            }
						}
						$stmtIns->closeCursor();
					}
				}

				$this->access->commit();

			}
			catch (PDOException $e) {
				if ($this->access->inTransaction()) {
					$this->access->rollBack();
				}
				error_log("PDOException dans Serie::modifier pour ID {$this->id}: " . $e->getMessage());
				echo "Une erreur est survenue lors de la modification de la série. Veuillez vérifier les logs pour plus de détails.";

			}
		}
		// Récupère les acteurs associés à une série
		public function getActeurs(){
			$stmt_acteurs = $this->access->prepare("SELECT a.* FROM acteurs a
											  INNER JOIN series_acteurs sa ON a.id = sa.acteur_id
											  WHERE sa.series_id = :serie_id");
			$stmt_acteurs->bindParam(':serie_id', $this->id, PDO::PARAM_INT);
			$stmt_acteurs->execute();
			$acteurs = $stmt_acteurs->fetchAll(PDO::FETCH_OBJ);
			$stmt_acteurs->closeCursor();
            return $acteurs;
		}
	}
?>