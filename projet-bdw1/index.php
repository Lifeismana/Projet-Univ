<?php
// index.php fait office de controleur frontal
session_start(); // démarre ou reprend une session
if(file_exists('../private/constantes.php'))  // vous n'avez pas besoin des lignes 4 à 6
	require('../private/constantes.php'); // inclut un fichier de constantes "privé"
else
	require('inc/constantes.php'); // vous pouvez inclure directement ce fichier de constantes (sans le if ... else précédent)
require('inc/includes.php'); // inclut le fichier avec fonctions (notamment celles du modele)
require('inc/routes.php'); // fichiers de routes

if(isset($_GET['page'])) {
	$nomPage = $_GET['page'];
	
	if(isset($routes[$nomPage])&&$nomPage!="Accueil") {
		$controleur = $routes[$nomPage]['controleur'];
		$vue = $routes[$nomPage]['vue'];
		include('controleurs/' . $controleur . '.php');
		include('vues/' . $vue . '.php');
	}
	else {
		include('controleurs/controleurAccueil.php');
		include('vues/vueAccueil.php');
	}
}
else {
	include('controleurs/controleurAccueil.php');
	include('vues/vueAccueil.php');
}


