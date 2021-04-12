<?php
$connexion = getConnexionBD();
if(isset($_SESSION['game']['status']) && $_SESSION['game']['status'] == 1 && isset($_POST['Move'])){
    //On déplace misterX sa position :
    $requete = "SELECT idQArrive, typeT, idR FROM Route WHERE idQDepart = ".$_SESSION['game']['position'][0].";";
    $resultat = mysqli_query( $connexion,$requete);
    $resDepart = mysqli_fetch_all($resultat);

    $size=count($resDepart);
    for ($i=0;$i<$size;$i++){
        $quartierUtilise =array_search($resDepart[$i][0],$_SESSION['game']['position']);
        if($quartierUtilise!=false) array_splice($resDepart,$quartierUtilise,1);
    }
    if(!empty($resDepart)) {

        $futurPos = array_rand($resDepart);
        //On stocke le tour dans la base de donnée
        $requete = "INSERT INTO TourMisterX(idP, idR, idQArrive, typeT, idQDepart,numTour) VALUES (" . $_SESSION['game']['idP'] . "," . $resDepart[$futurPos][2] . "," . $resDepart[$futurPos][0] . ",'" . $resDepart[$futurPos][1] . "'," . $_SESSION['game']['position'][0] .",".$_SESSION['game']['numTour']. ")";
        $verification = mysqli_query($connexion, $requete);
        if ($verification == TRUE) {
            $message[] = "Le tour a bien été ajouté !";
        } else {
            $message[] = "Erreur lors de l'insertion du tour.";
        }
        $_SESSION['game']['position'][0] = intval($resDepart[$futurPos][0]);

        //on déplace le joueur
        $futurPos = mysqli_escape_string($connexion, $_POST['champRoute']);
        $requete = "SELECT idQArrive FROM Route WHERE idQDepart = " . $_SESSION['game']['position'][1] . " AND idQArrive = " . $futurPos . "  ;";
        $routeJoueuse = mysqli_query($connexion, $requete);
        if ($routeJoueuse == FALSE) {
            $message[] = "Nonon pas de ça par ici, la route n'existe pas"; //TODO:demander de sélectionner une nouvelle destination et arreter le process du tour
        } else {
            $idQA = $routeJoueuse->fetch_assoc();
            $_SESSION['game']['position'][1] = intval($idQA['idQArrive']);
        }

        //on déplace les bots
        for ($i = 2; $i < $_SESSION['game']['nombreJoueur']; $i++) {
            $requete = "SELECT idQArrive, typeT, idR FROM Route WHERE idQDepart = " . $_SESSION['game']['position'][$i] . ";";
            $resultat = mysqli_query($connexion, $requete);
            $resDepart = mysqli_fetch_all($resultat);
            if (in_array($_SESSION['game']['position'][0], $resDepart)) { //si il est à côté de misterX il va dessus
                $futurPos = $_SESSION['game']['position'][0];
            } else {
                do {
                    $futurPos = array_rand($resDepart);
                } while (in_array($futurPos, $_SESSION['game']['position']));
            }
            $_SESSION['game']['position'][$i] = intval($resDepart[$futurPos][0]);
        }

        //Est-ce qu'on a gagné ?
        if (in_array($_SESSION['game']['position'][0], array_slice($_SESSION['game']['position'], 1, null, true))) {

        }
        //On regarde si on est au 20ieme tour (19 ;) de 0 à 19)
            if ($_SESSION['game']['numTour'] >= 19) {
                $_SESSION['game']['status'] = 2;
                $message[] = "Partie terminée, Vous avez perdu";
            }
        $_SESSION['game']['numTour']++;
    }
    //il est entouré, on a gagné
    else {
        $_SESSION['game']['status'] = 3; //$_SESSION['game']['status'] NULL non initialisé, 0 erreur pendant la configuration , 1 initialisé, 2 gagné par misterX, 3 gagné par le joueur
        $message[] = "Partie terminée, Vous avez gagné";
        //TODO:ça marche ?
        $requete = "UPDATE Partie SET idJVictoire=idJ WHERE idP=" . $_SESSION['game']['idP'] . ";";
        $verification = mysqli_query($connexion, $requete);
        if ($verification == TRUE) {
            $message[] = "Le tour a bien été ajouté !";
        } else {
            $message[] = "Erreur lors de l'insertion du tour.";
            $message[] = $requete;
        }
    }
}

//Affichage des routes disponibles pour le joueur
if (isset($_SESSION['game'])&&$_SESSION['game']['status'] == 1) {

    $requete = "SELECT nomQ, idQArrive, typeT FROM Route INNER JOIN Quartier ON idQArrive = idQ WHERE idQDepart = " . $_SESSION['game']['position'][1] . ";";
    $routeJoueuse = mysqli_query($connexion, $requete);
    if ($routeJoueuse == FALSE) {
        $message[] = "Erreur pendant la requete pour trouver les routes disponible pour le joueur";
    }
}
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