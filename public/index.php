
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
</head>

<body>
	<header>
		<?php include $niveau . "liaisons/fragments/entete.inc.php"; ?>
	</header>

	<main>
	</main>

	<footer>
		<?php include $niveau . "liaisons/fragments/piedDePage.inc.php"; ?>
	</footer>
</body>
</html>