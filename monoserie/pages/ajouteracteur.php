<?php
session_start();
require_once("../config/commandes.php");
require_once("../config/connexion.php");
require_once '../template.php';

$cnx = new Connexion();
// Préparation et exécution de la requête pour récupérer toutes les séries, triées par ordre alphabétique
$stmt = $cnx->access->prepare("SELECT id, nom FROM series ORDER BY nom ASC");
$stmt->execute();
$seriesList = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

ob_start();
?>

<div class="add-series-container">
    <h1 class="add-series-title">Ajouter Acteur(s) à une Série</h1>
    <form action="../series/ajouteacteur.php" method="post" class="add-series-form">
        <div class="add-series-form-group">
            <label for="serie_id" class="add-series-label">Choisir la série :</label>
            <select name="serie_id" id="serie_id" class="add-series-input" required>
                <option value="">Sélectionnez une série</option>
                <?php foreach($seriesList as $serie): ?>
                    <option value="<?= $serie->id ?>"><?= htmlspecialchars($serie->nom) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="actors-container">
            <div class="actor-entry">
                <div class="add-series-form-group">
                    <label for="nom1" class="add-series-label">Nom de l'acteur 1 :</label>
                    <input type="text" name="nom[]" id="nom1" class="add-series-input" required>
                </div>
                <div class="add-series-form-group">
                    <label for="photo1" class="add-series-label">URL de la photo 1 :</label>
                    <input type="url" name="photo[]" id="photo1" class="add-series-input" required>
                </div>
                <div class="add-series-form-group">
                    <label for="role1" class="add-series-label">Rôle 1 :</label>
                    <input type="text" name="role[]" id="role1" class="add-series-input" required>
                </div>
            </div>
        </div>
        <button type="button" id="add-actor-btn" class="add-series-submit-button">Ajouter un autre acteur</button>
        <br><br>
        <button type="submit" class="add-series-submit-button">Valider</button>
    </form>
</div>

<script>
    let actorCounter = 1;
    document.getElementById('add-actor-btn').addEventListener('click', function(e) {
        e.preventDefault();
        actorCounter++;
        const container = document.getElementById('actors-container');
        const newEntry = document.createElement('div');
        newEntry.className = 'actor-entry';
        newEntry.innerHTML = `
            <div class="add-series-form-group">
                <label for="nom${actorCounter}" class="add-series-label">Nom de l'acteur ${actorCounter} :</label>
                <input type="text" name="nom[]" id="nom${actorCounter}" class="add-series-input" required>
            </div>
            <div class="add-series-form-group">
                <label for="photo${actorCounter}" class="add-series-label">URL de la photo ${actorCounter} :</label>
                <input type="url" name="photo[]" id="photo${actorCounter}" class="add-series-input" required>
            </div>
            <div class="add-series-form-group">
                <label for="role${actorCounter}" class="add-series-label">Rôle ${actorCounter} :</label>
                <input type="text" name="role[]" id="role${actorCounter}" class="add-series-input" required>
            </div>
        `;
        container.appendChild(newEntry);
    });
</script>

<?php
$content = ob_get_clean();
Template::render($content, 'MonoSerie - Ajouter Acteur(s)');
?>
