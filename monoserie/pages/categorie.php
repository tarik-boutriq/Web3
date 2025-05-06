<?php
session_start();
require_once '../template.php';
require_once '../class/SerieManager.php';
require_once '../class/CategorieManager.php';
// Initialisation des gestionnaires pour récupérer les séries et les catégories
$categorieManager = new CategorieManager();
$categories = $categorieManager->getTous();

$serieManager = new SerieManager();
$mesSeries = $serieManager->getTous();
// Organisation des séries par catégorie pour faciliter l'affichage et le filtrage
$seriesParCategorie = [];
if (!empty($mesSeries)) {
    foreach ($mesSeries as $serie) {
        if (!empty($serie->categories)) {
             $serieCategories = explode(', ', $serie->categories);
             foreach ($serieCategories as $cat) {
                 $catTrimmed = trim($cat);
                 if (!empty($catTrimmed)) {
                     $seriesParCategorie[$catTrimmed][] = $serie;
                 }
             }
        }
    }
}
// Filtrage des catégories actives, c'est-à-dire celles contenant des séries
$activeCategories = [];
foreach ($categories as $category) {
    if (!empty($seriesParCategorie[htmlspecialchars($category->nom)])) {
        $activeCategories[] = $category;
    }
}


ob_start();
?>
<style>
  .categories-nav {
    margin: 30px 0;
  }
  .categories-nav ul {
    list-style: none;
    margin: 0 auto;  
    display: flex;
    justify-content: center; 
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  .categories-nav li {
    margin-right: 15px;
    flex-shrink: 0;
  }
  .categories-nav a {
    text-decoration: none;
    color: #fff;
    font-size: 1rem;
    padding: 5px 10px;
    transition: color 0.3s ease;
    position: relative;
    cursor: pointer;
  }
  .categories-nav a.active {
    color: #f39c12;
    font-weight: bold;
  }
</style>
<main>
  <nav class="categories-nav">
    <ul>
      <li>
        <a href="#" data-category="" class="active">Tous</a>
      </li>
      <?php foreach ($categories as $category) : ?>
        <?php if (empty($seriesParCategorie[$category->nom])) continue; // ignorer si aucune série ?>
        <li>
          <a href="#" data-category="<?= htmlspecialchars($category->nom) ?>">
            <?= htmlspecialchars($category->nom) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <?php foreach ($categories as $category) : ?>
    <?php if (empty($seriesParCategorie[$category->nom])) continue; ?>
    <div class="category-section" data-category="<?= htmlspecialchars($category->nom) ?>">
      <h2 class="categories__title"><?= htmlspecialchars($category->nom) ?></h2>
      <br>
      <section class="carousel">
        <div class="carousel__container">
          <?php foreach ($seriesParCategorie[$category->nom] as $serie) : ?>
            <div class="carousel-item">
              <a href="./voir.php?id=<?= $serie->id ?>" style="display: block;">
                <img 
                  src="<?= $serie->image ?>" 
                  alt="Affiche de la série" 
                  class="carousel-item__img"
                  data-serie-nom="<?= htmlspecialchars($serie->nom) ?>"
                />
                <div class="carousel-item__details">
                  <?= htmlspecialchars($serie->nom) ?>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
      <br>
    </div>
  <?php endforeach; ?>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function(){
    const navLinks = document.querySelectorAll('.categories-nav a');
    const sections = document.querySelectorAll('.category-section');

    navLinks.forEach(link => {
      link.addEventListener('click', function(e){
        e.preventDefault();
        const selectedCategory = this.getAttribute('data-category');

        navLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');

        sections.forEach(section => {
          if (selectedCategory === '' || section.getAttribute('data-category') === selectedCategory) {
            section.style.display = '';
          } else {
            section.style.display = 'none';
          }
        });
      });
    });
  });
</script>


<?php
$content = ob_get_clean();
Template::render($content, 'MonoSerie - Catégories');
?>