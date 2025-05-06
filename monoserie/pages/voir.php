<?php
session_start();
require_once("../class/SerieManager.php");
require_once("../class/Serie.php");

require_once '../template.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$serie = null;

if ($id && $id > 0) {
    $serieManager = new SerieManager();
    $serie = $serieManager->getSerieParId($id);
}

if (!$serie) {
    ob_start();
    echo "<div class='container'><h1>Série non trouvée</h1></div>";
    $content = ob_get_clean();
    Template::render($content, 'MonoSerie - Erreur');
    exit;
}

$acteurs = $serie->getActeurs();


ob_start();
?>

<style>
    /* Styles originaux de la page */
    .voir-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
        border-radius: 12px;
        text-align: left;
        display: flex;
        align-items: flex-start;
        gap: 25px;
        color: #333;
    }

    .voir-affiche img {
        width: 250px;
        height: auto;
        border-radius: 15px;
        display: block;
    }

    .voir-details {
        font-size: 16px;
        line-height: 1.6;
        background-color: rgba(255, 255, 255, 0.75);
        border-radius: 10px;
        padding: 20px;
        flex: 1;
    }
    .voir-details h1 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #111;
    }
    .voir-details b {
        color: #000;
    }

    /* Conteneur pour la vidéo intégrée */
    .video-embed-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        padding-top: 56.25%; 
        margin-top: 20px;
        margin-bottom: 20px;
        background-color: #000;
        border-radius: 5px;
    }
    .video-embed-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .voir-actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }

    .acteurs-section {
         max-width: 900px;
         margin: 30px auto;
         padding: 20px;
         background-color: rgba(0, 0, 0, 0.3);
         border-radius: 12px;
    }
     .acteurs-section h2 {
         color: #fff;
         text-align: center;
         margin-bottom: 20px;
     }

    .acteurs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
    }

    .acteur-carte {
        background-color: rgba(255, 255, 255, 0.75);
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        color: #333;
    }
    .acteur-carte img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 3px solid #eee;
    }
    .acteur-carte h3 {
        font-size: 1.1em;
        margin-bottom: 5px;
        color: #111;
    }
    .acteur-carte p {
        font-size: 0.9em;
        margin-bottom: 15px;
    }
    .acteur-actions {
         display: flex;
         justify-content: center;
         gap: 8px;
     }
     .acteur-actions .btn-edit, .acteur-actions .btn-delete {
         padding: 3px 8px;
         font-size: 0.8em;
     }

     .aucun-acteur {
         color: white;
         text-align: center;
         padding: 20px;
     }
</style>

<div class="voir-container">
    <div class="voir-affiche">
        <img src="<?= htmlspecialchars($serie->image ?? '') ?>" alt="Affiche de <?= htmlspecialchars($serie->nom ?? '') ?>" />
    </div>

    <div class="voir-details">
        <h1><?= htmlspecialchars($serie->nom ?? '') ?></h1>
        <b>Description :</b> <br><?= nl2br(htmlspecialchars($serie->description ?? '')) ?>
        <br><br>
        <b>Nombre de saisons :</b> <?= htmlspecialchars($serie->dure ?? '?') ?>
        <br>
        <b>Nombre d'épisodes :</b> <?= htmlspecialchars($serie->episode ?? '?') ?>
        <br>
        <b>Catégories :</b> <?= htmlspecialchars($serie->categories ?? 'Non spécifiées') ?>
        <br><br>

        <?php
        if (!empty($serie->video)):
            $videoUrl = $serie->video;
            $embedUrl = '';

            if (strpos($videoUrl, 'youtube.com/watch') !== false) {
                parse_str(parse_url($videoUrl, PHP_URL_QUERY), $queryParams);
                if (isset($queryParams['v']) && !empty($queryParams['v'])) {
                    $videoId = $queryParams['v'];
                    $embedUrl = "https://www.youtube.com/embed/" . $videoId;
                }
            }
            elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                $path = parse_url($videoUrl, PHP_URL_PATH);
                $videoId = ltrim($path, '/');
                if (!empty($videoId)) {
                    $embedUrl = "https://www.youtube.com/embed/" . $videoId;
                }
            }

            if (!empty($embedUrl)):
        ?>
                <div class="video-embed-container">
                    <iframe
                        src="<?= htmlspecialchars($embedUrl) ?>"
                        title="Bande annonce de <?= htmlspecialchars($serie->nom ?? '') ?>"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                    </iframe>
                </div>
        <?php
            else:
                echo '<span class="text-muted">Format de bande annonce non intégrable.</span>';
            endif;
        else:
            echo '<span class="text-muted">Bande annonce non disponible.</span>';
        endif;
        ?>

        <?php if (isset($_SESSION['admin_id'])) : ?>
            <div class="voir-actions">
                <a href="./editerserie.php?id=<?= $serie->id ?>" class="btn btn-edit">Modifier Série</a>
                <a href="../series/supprimerserie.php?id=<?= $serie->id ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette série ?');">Supprimer Série</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="acteurs-section">
    <h2>Acteurs</h2>
    <?php if (!empty($acteurs)): ?>
        <div class="acteurs-grid">
            <?php foreach ($acteurs as $acteur): ?>
                <div class="acteur-carte">
                    <img src="<?= htmlspecialchars($acteur->photo ?? '') ?>" alt="Photo de <?= htmlspecialchars($acteur->nom) ?>">
                    <h3><?= htmlspecialchars($acteur->nom) ?></h3>
                    <p><b>Rôle :</b> <?= htmlspecialchars($acteur->role) ?></p>

                    <?php if (isset($_SESSION['admin_id'])) : ?>
                        <div class="acteur-actions">
                            <a href="./editeracteur.php?id=<?= $acteur->id ?>" class="btn btn-edit">Modifier</a>
                            <a href="../series/supprimeracteur.php?id=<?= $acteur->id ?>" class="btn btn-delete" onclick="return confirm('Supprimer cet acteur ?');">Supprimer</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="aucun-acteur">
            <p>Aucun acteur renseigné pour cette série.</p>
            <?php if (isset($_SESSION['admin_id'])) : ?>
                <br>
                <a href="./ajouteracteur.php?serie_id=<?= $serie->id ?>" class="btn btn-primary">Ajouter Acteur(s)</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
Template::render($content, 'MonoSerie - ' . htmlspecialchars($serie->nom ?? 'Voir la série'));
?>
