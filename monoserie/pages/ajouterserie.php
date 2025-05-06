<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
     header("Location: login.php"); 
     exit();
}
require_once '../class/CategorieManager.php'; 
require_once '../template.php';

$categorieManager = new CategorieManager();
$categories = $categorieManager->getTous();



$uniqueId = uniqid(); 
ob_start();
?>


    <div class="add-series-container">

        <h1 class="add-series-title">Ajouter une Nouvelle Série</h1>
        <form action="../series/ajouterserie.php" method="post" class="add-series-form">

            <div class="add-series-form-group">
                <label for="nom" class="add-series-label">Nom de la série :</label>
                <input type="text" name="nom" id="nom" class="add-series-input" required>
            </div>
            <div class="add-series-form-group">
                <label for="image" class="add-series-label">URL de l'image :</label>
                <input type="url" name="image" id="image" class="add-series-input" required>
            </div>
            <div class="add-series-form-group">
                <label for="video" class="add-series-label">URL de la Bande Annonce :</label>
                <input type="url" name="video" id="video" class="add-series-input" required>
            </div>
            <div class="add-series-form-group">
                <label for="desc" class="add-series-label">Description :</label>
                <textarea name="desc" id="desc" class="add-series-textarea" rows="5" required></textarea>
            </div>
            <div class="add-series-form-group">
                <label for="dure" class="add-series-label">Nombre de saisons :</label>
                <input type="number" name="dure" id="dure" class="add-series-input" min="1" required>
            </div>
            <div class="add-series-form-group">
                <label for="episode" class="add-series-label">Nombre d'épisodes :</label>
                <input type="number" name="episode" id="episode" class="add-series-input" min="1" required>
            </div>
            <div class="add-series-form-group">
                <label class="add-series-label">Catégories :</label><br>
                 <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <input type="checkbox" name="categories[]" value="<?= $category->id ?>" id="cat<?= $category->id ?>">
                        <label for="cat<?= $category->id ?>"><?= htmlspecialchars($category->nom) ?></label><br>
                    <?php endforeach; ?>
                 <?php else: ?>
                     <p class="text-muted small">Aucune catégorie à sélectionner.</p>
                 <?php endif; ?>
            </div>
            <button type="submit" class="add-series-submit-button">Ajouter la série</button>
        </form>

    </div>


<?php
$content = ob_get_clean();
Template::render($content, 'MonoSerie - Ajouter une série');
?>