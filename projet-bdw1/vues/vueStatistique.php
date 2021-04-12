<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
	<!-- le titre du document, qui apparait dans l'onglet du navigateur -->
    <title><?= $nomSite ?></title>
    <!-- lie le style CSS externe  -->
    <link href="css/style.css" rel="stylesheet" media="all" type="text/css">
    <!-- ajoute une image favicon (dans l'onglet du navigateur) -->
    <link rel="shortcut icon" type="image/x-icon" href="img/sheep.png" />
</head>
<body>
    <?php include('static/header.php'); ?>
    <div id="divCentral">
		<?php include('static/menu.php'); ?>
        <main>
            <?php
                    echo 'Nombre Routes: '.$resCountidR['countidR'].'<br>';
                    echo 'Nombre Quartiers: '.$resCountidQ['countidQ'].'<br>';
                    echo 'Nombre Communes: '.$resCountCom['countCom'].'<br>';
                     echo "Nombre de Victoire par Joueuse: <br>";
                    while ($countVictoire = mysqli_fetch_assoc($resCountVictoire)) { ?>
                        <table class="tableauStat">
                            <thead>
                            <tr>
                                <th> idJ </th>
                                <th> Nom du Joueur </th>
                                <th> Nombre du Victoire </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?= $countVictoire['idJ'] ?></td>
                                <td><?= $countVictoire['nomJ']?></td>
                                <td><?= $countVictoire['countVictoire'] ?></td>
                            </tr>
                            </tbody>
                        </table>
                    <?php }
                    while ($countVictoire = mysqli_fetch_assoc($resCountTop3)){
                        echo "Top des meilleurs joueuses: ".$countVictoire['nomC'].'<br>';
                    }


            ?>
		</main>
	</div>
    <?php include('static/footer.php'); ?>
</body>
<script src="js/correction.js" charset="utf-8"></script>
<script src="js/messages.js" charset="utf-8"></script>
</html>