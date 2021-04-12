<!DOCTYPE html>
<!-- 
Page d'accueil non MVC. En début de TP, on vous demande de la modifier directement, puis vous la transformerez selon l'architecture MVC.
-->
<html>
<head>
    <meta charset="utf-8" />
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
			<p id="paragCrit">Pour être un bon critique, il faut critiquer</p>
            <img src="img/noel.jpg" height=100 class="opaque">
            <p>Bienvenue... blah blah...site le plus beau du web... blah blah</p>
			<?php
				$connexion = getConnexionBD(); // connexion à la BD
				// comptage des séries
                // $requete = "SELECT COUNT(nomSerie) AS nbS FROM Series";
				$res = mysqli_query($connexion, $requete);
				if($res == FALSE) {
					$message = "Aucune série n'a été trouvée dans la base de données !";
				}
				else {
					$row = mysqli_fetch_assoc($res);
					$message = "Actuellement ". $row['nbS'] . " séries dans la base.";
				}
			?>
			<div><?= $message ?></div>
			
		</main>
	</div>
    <?php include('static/footer.php'); ?>
</body>
<script src="js/correction.js" charset="utf-8"></script>
<script src="js/messages.js" charset="utf-8"></script>
</html>






