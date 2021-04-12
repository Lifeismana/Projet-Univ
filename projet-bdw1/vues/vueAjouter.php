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

			<h2>Ajout d'une série</h2>

			<form method="post" action="#">
				<label for="nomSerie">Nom de la série : </label>
				<input type="text" name="nomSerie" id="nomSerie" placeholder="Orange is the new black" required />
				<br/><br/>
				<input type="submit" name="boutonValider" value="Ajouter"/>
			</form>

		</main>
	</div>
    <?php include('static/footer.php'); ?>
</body>
<script src="js/correction.js" charset="utf-8"></script>
<script src="js/messages.js" charset="utf-8"></script>
</html>