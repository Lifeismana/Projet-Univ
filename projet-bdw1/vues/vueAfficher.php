<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
	<!-- le titre du document, qui apparait dans l'onglet du navigateur -->
    <title><?= $nomSite ?></title>
    <!-- lie le style CSS externe  -->
    <link href="css/style.css" rel="stylesheet" media="all" type="text/css">
    <!-- ajoute une image favicon (dans l'onglet du navigateur) -->
    <link rel="shortcut icon" type="image/x-icon" href="img/logo_bd.png" />
</head>
<body>
    <?php include('static/header.php'); ?>
    <div id="divCentral">
		<?php include('static/menu.php'); ?>
		<main>
            <table border="1" id="tableauPartie">
                <?php
                // $NbrLigne : le nombre de lignes
                $NbrLigne = 20;
                // $NbrCol : le nombre de colonnes
                $NbrCol = 10;
                // -------------------------------

                ?>
                <tbody>
                <?php
                // pour chaque ligne
                for ($i=0; $i<$NbrLigne; $i++)
                {
                    ?>
                    <tr>
                        <?php
                        // pour chaque colonne (de la ligne)
                        for ($j=0; $j<$NbrCol; $j++)
                        {
                            ?>
                            <td><?php $info = mysqli_fetch_array ($quartier);
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
                        } // end for
                        ?>
                    </tr>
                    <?php
                } // end for
                ?>
                </tbody>
            </table>

		</main>

	</div>
    <?php include('static/footer.php'); ?>
</body>
<script src="js/correction.js" charset="utf-8"></script>
<script src="js/messages.js" charset="utf-8"></script>
</html>

