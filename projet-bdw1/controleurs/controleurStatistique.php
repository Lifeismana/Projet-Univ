<?php
$connexion = getConnexionBD(); // connexion à la BD

//calcul du nombre de quartier
$requete = "SELECT COUNT(idQ) AS countidQ FROM Quartier";
$resultat=mysqli_query($connexion, $requete);
$resCountidQ = mysqli_fetch_array($resultat);

//calcul du nombre de route
$requete = "SELECT COUNT(idR) AS countidR FROM Route";
$resultat= mysqli_query($connexion, $requete);
$resCountidR = mysqli_fetch_array($resultat);

//calcul du nombre de commune
$requete = "SELECT COUNT(codePostal) AS countCom FROM Commune";
$resultat= mysqli_query($connexion, $requete);
$resCountCom = mysqli_fetch_array($resultat);

//calcul du nombre de quartier de départ
$requete = "SELECT COUNT(idQDepart) AS countRD  FROM Route";
$resultat= mysqli_query($connexion, $requete);
$resCountDepart= mysqli_fetch_array($resultat);

//calcul du nombre de parties gagnées/perdues par une joueuse
$requete = "SELECT idJ, nomJ, COUNT(idJVictoire) AS countVictoire FROM Partie NATURAL JOIN Joueuse ORDER BY countVictoire;";
$resCountVictoire = mysqli_query ($connexion, $requete);

//top 3 des meilleurs detectives
$requete = "SELECT nomJ, COUNT(idJVictoire) AS countVictoire FROM Partie NATURAL JOIN Joueuse ORDER BY countVictoire LIMIT 3;";
$resultat = mysqli_query ($connexion, $requete);
//$resCountTop3 = mysqli_fetch_all ($resultat);

deconnectBD($connexion);

