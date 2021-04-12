<?php
$connexion = getConnexionBD(); // connexion à la BD

if(isset($_POST['boutonValider'])) { // formulaire soumis

	$nomSerie = mysqli_real_escape_string($connexion, $_POST['nomSerie']); // recuperation de la valeur saisie
	$requete = "SELECT * FROM Series WHERE nomSerie = '". $nomSerie . "'";
	$verification = mysqli_query($connexion, $requete);

	if($verification == FALSE || mysqli_num_rows($verification) == 0) { // pas de série avec ce nom, insertion
		$requete = "INSERT INTO Series VALUES ('". $nomSerie . "')";
		$insertion = mysqli_query($connexion, $requete);
		if($insertion == TRUE) {
			$message[] = "La série $nomSerie a bien été ajoutée !";
		}
		else {
			$message[] = "Erreur lors de l'insertion de la série $nomSerie.";

		}
	}
	else {
		$message[] = "Une série existe déjà avec ce nom ($nomSerie).";
	}
}
deconnectBD($connexion);

