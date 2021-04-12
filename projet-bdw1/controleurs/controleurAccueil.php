<?php
$connexion = getConnexionBD(); // connexion à la BD

if(isset($_POST['boutonMiseAJourGit'])) {
    // formulaire soumis
    include 'git.php';
    $message[] = "git pull executé";
}
if(isset($_POST['boutonIntegration'])) {
    // formulaire soumis
    include 'intégrationDonnée.php';
    $message[] = "Donnée intégrée";
}
if(isset($_POST['resetPartie'])) {
    // formulaire soumis
    unset($_SESSION['game']);
    $message[] = "Partie réinitialisé";
}
if(isset($_POST['boutonDeconnexion'])) {
    // formulaire soumis
    session_destroy();
    header('Location: index.php');
    $message[] = "Déconnectée";
}
if(isset($_SESSION['idJ'])){
    $requete = "SELECT nomJ FROM Joueuse WHERE idJ = ".$_SESSION['idJ'].";"; //recuperation nom joueur
    $verification = mysqli_query($connexion, $requete);
    if ($verification == FALSE) {
        $message[] = "Erreur pendant la requete";
    } else {
        $nomJ = mysqli_fetch_row($verification);
    }
}
if(isset($_POST['boutonJouer'])) { // formulaire soumis
    $erreur = false;
    unset($_SESSION['game']);//on vide la variable pcq si on a eu une erreur pendant la config elle est pas vide
    //On regarde si les données sont correctes
    if(!isset($_SESSION['idJ'])) {
        $nomJoueur = mysqli_real_escape_string($connexion, $_POST['nomJoueuse']); // recuperation de la valeur saisie
        $requete = "SELECT * FROM Joueuse WHERE nomJ = \"$nomJoueur\" ;";
        $verification = mysqli_query($connexion, $requete);

        if ($verification == FALSE || mysqli_num_rows($verification) == 0) { // pas de joueur avec ce nom, insertion
            $requete = "INSERT INTO Joueuse(nomJ) VALUES ('" . $nomJoueur . "')";
            $insertion = mysqli_query($connexion, $requete);
            if ($insertion == TRUE) {
                $message[] = "Le joueur $nomJoueur a bien été ajouté !";
            } else {
                $message[] = "Erreur lors de l'insertion du nom du joueur $nomJoueur.";
                $erreur = true;
            }
        } else {
            $message[] = "Le nom de joueur: $nomJoueur existe déjà !";
        }

        $requete = "SELECT idJ FROM Joueuse WHERE nomJ = \"$nomJoueur\";"; //recuperation nom joueur
        $verification = mysqli_query($connexion, $requete);
        if ($verification == FALSE) {
            $message[] = "Erreur pendant la requete";
            $erreur = true;
        } else {
            $idJ = mysqli_fetch_assoc($verification);
            $_SESSION['idJ'] = $idJ['idJ'];
        }
    }
    $_SESSION['game']['nombreJoueur']= ($_POST['nbDet'] + 2);
    //creation de la partie dans la base de donnée
    $mysqltime = "'".date('Y-m-d H:i:s')."'";
    $nbDet = mysqli_escape_string($connexion,$_POST['nbDet']);
    $requete = "INSERT INTO Partie(dateD, nbDetective, idJ) VALUES (" . $mysqltime . "," . $nbDet . "," . $_SESSION['idJ'] . ")";
    $verification = mysqli_query($connexion, $requete);
    if ($verification == TRUE) { //Partie inséré on récupère l'idP
        $requete = "SELECT idP FROM Partie WHERE dateD = $mysqltime";
        $resultat = mysqli_query($connexion, $requete);
        $idP = mysqli_fetch_assoc($resultat);
        $_SESSION['game']['idP'] = $idP['idP'];
        if ($resultat == TRUE) {
            $message[] = "La partie a bien été ajouté !";
        } else {
            $message[] = "Erreur lors de l'insertion de la partie";
            $erreur = true;
        }
    }

    $requete = "SELECT idQ FROM Quartier WHERE estDepart = 1;";
    $resultat = mysqli_query($connexion,$requete);
    $quartierDepart = mysqli_fetch_all($resultat);
    $utilise[]= "";
    for ($i = 0; $i < $_SESSION['game']['nombreJoueur']; $i++) {
        do {
            $pos = array_rand($quartierDepart);
        } while ((isset($utilise) && in_array($pos, $utilise)));
        $utilise[] = $pos;
    }
    for ($i = 0; $i < $_SESSION['game']['nombreJoueur']; $i++) {

        $_SESSION['game']['position'][$i] = intval($quartierDepart[$utilise[$i+1]][0]);
    }
    //on initialise le nombre de tour
    $_SESSION['game']['numTour']=0;

    if($erreur){
        $_SESSION['game']['status'] = 0;
    }
   else $_SESSION['game']['status'] = 1;
}
deconnectBD($connexion);
