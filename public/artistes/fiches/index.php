<?php 
    //---------------------------------- Connexion à la base de données ----------------------------------//
    $niveau="../../";
    include($niveau.'liaisons/php/config.inc.php');

    //Récupération des paramètres d'artiste dans l'URL
    if(isset($_GET['id_artiste'])){
        $id_artiste = $_GET['id_artiste'];
    }

    //Gestion des jours et des mois
    $arrJours = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
    $arrMois = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');


    //Requête pour obtenir les dates et les lieux des événements en fonction de l'artiste sélectionné
    $strRequeteDateLieu = 'SELECT HOUR(date_et_heure) AS heure, MINUTE(date_et_heure) AS minute, DAY(date_et_heure) AS jour, MONTH(date_et_heure) AS mois, YEAR(date_et_heure) AS annee, DAYOFWEEK(date_et_heure) AS jour_semaine, lieux.nom FROM evenements 
                           INNER JOIN lieux ON evenements.lieu_id=lieux.id 
                           WHERE artiste_id =' . $id_artiste
                           . ' ORDER BY date_et_heure';

    $pdoResultatDateLieu = $pdoConnexion->query($strRequeteDateLieu);
    $arrDateLieu = array();
    for($i=0; $ligne = $pdoResultatDateLieu->fetch(); $i++){
        $arrDateLieu[$i]['heure'] = $ligne['heure'];
        $arrDateLieu[$i]['minute'] = $ligne['minute'];
        $arrDateLieu[$i]['lieu'] = $ligne['nom'];
        $arrDateLieu[$i]['jour'] = $ligne['jour'];
        $arrDateLieu[$i]['jour_semaine'] = $ligne['jour_semaine'];
        $arrDateLieu[$i]['mois'] = $ligne['mois'];
        $arrDateLieu[$i]['annee'] = $ligne['annee'];
    }

    //----------------------------------------------------------------------------------------------------//
    //Requête pour obtenir le lieu de provenance
    $strRequeteInfosArtiste = 'SELECT artistes.nom, description, provenance, pays, site_web, myspace FROM artistes 
                                WHERE id=' . $id_artiste;

    $pdoResultatInfosArtiste = $pdoConnexion->query($strRequeteInfosArtiste);
    $arrInfosArtiste = $pdoResultatInfosArtiste->fetchAll(PDO::FETCH_NUM);

    $pdoResultatInfosArtiste->closeCursor();

    //----------------------------------------------------------------------------------------------------//
    //Requête pour obtenir tous les styles de l'artiste
    $strRequeteStyles = 'SELECT styles.nom, styles_artistes.style_id FROM styles 
                         INNER JOIN styles_artistes ON styles.id=styles_artistes.style_id 
                         WHERE styles_artistes.artiste_id=' . $id_artiste;
    
    $pdoResultatStyles = $pdoConnexion->query($strRequeteStyles);
    $arrStyles = $pdoResultatStyles->fetchAll();

    //----------------------------------------------------------------------------------------------------//
    //Gestion d'affichage des images

    $arrTotalImages = array("test1", "test2", "test3", "test4", "test5", "test6", "test7", "test8", "test9", "test10");
    $intNbImagesAffichage = rand(3, 5);
    $arrImagesChoisies = array();

    for($i = 0; $i < $intNbImagesAffichage; $i++){
        $intNbTotalImages = count($arrTotalImages);
        $intIndexHazard = rand(0, $intNbTotalImages - 1);
		array_push($arrImagesChoisies,$arrTotalImages[$intIndexHazard]);
		array_splice($arrTotalImages,$intIndexHazard,1);
        // print_r($arrImagesChoisies); echo "<br>";
    }

    //---------------------------------- Suggestions d'artistes ----------------------------------//

    //Sélection aléatoire de 3 à 5 artistes
    $nbSuggestions = rand(1, 3);

    //Sélection de tous les artistes
    $strRequeteSuggestion = 'SELECT DISTINCT artistes.id, nom FROM artistes 
                            INNER JOIN styles_artistes ON artistes.id=styles_artistes.artiste_id 
                            WHERE style_id IN 
                            (SELECT style_id FROM styles_artistes WHERE artiste_id=' . $id_artiste . ') 
                            AND artistes.id !=' . $id_artiste . ' ORDER BY nom';

    //Récupération des artistes 
    $pdoResultatSuggestion = $pdoConnexion->query($strRequeteSuggestion);

    $arrArtistesSuggestionPotentiel = array();
    for($i = 0; $ligne = $pdoResultatSuggestion->fetch(); $i++){
        $arrArtistesSuggestionPotentiel[$i]['nom'] = $ligne['nom'];
        $arrArtistesSuggestionPotentiel[$i]['id'] = $ligne['id'];
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
    <title> 
        <?php foreach($arrInfosArtiste as $strInfosArtiste){ 
            echo $strInfosArtiste[0];
        } ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Page d'un artiste pour le Festival OFF" />
    <meta name="keywords" content="Festival, Music, Artistes, Genre, Description" />
    <meta name="author" content="Daoud Coulibaly" />
    <?php include($niveau . "liaisons/fragments/headlinks.inc.php") ?>
</head>
<body class="body">
    <a href="#contenu">Aller au contenu</a>
    <?php include($niveau . "liaisons/fragments/entete.inc.php") ?>
    <main>
        <div class="infoArtiste">
            <h1 class="infoArtiste__nom" id="contenu">
                <?php foreach($arrInfosArtiste as $strInfosArtiste){ 
                    echo $strInfosArtiste[0];
                } ?>    
            </h1>
            <p class="infoArtiste__styles">
                <?php foreach($arrStyles as $strStyle){
                    echo $strStyle['nom'] . " / ";
                } ?>
            </p>
            <?php foreach($arrImagesChoisies as $strImage){ ?>
                <img class="infoArtiste__image" src="<?php echo $niveau ?>liaisons/images/<?php echo $strImage ?>.jpg" alt="Image de l'artiste">
            <?php } ?>
            <?php foreach($arrInfosArtiste as $strInfosArtiste){ ?>
                <p class="infoArtiste__provenance">Provenance : <span class="infoArtiste__provenance--smallSize"><?php echo $strInfosArtiste[2]?>, <?php echo $strInfosArtiste[3] ?></span></p>
                <p class="infoArtiste__biographie"><?php echo $strInfosArtiste[1] ?></p>
            <?php } ?>
            <div class="infoArtiste__medias">
                <h2 class="infoArtiste__medias__titre">Liens de contact</h2>
                <ul class="infoArtiste__medias__liste">
                    <?php foreach($arrInfosArtiste as $strInfosArtiste){ ?>
                        <?php if($strInfosArtiste[4] != null){ ?>
                            <li class="infoArtiste__medias__item">Site web : <?php echo $strInfosArtiste[4] ?></li>
                        <?php } ?>
                        <?php if($strInfosArtiste[5] != null){ ?>
                            <li class="infoArtiste__medias__item">Myspace : <?php echo $strInfosArtiste[5] ?></li>
                        <?php } ?>
                        <?php if($strInfosArtiste[4] == null && $strInfosArtiste[5] == null){ ?>
                            <li class="infoArtiste__medias__retroaction">Aucun lien n'est disponible</li>
                        <?php } ?>
                    <?php } ?>        
                </ul>
            </div>
            <div class="infoArtiste__representations">
                <h2 class="infoArtiste__representations__titre">Représentations</h2>
                <ul class="infoArtiste__representations__liste">
                    <?php foreach($arrDateLieu as $strDateLieu){ 
                        for($i = 0; $i < count($arrJours); $i++){
                            for($j = 0; $j < count($arrJours); $j++){
                                if(intval($strDateLieu['jour_semaine']) === $i + 1 && intval($strDateLieu['mois']) === $j + 1){?>
                                    <li class="infoArtiste__representations__item">Heure : <?php echo $strDateLieu['heure'] ?>h <?php echo $strDateLieu['minute'] ?>
                                    <br> Lieu : <?php echo $strDateLieu['lieu'] ?> 
                                    <br>Date : <?php echo $arrJours[$i] . " " . $strDateLieu['jour'] . " " . $arrMois[$j] . " " . $strDateLieu['annee'] ?></li>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="seggestionArtiste">
        <h2 class="seggestionArtiste__titre">Autres artistes à découvrir</h2>
        <ul class="seggestionArtiste__liste">
            <?php foreach($arrArtistesSuggestion as $artiste){ ?>
                <li class="seggestionArtiste__item">
                        <!-- Lien de l'image à finir -->
                        <img src="<?php echo $niveau ?>liaisons/images/artistes/<?php echo $artiste['id'] ?>/..." alt="">
                        <?php echo $artiste['nom'] ?>
                        <button>
                            <a href="<?php echo $niveau ?>artistes/fiches/index.php?id_artiste=<?php echo $artiste['id'] ?>">
                                Voir l'artiste
                            </a>
                        </button>
                    </a>
                </li>
            <?php } ?>
        </ul>
        </div>
    </main>
        <?php include($niveau . "liaisons/fragments/piedDePage.inc.php") ?>

</body>
</html>