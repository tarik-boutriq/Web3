<?php
require_once '../class/SerieManager.php';
require_once("../config/commandes.php");
// Script permettant de rechercher une série par son nom et de rediriger vers sa page de visualisation si elle existe
if (isset($_GET['s']) && !empty($_GET['s'])) {
    $nomSerie = htmlspecialchars($_GET['s']);

    $serieManager = new SerieManager();
    $idSerie = $serieManager->getSerieParNom($nomSerie);
    
    if ($idSerie) {
        header("Location: /Web3/monoserie/pages/voir.php?id=" . intval($idSerie));
        exit();
    } else {
        echo "<p>Oups ! Il semble qu’on n’arrive pas à trouver de résultats pour votre recherche '<b>" . htmlspecialchars($nomSerie) . "</b>'</p>";
    }
}
?>