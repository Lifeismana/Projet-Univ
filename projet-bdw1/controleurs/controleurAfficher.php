<?php 
$connexion = getConnexionBD(); // connexion à la BD

// recuperation données tableau
$requete = "SELECT idQ, nomQ, nomC FROM Quartier NATURAL JOIN Commune ORDER BY idQ";
$quartier = mysqli_query($connexion, $requete);
if($quartier == FALSE) {
	$message[] = "Aucune donnée n'a été trouvée dans la base de données !";
}

// recuperation données tableau
$requete = "SELECT idQDepart,COUNT(idQArrive) as cQA, GROUP_CONCAT(idQArrive) AS idQA, GROUP_CONCAT(typeT) AS ctT FROM Route GROUP BY idQDepart ORDER BY idQDepart, idQArrive;";
$route = mysqli_query($connexion, $requete);
if($route == FALSE) {
	$message[] = "Aucune donnée n'a été trouvée dans la base de données !";
}
deconnectBD($connexion);

