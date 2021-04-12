<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title><?= $nomSite ?></title>
    <link href="css/style.css" rel="stylesheet" media="all" type="text/css">
    <link rel="shortcut icon" type="image/x-icon" href="img/sheep.png" />
</head>
<body>
<?php include('static/header.php'); ?>
<div id="divCentral">
    <?php include('static/menu.php'); ?>
    <main>
        <p id="paragCrit">Pour jouer il faut être joueur</p>
        <div id="logMessage"><?php
            if(isset($message)) {
                foreach ($message as $msg)
                    echo $msg . "<br>";
            }
            ?>
        </div>
        <?php
        if (!isset($_SESSION['game']['status'])||$_SESSION['game']['status']==0){
            if (isset($_SESSION['game']['status'])&& $_SESSION['game']['status']==0)echo "Une erreur a eu lieu lors de la configuration de la partie veuillez réessayer";
            ?>
            <form method="post" action="" id="deco">
            </form>
            <form   method="post" action="" id="config">
                <?php if (isset($_SESSION['idJ'])){
                echo "Vous êtes connecté en tant que ".$nomJ[0]."<br><br>";?>
                    <input type="submit" form="deco" name="boutonDeconnexion" value="Se déconnecter"/><br><br>
                <?php }else{ ?>
                <label>
                    Nom de joueur.se : <br>
                    <input type="text" name="nomJoueuse" required   /><br>
                </label>
                <?php } ?>
                <label >
                    Nombre de détective :<br>
                    <input type="radio" form="config" name="nbDet" value="3" required> 3<br>
                    <input type="radio" form="config" name="nbDet" value="4" required> 4<br>
                    <input type="radio" form="config" name="nbDet" value="5" required> 5<br>
                </label>
                <input type="submit" form="config" name="boutonJouer" value="Jouer"><br><br><br>
            </form>
        <?php } else {?>
        <form   method="post" action="index.php?page=partie">
            <input type="submit" value="Lancer la partie"><br>
        </form>
            <form   method="post" action="">
                <input type="submit" name="resetPartie" value="Réinitialiser la partie"><br><br><br>
            </form>
            <?php } ?>
        <form method="post" action="">
            <input type="submit" name="boutonMiseAJourGit" value="Mettre à jour le site"/>
        </form>
        <form method="post" action="">
            <input type="submit" name="boutonIntegration" value="Intégrer les données"/>
        </form>
        <?php echo php_uname()."<br>";
        echo PHP_OS;
        ?>
    </main>
</div>
<?php include('static/footer.php'); ?>
</body>
<script src="js/correction.js" charset="utf-8"></script>
<script src="js/messages.js" charset="utf-8"></script>
</html>