<?php
    session_start();
    require("./class/SerieManager.php");

    require_once './template.php'; 

	$serieManager = new SerieManager();
	$mesSeries = $serieManager->getTous();

    ob_start(); 
?>
  <div class="accueil-container">
    <h1>Bienvenue sur MonoSerie</h1>
    <p>
      Découvrez notre sélection exclusive de séries, où chaque titre est choisi avec soin pour son intrigue captivante, sa qualité visuelle et son originalité. Parcourez nos actualités, critiques et recommandations afin de dénicher votre prochaine obsession. Laissez-vous emporter par un univers riche en émotions et suspendu entre réalité et fiction avec MonoSerie.
    </p>
  </div>
  <h2 class="categories__title"> Toutes les séries </h2>
  <br>
  <section class="carousel">
    <div class="main-carousel__container">
      <?php foreach ($mesSeries as $serie) : ?>
        <div class="main-carousel-item">
          <a href="./pages/voir.php?id=<?= $serie->id ?>" style="display: block;">
            <img 
              src="<?= $serie->image ?>" 
              alt="Affiche de la série" 
              class="carousel-item__img"
              data-serie-nom="<?= $serie->nom ?>"
            />
            <div class="carousel-item__details">
              <?= $serie->nom ?>
              <br>            
              <?= $serie->dure ?> Saison</p>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

<?php
    $content = ob_get_clean();
    Template::render($content, 'MonoSerie - Accueil'); 
?>
