<?php
	require_once("../class/SerieManager.php");
	require_once("../class/Serie.php");
	// Script permettant de supprimer une série en fonction de son ID, puis de rediriger vers la page principale
	$id = isset($_GET['id']) ? intval($_GET['id']) : null;

	$serieManager = new SerieManager();
	$serie = ($id) ? $serieManager->getSerieParId($id) : null;
	// Vérification que la série existe avant de tenter la suppression
	if($serie){
		try {
			$serie->supprimer();
			header("Location: ../");
			exit();
		} catch (Exception $e) {
			$e->getMessage();
		}
	}
	
?>

