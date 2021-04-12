<?php
$nomSite = "Scotland Yard";
$baseline = "Trouvez MisterX !";

// connexion à la BD, retourne un lien de connexion
function getConnexionBD() {
	$connexion = mysqli_connect(SERVEUR, UTILISATRICE, MOTDEPASSE, BDD);
	if (mysqli_connect_errno()) {
	    printf("Échec de la connexion : %s\n", mysqli_connect_error());
	    exit();
	}
	return $connexion;
}

// déconnexion de la BD
function deconnectBD($connexion) {
	mysqli_close($connexion);
}

function convToEmoji($transport){
	switch ($transport){
		case "Bateau":
			$emo="\xF0\x9F\x9A\xA2";
			break;
		case "Bus":
			$emo="\xF0\x9F\x9A\x8C";
			break;
		case "Métro/tramway":
			$emo="\xF0\x9F\x9A\x87/\xF0\x9F\x9A\x8A";
			break;
		case "Taxi":
			$emo="\xF0\x9F\x9A\x95";
			break;
		default:
			$emo="bullshit";
			break;
	}
return $emo;
}

