<?php
$connexion = getConnexionBD();

/* Provenant de la table Quartiers
Fait:       `idQ``codeInsee``typeQ``nomQ``departement``cpCommune``nomCommune`
Restant:    `coords`
Provenant de la table routes:
Fait:       `isQuartierDepart``idQuartierDepart` `idQuartierArrivee``transport`
Restant:
*/

$requete = "SET FOREIGN_KEY_CHECKS=0;";
if ($connexion->query($requete) === TRUE) {
    $message[] = "Clée étrangère desactivé". "<br>";
} else {
    $message[] = "Error: " . $requete . "<br>" . $connexion->error;
}
$requeteTrunc = "SELECT Concat('TRUNCATE TABLE ',table_schema,'.',TABLE_NAME, ';') AS tc
FROM INFORMATION_SCHEMA.TABLES where  table_schema in ('p1805309','p1802879');";
$resultTrunc = $connexion->query($requeteTrunc);

while ($req = mysqli_fetch_assoc($resultTrunc)){
    $connexion->query($req['tc']);
    /*    if ($connexion->query($req['tc']) === TRUE) {
            $message[] = $req['tc']."<br>"." executé". "<br>" ;
        } else {
            $message[] = "Error: " . $requete . "<br>" . $connexion->error;
        }*/
}
$requete = "SET FOREIGN_KEY_CHECKS=1;";
if ($connexion->query($requete) === TRUE) {
    $message[] = "Clée étrangère activé". "<br>";
} else {
    $message[] = "Error: " . $requete . "<br>" . $connexion->error;
}


//insertion des departement TODO: ON en pas besoin donc à enlever
$requete = "INSERT INTO  Departement(codeDep) SELECT DISTINCT Quartiers.departement  FROM dataset.Quartiers;";
//$resultat = mysqli_query($connexion, $requete);

if ($connexion->query($requete) === TRUE) {
    $message[] = $requete."<br>"." executé". "<br>";
} else {
    $message[] = "Error: " . $requete . "<br>" . $connexion->error;
}

//insertion des communes `cpCommune``nomCommune`
$requete = "INSERT INTO  Commune(codePostal, nomC, codeDep) SELECT DISTINCT Quartiers.cpCommune, Quartiers.nomCommune, Quartiers.departement FROM dataset.Quartiers;";
//$resultat = mysqli_query($connexion, $requete);

if ($connexion->query($requete) === TRUE) {
    $message[] = $requete."<br>"." executé". "<br>";
} else {
    $message[] = "Error: " . $requete . "<br>" . $connexion->error;
}

//insertion des idQ, nomQ, codeInsee et typeQ des quartiers
$requete = "INSERT INTO  Quartier(idQ,nomQ,codeInsee,typeQ,codePostal) SELECT Quartiers.idQ, Quartiers.nomQ, Quartiers.codeInsee, Quartiers.typeQ, Quartiers.cpCommune FROM dataset.Quartiers;";
//$resultat = mysqli_query($connexion, $requete);

if ($connexion->query($requete) === TRUE) {
    $message[] = $requete."<br>"." executé". "<br>";
} else {
    $message[] = "Error: " . $requete . "<br>" . $connexion->error;
}

//insertion des isQuartierDepart de route vers estDepart
$requete = "UPDATE Quartier INNER JOIN (SELECT DISTINCT idQuartierDepart, isQuartierDepart FROM dataset.Routes WHERE isQuartierDepart = 1)as routes ON Quartier.idQ = routes.idQuartierDepart SET Quartier.estDepart = routes.isQuartierDepart";
//$resultat = mysqli_query($connexion, $requete);

if ($connexion->query($requete) === TRUE) {
    $message[] = $requete."<br>"." executé". "<br>";
} else {
    $message[] = "Error: " . $requete . "<br>" . $connexion->error;
}

//insertion des idQuartierDepart, idQuartierArrivee, transport des routes
$requete = "INSERT INTO  Route(idQDepart,idQArrive,typeT) SELECT Routes.idQuartierDepart, Routes.idQuartierArrivee, Routes.transport FROM dataset.Routes;";
//$resultat = mysqli_query($connexion, $requete);

if ($connexion->query($requete) === TRUE) {
    $message[] = $requete."<br>"." executé". "<br>";
} else {
    $message[] = "Error: " . $requete . "<br>" . $connexion->error;
}

//insertion des Images
$requete = 'INSERT INTO  Image(nomI, pathToFile)  VALUES ("pionBlack", "img/pion_black.jpg"), ("pionBus", "img/pion_bus.jpg"), ("pionTaxi", "img/pion_taxi.jpg"), ("pionMetro","img/pion_under.jpg");';
if ($connexion->query($requete) === TRUE) {
    $message[] = $requete."<br>"." executé". "<br>";
} else {
    $message[] = "Error: " . $requete . "<br>" . $connexion->error;
}

//insertion dans Se_situe
//$requete = "INSERT INTO  Se_situe(idQ,codePostal) SELECT Quartiers.idQ, Quartiers.cpCommune FROM dataset.Quartiers;";
//$resultat = mysqli_query($connexion, $requete);

//insertion dans UtiliseD
//$requete = "INSERT INTO  UtiliseD(idQ,codePostal) SELECT Quartiers.idQ, Quartiers.cpCommune FROM dataset.Quartiers;";
//$resultat = mysqli_query($connexion, $requete);

//insertion dans Est_dans
//$requete = "INSERT INTO  Est_dans(idDet, idQ) SELECT Quartiers.idDet, Quartiers.idQ FROM dataset.Quartiers;";
//$resultat = mysqli_query($connexion, $requete);