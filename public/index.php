
<?php $niveau = "";

		
?> 
<?php 
	//---------------------------------- Variables importantes ----------------------------------//
	//Gestion des jours et des mois
	$arrJours = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
	$arrMois = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

    //---------------------------------- Connexion à la base de données ----------------------------------//

    $niveau="./";

    include($niveau . 'liaisons/php/config.inc.php');



	$intNbArticlesAffichage = rand(3, 5);
	$strRequeteActualites="SELECT actualites.id, actualites.titre, actualites.auteurs, actualites.date_actualite AS date_complete,
							YEAR(actualites.date_actualite) AS annee, 
							MONTH(actualites.date_actualite) AS mois, 
							DAYOFMONTH(actualites.date_actualite) AS jour, 
							HOUR(actualites.date_actualite) AS heure, 
							MINUTE(actualites.date_actualite) AS minute, 
							actualites.article, evenements.artiste_id, artistes.id AS artiste_id
						FROM actualites
						INNER JOIN evenements ON actualites.id = evenements.id 
						INNER JOIN artistes ON evenements.artiste_id = artistes.id
						ORDER BY actualites.date_actualite DESC 
						LIMIT 0, " . $intNbArticlesAffichage;
	
	$objResultatArticles = $pdoConnexion -> query($strRequeteActualites);
	
	$arrArticles = array();
	
	for($i=0; $ligne = $objResultatArticles->fetch(); $i++){
		$arrArticles[$i]['id'] = $ligne['id'];
		$arrArticles[$i]['titre'] = $ligne['titre'];
		$arrArticles[$i]['auteurs'] = $ligne['auteurs'];
		$arrArticles[$i]['date_complete'] = $ligne['date_complete'];
		$arrArticles[$i]['annee'] = $ligne['annee'];
		$arrArticles[$i]['mois'] = $ligne['mois'];
		$arrArticles[$i]['jour'] = $ligne['jour'];
		$arrArticles[$i]['heure'] = $ligne['heure'];
		$arrArticles[$i]['minute'] = $ligne['minute'];
		$arrArticles[$i]['artiste_id'] = $ligne['artiste_id']; // Ajout de l'artiste_id
	
		$arrContenuArticle = explode(' ', $ligne['article']);
		if(count($arrContenuArticle) > 45){
			array_splice($arrContenuArticle, 45, count($arrContenuArticle));
		}
		$arrArticles[$i]['article_preview'] = implode(' ', $arrContenuArticle);
	}
	
	$objResultatArticles->closeCursor();
	
	//---------------------------------- Suggestions d'artistes ----------------------------------//

    //Sélection aléatoire de 3 à 5 artistes
    $nbSuggestions = rand(3, 5);

    //Sélection de tous les artistes
    $strRequeteSuggestion = "SELECT artistes.nom, artistes.id, artistes.description FROM artistes ORDER BY nom";

    //Récupération des artistes 
    $pdoResultatSuggestion = $pdoConnexion->query($strRequeteSuggestion);
    $arrArtistesSuggestionPotentiel = array();
    for($i = 0; $ligne = $pdoResultatSuggestion->fetch(); $i++){
        $arrArtistesSuggestionPotentiel[$i]['nom'] = $ligne['nom'];
        $arrArtistesSuggestionPotentiel[$i]['id'] = $ligne['id'];
		$arrArtistesSuggestionPotentiel[$i]['description'] = $ligne['description'];
    }

    //Fermeture de la requête
    $pdoResultatSuggestion->closeCursor();

    //---------------------------------------------------
    $arrArtistesSuggestion = array();
    for($i = 0; $i < $nbSuggestions; $i++){
        //Sélection aléatoire d'un artiste
        $artisteChoisi = rand(0, count($arrArtistesSuggestionPotentiel) - 1);
        //Ajout de l'artiste dans le tableau
        array_push($arrArtistesSuggestion, $arrArtistesSuggestionPotentiel[$artisteChoisi]);
        //Suppression de l'artiste du tableau potentiel
        array_splice($arrArtistesSuggestionPotentiel, $artisteChoisi, 1);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Festival OFF - Accueil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Page d'accueil pour le Festival OFF" />
    <meta name="keywords" content="Festival, Articles, Music, Artistes" />
    <meta name="author" content="Esteban Henry" />
    <?php include($niveau . "liaisons/fragments/headlinks.inc.php") ?>
</head>

<body>
	<header>
		<?php include $niveau . "liaisons/fragments/entete.inc.php"; ?>
	</header>

	<main id="contenu" class="conteneur">
			<section class="accueil">
			<header class="gros-titre">
				<div class="lignes ligne1">
					<span>Festival</span>
				</div>
				<div class="lignes ligne2">
					<span>Off</span>
			</div>
			</header>
			<footer class="image-menu">
				<article class="video-background">
					<div class="video-tour">
						<video src="liaisons/videos/OFF.mp4"  
						width="640" height="360" autoplay  loop muted  frameborder="0" allow="autoplay; encrypted-media"
						 allowfullscreen>
						</video>
					</div>
				</article>
				<article class="h1-boite">
					<div class="gros-titre ">OFF</div>
					<div class="coin left-top"></div>
					<div class="coin right-bottom"></div>
				</article>
			</footer>
			</section>


			
		
			<div id="contenu" class="contenu__article">
				
			<section class="article">
    <?php foreach($arrArticles as $article){ ?>
        <article class="contenu__article__artiste">
            <header class="contenu__article__artiste__titreCont">
                <h3 class="contenu__article__artiste__titre"><?php echo $article['titre']; ?></h3>
            </header>
            <picture class="contenu__article__artiste__picture">
                <source media="(min-width:920px)" srcset="<?php echo $niveau; ?>liaisons/images/artistes/carre/<?php echo $article['artiste_id']; ?>_0__w290_carre.jpg">
                <img class="contenu__article__artiste__image" src="<?php echo $niveau; ?>liaisons/images/artistes/rect/<?php echo $article['artiste_id']; ?>_0__w260_rect.jpg" alt="Image de l'article <?php echo $article['titre']; ?>">
            </picture>

            <p class="contenu__article__artiste__preview">
                <?php echo $article['article_preview']; ?>
                <a href="#">...</a>
            </p>

            <footer class="contenu__article__artiste__footer">
                <p><?php echo $article['auteurs']; ?></p>
                <time datetime="<?php echo $article['date_complete']; ?>">
                    <?php echo "Le " . $article['jour'] . " " . $arrMois[$article['mois'] - 1] . " " . $article['annee'] . " à " . $article['heure'] . "h" . $article['minute']; ?>
                </time>
            </footer>
        </article>
    <?php } ?>
</section>


			<section class="suggestions">
    <h2 class="suggestions__titre">Artistes</h2>
    <?php foreach($arrArtistesSuggestion as $artiste){ ?>
        <article class="suggestions__article">
            <ul class="suggestions__article__contenu">
                <li>
                    <a href="<?php echo $niveau ?>artistes/fiches/index.php?id_artiste=<?php echo $artiste['id']; ?>" class="suggestions__article__titre">
                        <?php echo $artiste['nom']; ?>
                    </a>
                    <p class="suggestions__article__description">
                        <?php echo $artiste['description']; ?>
                    </p>
                </li>
            </ul>
            <picture class="suggestions__article__picture">
                <source media="(min-width:920px)" srcset="<?php echo $niveau; ?>liaisons/images/artistes/carre/<?php echo $artiste['id']; ?>_0__w290_carre.jpg">
                <img class="suggestions__article__image" src="<?php echo $niveau; ?>liaisons/images/artistes/rect/<?php echo $artiste['id']; ?>_0__w260_rect.jpg" alt="Image de l'artiste <?php echo $artiste['nom']; ?>">
            </picture>
        </article>
    <?php } ?>
</section>
	
   
      
	</main>

	
		<?php include $niveau . "liaisons/fragments/piedDePage.inc.php"; ?>

</body>
</html>