
<?php $niveau = "";
/*
			$requeteSQL="Select titre from actualites";
			$objStat=$objPdo -> prepare($requeteSQL);
			$objStat -> execute();
			$arrActualite=$objStat -> fetchAll();
			forEach($arrActualite as $actualite){
				echo $actualite["titre"];?><BR>
			<?php } 
			*/
?> 


<!DOCTYPE html>
<html lang="fr">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keyword" content="">
	<meta name="author" content="">
	<meta charset="utf-8">
	<title>Festival Off</title>
	<?php include $niveau . "liaisons/php/config.inc.php"; ?>
	<?php include $niveau . "liaisons/fragments/headlinks.inc.php"; ?>
</head>

<body>
	<header>
		<?php include $niveau . "liaisons/fragments/entete.inc.php"; ?>
	</header>

	<main>
<div id="container"></div>
	<script src="liaisons/js/script.js"></script>
		<div id="contenu" class="conteneur">
			<section class="accueil">
			<div class="gros-titre">
				<div class="lignes ligne1">
					<span>Festival</span>
					<div class="bulle"></div>
				</div>
				<div class="lignes ligne2">
					<span>Off</span>
			</div>
			</div>
			<div class="image-menu">
				<div class="video-background">
					<div class="video-tour">
						<video src="liaisons/videos/OFF.mp4"  
						width="640" height="360" autoplay  loop muted  frameborder="0" allow="autoplay; encrypted-media"
						 allowfullscreen>
							
</video>
					</div>
				</div>
				<div class="h1-boite">
					<div class="gros-titre ">OFF</div>
					<div class="coin left-top"></div>
					<div class="coin right-bottom"></div>
				</div>
			</div>

			</section>
			



			<section>
				<h3>Entête de section</h3>
				<article>
			
			</section>
		</div>
	
   
        <p><a href="#" class="bouton">Bouton</a></p>
		<p><a href="#" class="bouton--inverse">Bouton</a></p>
     <a href="#" class="hyperlien">lien test!</a>
	</main>

	
		<?php include $niveau . "liaisons/fragments/piedDePage.inc.php"; ?>

</body>
</html>