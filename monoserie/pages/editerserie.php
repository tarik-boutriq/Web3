<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }

    require_once '../template.php';
    require_once "../class/SerieManager.php";
    require_once "../class/CategorieManager.php";
    require_once "../class/Serie.php";

    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $serie = null;
    $serieCategoriesIds = [];
    // Récupération des informations de la série si un ID valide est fourni
    if ($id) {
        $serieManager = new SerieManager();
        $serie = $serieManager->getSerieParId($id);
        // Si la série existe et a des catégories associées, récupération de leurs ID
        if ($serie && !empty($serie->categories)) {
             try {
                 $conn = new Connexion();
                 $stmt = $conn->access->prepare("SELECT categorie_id FROM series_categories WHERE series_id = :id");
                 $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                 $stmt->execute();
                 $serieCategoriesIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
                 $stmt->closeCursor();
             } catch (PDOException $e) {
                 error_log("Erreur PDO Editer (get categories IDs): " . $e->getMessage());
             }
        }
    }
    // Vérification si la série existe, sinon affichage d'un message d'erreur
    if (!$serie) {
         ob_start();
         echo "<h1>Série non trouvée</h1><p>Impossible d'éditer une série inexistante.</p></div>";
         $content = ob_get_clean();
         Template::render($content, 'MonoSerie - Erreur');
         exit;
    }
    // Récupération de toutes les catégories disponibles
    $categorieManager = new CategorieManager();
    $toutesCategories = $categorieManager->getTous();

    ob_start();
?>


<div class="add-series-container">
    <h1 class="add-series-title">Éditer la série : <?= htmlspecialchars($serie->nom) ?></h1>
    <form action="../series/modifierserie.php" method="post" class="add-series-form">
        <input type="hidden" name="serie_id" value="<?= $serie->id ?>">

        <div class="add-series-form-group">
            <label for="nom" class="add-series-label">Nom de la série :</label>
            <input type="text" name="nom" id="nom" class="add-series-input" value="<?= htmlspecialchars($serie->nom) ?>" required>
        </div>

        <div class="add-series-form-group">
            <label for="image" class="add-series-label">URL de l'image :</label>
            <input type="url" name="image" id="image" class="add-series-input" value="<?= htmlspecialchars($serie->image) ?>" required>
        </div>

        <div class="add-series-form-group">
            <label for="video" class="add-series-label">URL de la Bande Annonce :</label>
            <input type="url" name="video" id="video" class="add-series-input" value="<?= htmlspecialchars($serie->video) ?>" required>
        </div>
        <div class="add-series-form-group">
            <label for="desc" class="add-series-label">Description :</label>
            <textarea name="desc" id="desc" class="add-series-textarea" rows="5" required><?= htmlspecialchars($serie->description) ?></textarea>
        </div>

        <div class="add-series-form-group">
            <label for="dure" class="add-series-label">Nombre de saisons :</label>
            <input type="number" name="dure" id="dure" class="add-series-input" min="1" value="<?= htmlspecialchars($serie->dure) ?>" required>
        </div>
        <div class="add-series-form-group">
            <label for="episode" class="add-series-label">Nombre d'épisodes :</label>
            <input type="number" name="episode" id="episode" class="add-series-input" min="1" value="<?= htmlspecialchars($serie->episode) ?>" required>
        </div>

        <div class="add-series-form-group">
            <label class="add-series-label">Catégories :</label><br>
            <?php foreach ($toutesCategories as $category): ?>
                <?php
                    $isChecked = in_array($category->id, $serieCategoriesIds);
                ?>
                <input type="checkbox" name="categories[]" value="<?= $category->id ?>" id="cat<?= $category->id ?>" <?= $isChecked ? 'checked' : '' ?>>
                <label for="cat<?= $category->id ?>"><?= htmlspecialchars($category->nom) ?></label><br>
            <?php endforeach; ?>
             <?php if (empty($toutesCategories)): ?>
                 <p class="text-muted small">Aucune catégorie disponible.</p>
             <?php endif; ?>
        </div>

        <button type="submit" class="add-series-submit-button">Modifier la série</button>
    </form>
</div>


<?php
$content = ob_get_clean();
Template::render($content, 'MonoSerie - Éditer : ' . htmlspecialchars($serie->nom));
?>