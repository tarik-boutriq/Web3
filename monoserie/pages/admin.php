<?php
	session_start();
    require("../class/SerieManager.php");
    require_once '../template.php'; 

	$serieManager = new SerieManager();
	$mesSeries = $serieManager->getTous();// Récupération de toutes les séries depuis la base de données
	
    ob_start(); 
?>

<main style="margin:40px;">
    <?php
    foreach ($mesSeries as $serie) :
    ?>
        <div class="card mb-3" >
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?= $serie->image ?>" class="img-fluid rounded-start" alt="Affiche de la série">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?= $serie->nom ?></h5>
                        <p class="card-text"><?= substr($serie->description, 0, 100); ?></p>
                        <p class="card-text"><strong>Durée :</strong> <?= $serie->dure ?> Saison</p>
                        <p class="card-text"><strong>Nombre d'épisodes :</strong> <?= $serie->episode ?? '' ?> episodes</p>

                        <div class="buttons-box">
                            <a href="./voir.php?id=<?= $serie->id ?>" class="btn btn-primary">Voir</a>
                            <a href="./editerserie.php?id=<?= $serie->id ?>" class="btn btn-edit">Modifier</a>
                            <a href="../series/supprimerserie.php?id=<?= $serie->id ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette série ?');">Supprimer</a>
                            <?php if (isset($_SESSION['admin_id'])) : ?>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
    endforeach;
    ?>
</main>

<?php
$content = ob_get_clean();
Template::render($content, 'MonoSerie - Accueil'); 
?>
