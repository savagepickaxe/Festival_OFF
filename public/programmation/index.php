<?php $niveau="../";?>
<?php
	// Inclusion du fichier de configuration de l'endroit
	include($niveau.'liaisons/php/config.inc.php');

    if (isset($_GET['id']) == true) {
        $strId = $_GET['id'];
    } else {
        $strId = 8;
    }
	// Requete pour obtenire : Les ID des artistes et les Noms des artistes
	$strRequeteUn =  'SELECT DISTINCT DAYOFMONTH(date_et_heure) AS date_jour, MONTH(date_et_heure) as date_mois, DAYOFWEEK(evenements.date_et_heure) AS date_jourSemaine FROM evenements ORDER BY date_mois, date_jour';

    $strRequeteDeux = 'SELECT lieux.id AS id_lieu, lieux.nom AS nom_lieu
                        FROM lieux
                        ORDER BY lieux.nom';

	// Artistes Suggerés -------------------------------------------------------------


    $pdosResultat = $pdoConnexion->prepare($strRequeteUn);
    $pdosResultat->execute(); //Stockage dans une array
    $arrDates = [];
    $arrDaysofWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    $arrMonths = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'May', 'Juin', 'Juillet', 'Aout', 'September', 'October', 'November', 'December'];


    $ligne = $pdosResultat->fetch();

    for ($cpt = 0; $cpt < $pdosResultat->rowCount(); $cpt++) {
        $arrDates[$cpt]['id_date'] = $cpt;
        $arrDates[$cpt]['date_jour'] = $ligne['date_jour'];
        $arrDates[$cpt]['date_mois'] = $ligne['date_mois'];
        $arrDates[$cpt]['date_jourSemaine'] = $ligne['date_jourSemaine'];
        $ligne = $pdosResultat->fetch();
    }
    $pdosResultat->closeCursor();



    // ----------------
    $pdosResultat = $pdoConnexion->prepare($strRequeteDeux);
    $pdosResultat->execute(); //Stockage dans une array
    $arrLieux = [];

    $ligne = $pdosResultat->fetch();

    if ($strId == 0) {
        for ($cpt = 0; $cpt < $pdosResultat->rowCount(); $cpt++) {
            $arrLieux[$cpt]['id_lieu'] = $ligne['id_lieu'];
            $arrLieux[$cpt]['nom_lieu'] = $ligne['nom_lieu'];
                $pdosResultatEvenement = $pdoConnexion->prepare('SELECT lieux.id AS id_lieu, artistes.id AS id_artiste, lieux.nom AS nom_lieu, artistes.nom AS nom_artiste, DAY(evenements.date_et_heure) AS date_jour, HOUR(evenements.date_et_heure) AS date_heure, MINUTE(evenements.date_et_heure) AS date_minute
                                                                    FROM evenements INNER JOIN lieux ON evenements.lieu_id = lieux.id
                                                                    INNER JOIN artistes ON evenements.artiste_id = artistes.id
                                                                    WHERE lieux.id =' . $arrLieux[$cpt]['id_lieu']);
                $pdosResultatEvenement->execute();
                $ligneEvenement = $pdosResultatEvenement->fetch();
                $arrEvenements = [];
    
                for ($cpt2 = 0; $cpt2 < $pdosResultatEvenement->rowCount(); $cpt2++) {
                    if ($ligneEvenement['date_minute'] == "0") {
                        $ligneEvenement['date_minute'] = "00";
                    }
                    $arrEvenements[$cpt2]['heure'] = $ligneEvenement['date_heure'];
                    $arrEvenements[$cpt2]['minute'] = $ligneEvenement['date_minute'];
                    $arrEvenements[$cpt2]['artiste'] = $ligneEvenement['nom_artiste'];
                    $arrEvenements[$cpt2]['id_artiste'] = $ligneEvenement['id_artiste'];

                        // Afficher les styles de l'artiste sur une ligne --------------------------------------
                        $strStyles = "";
                        // Requete pour obtenire : Le Nom de style de l'artiste et L'ID du style de l'artiste présent
                        $pdosResultatStyle = $pdoConnexion->prepare('SELECT styles.id AS id_style, styles.nom AS nom_style, styles_artistes.artiste_id AS id_artiste, styles_artistes.style_id AS ti_id_style
                                                                        FROM styles_artistes INNER JOIN styles ON styles_artistes.style_id = styles.id
                                                                        WHERE styles_artistes.artiste_id =' . $arrEvenements[$cpt2]['id_artiste']);
                        $pdosResultatStyle->execute();
                        $ligneStyle = $pdosResultatStyle->fetch();
                        for ($cpt3 = 0; $cpt3 < $pdosResultatStyle->rowCount(); $cpt3++) {
                            // Accumuler les styles de l'artiste
                            $strStyles .= $ligneStyle['nom_style'] . ", ";
                            $ligneStyle = $pdosResultatStyle->fetch();
                        }
                        // Enlever la virgule a la fin de la liste des styles de l'artiste

                    $arrEvenements[$cpt2]['styles'] = substr_replace($strStyles, "", -2);;
                    $ligneEvenement = $pdosResultatEvenement->fetch();
                }
            $ligne = $pdosResultat->fetch();
            $arrLieux[$cpt]['info'] = $arrEvenements;
        }
    
        $pdosResultat->closeCursor();
    } else {
        for ($cpt = 0; $cpt < $pdosResultat->rowCount(); $cpt++) {
            $arrLieux[$cpt]['id_lieu'] = $ligne['id_lieu'];
            $arrLieux[$cpt]['nom_lieu'] = $ligne['nom_lieu'];
                $pdosResultatEvenement = $pdoConnexion->prepare('SELECT lieux.id AS id_lieu, artistes.id AS id_artiste, lieux.nom AS nom_lieu, artistes.nom AS nom_artiste, DAY(evenements.date_et_heure) AS date_jour, HOUR(evenements.date_et_heure) AS date_heure, MINUTE(evenements.date_et_heure) AS date_minute
                                                                    FROM evenements INNER JOIN lieux ON evenements.lieu_id = lieux.id
                                                                    INNER JOIN artistes ON evenements.artiste_id = artistes.id
                                                                    WHERE lieux.id =' . $arrLieux[$cpt]['id_lieu'] . ' AND DAY(evenements.date_et_heure) =' . $strId . ' ORDER BY DAY(evenements.date_et_heure)');
                $pdosResultatEvenement->execute();
                $ligneEvenement = $pdosResultatEvenement->fetch();
                $arrEvenements = [];
                $arrStylesArtiste = [];

                for ($cpt2 = 0; $cpt2 < $pdosResultatEvenement->rowCount(); $cpt2++) {
                    if ($ligneEvenement['date_minute'] == "0") {
                        $ligneEvenement['date_minute'] = "00";
                    }
                    $arrEvenements[$cpt2]['heure'] = $ligneEvenement['date_heure'];
                    $arrEvenements[$cpt2]['minute'] = $ligneEvenement['date_minute'];
                    $arrEvenements[$cpt2]['artiste'] = $ligneEvenement['nom_artiste'];
                    $arrEvenements[$cpt2]['id_artiste'] = $ligneEvenement['id_artiste'];

                        // Afficher les styles de l'artiste sur une ligne --------------------------------------
                        $strStyles = "";
                        // Requete pour obtenire : Le Nom de style de l'artiste et L'ID du style de l'artiste présent
                        $pdosResultatStyle = $pdoConnexion->prepare('SELECT styles.id AS id_style, styles.nom AS nom_style, styles_artistes.artiste_id AS id_artiste, styles_artistes.style_id AS ti_id_style
                                                                        FROM styles_artistes INNER JOIN styles ON styles_artistes.style_id = styles.id 
                                                                        WHERE styles_artistes.artiste_id =' . $arrEvenements[$cpt2]['id_artiste']);
                        $pdosResultatStyle->execute();
                        $ligneStyle = $pdosResultatStyle->fetch();
                        for ($cpt3 = 0; $cpt3 < $pdosResultatStyle->rowCount(); $cpt3++) {
                            // Accumuler les styles de l'artiste
                            $strStyles .= $ligneStyle['nom_style'] . ", ";
                            $ligneStyle = $pdosResultatStyle->fetch();
                        }
                    // Enlever la virgule a la fin de la liste des styles de l'artiste
                    $arrEvenements[$cpt2]['styles'] = substr_replace($strStyles, "", -2);;
                    $ligneEvenement = $pdosResultatEvenement->fetch();
                }
            $ligne = $pdosResultat->fetch();
            $arrLieux[$cpt]['info'] = $arrEvenements;
        }
    
        $pdosResultat->closeCursor();
    }
   
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>Festival OFF - Programmation</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Page de programmation pour le Festival OFF" />
        <meta name="keywords" content="Festival, Programmation, Dates, Lieux, Artistes" />
        <meta name="author" content="El Hadj Papa Magor Sow" />
        <?php include($niveau . "liaisons/fragments/headlinks.inc.php") ?>
    </head>

    <body class="body">
        <?php include($niveau . "liaisons/fragments/entete.inc.php") ?>
        <main class="main">
            <div class="main_head">
                <h1 class="main_head-title">Programmation</h1>
                <div class="main_head-dropdown">
                    <button class="main_head-dropBtn">Afficher les lieux</button>
                    <div class="main_head-dropDiv main_head-dropHidden">
                        <div class="main_head-dropContent">
                            <?php for ($cpt = 0; $cpt < count($arrLieux); $cpt++) { ; ?>
                                <p class="main_head-dropText" href="#"><?php echo $arrLieux[$cpt]['nom_lieu']; ?></p>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main_main">
                <section class="dates_section">
                    <a class="<?php if ($strId == 8) { echo "dates_section-button--inactive";} else { echo "dates_section-button";} ?> dates_section-previous" href="index.php?id=<?php echo $strId-"1"; ?>"></a>
                    <ul class="dates_section-liste">
                        <?php for ($cpt = 0; $cpt < count($arrDates); $cpt++) { ; ?>
                            <?php if ($arrDates[$cpt]['date_jour'] == $strId) {; ?>
                                <a href="index.php?" class="dates_section-listeLien lien_sans-deco">
                                    <li class="dates_section-listeItem--active">
                            <?php } else {; ?>
                                <a href="index.php?id=<?php echo $arrDates[$cpt]['date_jour']; ?>" class="dates_section-listeLien lien_sans-deco">
                                    <li class="dates_section-listeItem">
                            <?php }; ?>
                                        <?php echo "<p class='dates_section-listeJour'>" . $arrDaysofWeek[$arrDates[$cpt]['date_jourSemaine']-1]. "</p>"; ?>
                                        <?php echo "<p class='dates_section-listeDate'>" . $arrDates[$cpt]['date_jour'] . "</p>"; ?>
                                        <?php echo "<p class='dates_section-listeMois'>" . $arrMonths[$arrDates[$cpt]['date_mois']-1] . "</p>"; ?>
                                    </li>
                            </a>
                        <?php }?>
                    </ul>
                    <a class="<?php if ($strId == 11) { echo "dates_section-button--inactive";} else { echo "dates_section-button";} ?> dates_section-next" href="index.php?id=<?php echo $strId+"1"; ?>"></a>
                </section>
                <?php for ($cpt = 0; $cpt < count($arrLieux); $cpt++) { ; ?>
                    <section class="artistes_section">
                        <div class="artistes_section-horaire">
                            <div class="artistes_section-head">
                                <img class="artistes_section-headImage" src="../liaisons/images/Image.png" alt="">
                                <div class="artistes_section-headText">
                                    <h2 class="h2 artistes_section-titre"><?php echo $arrLieux[$cpt]['nom_lieu']; ?></h2>
                                    <h3 class="h3 artistes_section-sousTitre">801-811 rue Saint-Jean, Québec</h3>
                                </div>
                            </div>
                            <?php for ($cpt2 = 0; $cpt2 < count($arrLieux[$cpt]['info']); $cpt2++) { ; ?>
                                    <a href="<?php echo  $niveau ;?>artistes/fiches/index.php?id_artiste=<?php echo $arrLieux[$cpt]['info'][$cpt2]['id_artiste']; ?>" class="artistes_section-lien">
                                        <div class="artistes_section-info">
                                            <picture class="artistes_section-picture">
                                                <source media="(min-width:920px)" srcset="<?php echo $niveau; ?>liaisons/images/artistes/carre/<?php echo $arrLieux[$cpt]['info'][$cpt2]['id_artiste']; ?>_0__w290_carre.jpg">
                                                <img class="artistes_section-image" src="<?php echo $niveau; ?>liaisons/images/artistes/rect/<?php echo $arrLieux[$cpt]['info'][$cpt2]['id_artiste']; ?>_0__w260_rect.jpg" alt="Image de l'artiste <?php echo $arrLieux[$cpt]['info'][$cpt2]['artiste'] ; ?>" style="width:auto;">
                                            </picture>
                                            <div class="artistes_section-infoGroup">
                                                <h4 class="h4 artistes_section-infoNom"><?php echo $arrLieux[$cpt]['info'][$cpt2]['artiste']; ?></h4>
                                                <p class="artistes_section-infoStyles"><?php echo $arrLieux[$cpt]['info'][$cpt2]['styles']; ?></p>
                                                <p class="artistes_section-infoPermission"><?php echo "Requis"; ?></p>
                                                <p class="artistes_section-infoTemp"><?php echo $arrLieux[$cpt]['info'][$cpt2]['heure'] . ":" . $arrLieux[$cpt]['info'][$cpt2]['minute']; ?></p>
                                            </div>
                                        </div>
                                    </a>
                            <?php }?>
                        </div>
                    </section>
                <?php }?>     
            </div>
        </main>
        <?php include($niveau . "liaisons/fragments/piedDePage.inc.php") ?>
    </body>
</html>