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
        <?php if(isset($_SESSION['game']['status'])&& $_SESSION['game']['status'] == 1){ ?>
        <table class="tableauPartie">
            <?php $NbrLigne = 20;$NbrCol = 10; ?>
            <tbody>
            <?php
            for ($i=0; $i<$NbrLigne; $i++)
            {
                ?>
                <tr>
                    <?php
                    for ($j=0; $j<$NbrCol; $j++)
                    {
                        ?>
                        <td <?php  $key=array_search(($j+$i*10)+1,$_SESSION['game']['position']) ;if(is_int($key)) {echo "id=\"personne$key\""; }?> >
                            <?php
                            $info = mysqli_fetch_array ($quartier);
                            if (!($j+$i*10 == 199)) {
                                echo $info['idQ'] . " " . $info['nomQ'] . " " . $info['nomC'] . "<br>";
                                $infoR = mysqli_fetch_array($route);
                                if ($infoR['idQDepart'] == $info['idQ'])
                                {
                                    $tabidQA = explode(',',$infoR['idQA']);
                                    $tabctT = explode(',',$infoR['ctT']);
                                    for ($g=0;$g<$infoR['cQA']; $g++){
                                        echo $tabidQA[$g]." ".convToEmoji($tabctT[$g]).", ";
                                    }
                                }
                                else echo "Error";
                            }
                            ?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <form   method="post" action="index.php?page=partie">
            <label for="idChamp">Choisir une route:
                <select name="champRoute" id="idChamp" required>
                    <option value="">--Veulliez choisir une destination--</option>
                    <?php while ($route = mysqli_fetch_assoc($routeJoueuse)) { ?>
                        <option value="<?= $route['idQArrive'] ?>"><?= $route['nomQ']." ".$route['typeT']." ".$route['idQArrive'] ?></option>
                    <?php } ?>
                </select>
            </label>
            <input type="submit" name="Move" value="Se Déplacer"><br><br><br>
        </form>
        <?php }
        elseif(isset($_SESSION['game']['status'])&& $_SESSION['game']['status'] == 2)//partie perdu
        { ?>
            <h1>PARTIE PERDU</h1><br><br><br>
            <form   method="post" action="index.php?accueil">
                <input type="submit" name="resetPartie" value="Redémarrer une partie"><br><br><br>
            </form>
        <?php }
        elseif(isset($_SESSION['game']['status'])&& $_SESSION['game']['status'] == 3)//partie gagné
        { ?>
            <h1>PARTIE GAGNÉE</h1><br><br><br>
            <form   method="post" action="index.php?accueil">
                <input type="submit" name="resetPartie" value="Redémarrer une partie"><br><br><br>
            </form>
        <?php }
        else //partie non initialisé TODO ? mettre des condition ?
        { ?>
            <h1>PARTIE NON INITIALISÉE</h1><br><br><br>
            <form   method="post" action="index.php?accueil">
                <input type="submit" name="resetPartie" value="Configurer une partie"><br><br><br>
            </form>
        <?php } ?>
        <div id="logMessage">
            <?php
            if(isset($message)) {
                foreach ($message as $msg)
                    echo $msg . "<br>";
            }
            ?>
        </div>
    </main>
</div>
<?php include('static/footer.php'); ?>
</body>
<script src="js/correction.js" charset="utf-8"></script>
<script src="js/messages.js" charset="utf-8"></script>
</html>