<?php
// Classe Connexion permettant d'établir une connexion sécurisée à la base de données
class Connexion {
    public $access;
        // Constructeur qui initialise la connexion à la base de données MySQL
    public function __construct() {
        try {
            $this->access = new PDO('mysql:host=localhost;dbname=monoserie', 'root', '');
            $this->access->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
                // Gestion des erreurs de connexion à la base de données
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}
?>
