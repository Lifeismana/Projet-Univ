/* messages.js - 2018 - Fabien Duchateau  */
/* génération de messages en console pour guider le tutorial (HTML, CSS, PHP) */

var i, j; // variables de parcours de collections
var params = location.search;

console.info("Les messages ci-dessous vous aident à vérifier si votre solution est correcte. Attention cependant, ces vérifications automatiques ne sont pas complètement fiables !");

if(params) // message si pas sur page accueil
	console.log("Pour valider les exercices HTML et CSS, il faut également vérifier la page d'accueil (certaines questions sont spécifiques à l'accueil).")


/* q1 (changement titre principal) */
var h1 = document.querySelector("header > h1");
if(h1 == null)
	console.warn("La balise <h1> n'a pas été trouvée ! Il faudrait la remettre...");
else { 
	var valH1 = h1.innerHTML;
	if(valH1 == "Serial critique")
		console.warn("Le titre principal sur la page n'est pas encore changé !");
	else 
		console.log("Bravo, le titre <h1> a été modifié en '" + valH1 + "' !");
}


/* q1 (changement titre document) */
var title = document.querySelector("title");
if(title.text == "Serial critique")
	console.warn("Soyons cohérent, et changeons le titre du document également.");
else 
	console.log("Voici un document avec un titre cohérent !");


/* q2 (paragraphe dans accueil) */
if(params == null || !params) { // que pour page accueil, i.e., sans parametres
	var p = document.querySelector("#contenu > p");
	if(p == null)
		console.warn("Toujours pas de paragraphe descriptif... ou il n'est pas placé au bon endroit.");
	else  
		console.log("Super, vous avez ajouté un paragraphe de description !");
	/* q2 (suppression des div "aide" et "page vide") */
	var divs = document.querySelectorAll("#contenu div.to-delete");
	if(divs.length > 0)
		console.warn("Supprimer les <div> inutiles de la page d'accueil : c'est facile, rapide, et vous aurez un warning en moins.");
	else
		console.log("La page d'accueil est nettoyée des <div> dépressives.");
}


/* q3 (image dans paragraphe dans accueil) */
if(params == null || !params) { // que pour page accueil, i.e., sans parametres
	var img = document.querySelector("#contenu img");
	if(img == null)
		console.warn("Allez, on se dépêche de choisir son image et de la mettre en page d'accueil !");
	else  
		console.log("Ooooh, la jolie image !");
}


/* q4 (lien dans menu, et liste optionnelle) */
var items = document.querySelectorAll("nav a");
var found = false, correctLink = false;
for (i = 0; i < items.length; i++) {
	if(items[i].innerHTML == "Ajouter critique") {
		found = true;
		if(items[i].hasAttribute("href") && items[i].getAttribute("href") == "index.php?page=critiquer"){
			correctLink = true;
		}
	}
}
if(found && correctLink)
	console.log("Ancre correcte, lien correct : c'est parfait, le menu est à jour !");
else if(found)
	console.warn("L'ancre (texte) est juste, mais le lien pose souci : inpirez-vous  des autres liens !");
else 
	console.warn("Patience et longueur de temps, Font plus que force ni que rage (La Fontaine). Ceci dit, il manque toujours un élément de menu !");
var lis = document.querySelectorAll("#menu li");
if(items.length == lis.length)
	console.log("Vous avez même mis le menu sous forme de liste, félicitations !");


/* q5 (paragraphe de accueil #paragDescription avec style) */
if(params == null || !params) { // que pour page accueil, i.e., sans parametres
	var p = document.querySelector("#contenu > p");
	if(p != null && p.hasAttribute("id") && p.getAttribute("id") == "paragDescription")
		console.log("Vous avez placé l'identifiant du paragraphe (#paragDescription). On vous fait confiance pour le style...");
	else
		console.warn("Mais où est cet identifiant de paragraphe #paragDescription ?!");
}
/* not working, document.styleSheets not fully implemented yet
var sheets = document.styleSheets;
console.log(sheets);
var messageStyle = "Votre style pour le paragraphe 'paragDescription' est : ";
sheets.forEach(function(sheet){
	var rules = sheet.cssRules;
	rules.forEach(function(rule){
		if(rule.selectorText == "#paragDescription") {
			if(rule.style["text-align"] == "justify")
				messageStyle += "justifié, ";
			else
				messageStyle += "*non justifié*, ";
			if(rule.style["font-style"] == "italic")
				messageStyle += "en italique, ";
			else
				messageStyle += "*non italique*, ";
			if(rule.style["border-left"])
				messageStyle += "avec bordure gauche, ";
			else
				messageStyle += "*sans bordure à gauche*, "
			if(rule.style["background-color"])
				messageStyle += "avec un fond coloré, ";
			else
				messageStyle += "*sans fond de couleur*, "
			if(rule.style["padding-left"] == "1em")
				messageStyle += "et avec marge interne.";
			else
				messageStyle += "*sans marge interne gauche de 1em*. Persévérez !";
		}
	});
});
*/


/* q6 (lien avec couleur de fond au survol) */
/* not working, document.styleSheets not fully implemented yet
var sheets = document.styleSheets;
var foundStyle = false;
sheets.forEach(function(sheet){
	var rules = sheet.cssRules;
	rules.forEach(function(rule){
		if(rule.selectorText == "nav a:hover" && rule.style["background-color"] == "#FAFAD2")
			foundStyle = true;
	});
});
if(foundStyle)
	console.log("Très bien, ces liens du menu avec un retour visuel jaune.");
else
	console.warn("Toujours sur la TODO liste : les liens du menu survolés ont un fond jaune.");
*/


/* q7 (classe opaque pour logo et licence CC) */
/* not working, document.styleSheets not fully implemented yet
var sheets = document.styleSheets;
var foundStyle = false;
sheets.forEach(function(sheet){
	var rules = sheet.cssRules;
	rules.forEach(function(rule){
		if(rule.selectorText == ".opaque" && rule.style["opacity"] == "0.7")
			foundStyle = true;
	});
});
if(foundStyle)
	console.log("Définition du style de classe 'opaque' : ok.");
else
	console.warn("Définition du style de classe 'opaque' : ???.");
*/

var imgs = document.querySelectorAll("img");
var nbImgFound = 0;
var nbImgClass = 0;
for (i = 0; i < imgs.length; i++) {
	if(imgs[i].hasAttribute("src") && (imgs[i].getAttribute("src") == "img/sheep.png" || imgs[i].getAttribute("src") == "img/by-nc-sa-eu.png")) {
		nbImgFound++;
		if(imgs[i].hasAttribute("class"))
			nbImgClass++;
	}
}
if(nbImgClass == 2) {
	console.log("Bien, vous avez affecté la classe 'opaque' aux images logo et licence. Si le style lié à l'opacité est écrit et fonctionnel, bravo !");
}
else if(nbImgClass == 1) {
	console.warn("À mi-chemin ! L'une des deux images possède bien une classe 'opaque'. Ca ne devrait pas être difficile de faire la même chose pour l'autre.");
}
else if(nbImgClass == 0) {
	console.warn("Tout est clair, alors que deux images devraient être opaques ! Ajoutez-donc les classes manquantes.");
}
if(nbImgFound < 2) {
	console.warn("L'adresse du logo et/ou de la licence a été modifiée (attribut src). Les messages JS utilisent les valeurs de cet attribut src, donc mieux vaut remettre les valeurs initiales...");
}


console.log("Pour l'exercice PHP, il n'y a plus de message en console... vérifiez votre code avec votre enseignant.e !");
