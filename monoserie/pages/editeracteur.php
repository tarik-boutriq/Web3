<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }

    require_once '../template.php';
    require_once "../class/ActeurManager.php";
    require_once "../class/Acteur.php"; 

    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $acteur = null;

    if ($id && $id > 0) {
        try {
            $acteurManager = new ActeurManager();
            $acteur = $acteurManager->getActeurParId($id);
        } catch(Exception $e){
             error_log("Erreur lors de la récupération de l'acteur : " . $e->getMessage());
             $acteur = null; 
        }
    }

    if (!$acteur) {
         ob_start();
         echo "<p>Impossible d'éditer un acteur inexistant ou ID invalide.</p></div>";
         $content = ob_get_clean();
         Template::render($content, 'MonoSerie - Erreur');
         exit;
    }

    ob_start();
?>

<div class="add-series-container">
    <h1 class="add-series-title">Éditer l'acteur : <?= htmlspecialchars($acteur->nom) ?></h1>
    <form action="../series/modifieracteur.php" method="post" class="add-series-form">
        <input type="hidden" name="acteur_id" value="<?= htmlspecialchars($acteur->id) ?>">

        <div class="add-series-form-group">
            <label for="nom" class="add-series-label">Nom de l'acteur :</label>
            <input type="text" name="nom" id="nom" class="add-series-input" value="<?= htmlspecialchars($acteur->nom) ?>" required>
        </div>

        <div class="add-series-form-group">
            <label for="photo" class="add-series-label">URL de la photo :</label>
            <input type="url" name="photo" id="photo" class="add-series-input" value="<?= htmlspecialchars($acteur->photo) ?>" required>
        </div>

        <div class="add-series-form-group">
            <label for="role" class="add-series-label">Rôle :</label>
            <input type="text" name="role" id="role" class="add-series-input" value="<?= htmlspecialchars($acteur->role) ?>" required>
        </div>

        <button type="submit" class="add-series-submit-button">Modifier l'acteur</button>
    </form>
</div>

<?php
$content = ob_get_clean();
Template::render($content, 'MonoSerie - Éditer Acteur : ' . htmlspecialchars($acteur->nom));
?>