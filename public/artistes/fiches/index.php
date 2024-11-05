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

    $pdoResultatDateLieu->closeCursor();

    //----------------------------------------------------------------------------------------------------//
    //Requête pour obtenir le lieu de provenance
    $strRequeteInfosArtiste = 'SELECT artistes.nom, description, provenance, pays, site_web, myspace FROM artistes 
                                WHERE id=' . $id_artiste;

    $pdoResultatInfosArtiste = $pdoConnexion->query($strRequeteInfosArtiste);
    // $arrInfosArtiste = $pdoResultatInfosArtiste->fetchAll(PDO::FETCH_NUM);

    $arrInfosArtiste = array();
    for($i = 0; $ligne = $pdoResultatInfosArtiste->fetch(PDO::FETCH_NUM); $i++){
        $arrInfosArtiste[$i][0] = $ligne[0];
        $arrInfosArtiste[$i][1] = $ligne[1];
        $arrInfosArtiste[$i][2] = $ligne[2];
        $arrInfosArtiste[$i][3] = $ligne[3];
        $arrInfosArtiste[$i][4] = $ligne[4];
        $arrInfosArtiste[$i][5] = $ligne[5];

        //----------------------------------------//
        //Requête pour obtenir les styles de l'artiste
        $strRequeteStyleParArtiste = "SELECT styles.nom FROM styles_artistes  
                                      INNER JOIN styles ON styles_artistes.style_id = styles.id 
                                      WHERE artiste_id=" . $id_artiste . " ORDER BY styles.nom";

        $pdoResultatStyleParArtiste = $pdoConnexion->query($strRequeteStyleParArtiste);

        $strResultatStyleParArtiste = array();
        $strStyles = "";
        for($j = 0; $ligneStyle = $pdoResultatStyleParArtiste->fetch(); $j++){
             if($strStyles != ""){
                 $strStyles = $strStyles . ", ";    //ajout d'une virgule lorsque nécessaire
             }
             else{
                 $strStyles = "";
             }
             $strStyles = $strStyles . $ligneStyle['nom'];
        }
         $arrInfosArtiste[$i]['styles'] = $strStyles;

        $pdoResultatStyleParArtiste->closeCursor();
    }

    $pdoResultatInfosArtiste->closeCursor();

    
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

        //----------------------------------------//
        //Requête pour obtenir les styles de l'artiste
        $strRequeteStyleParArtiste = "SELECT styles.nom FROM styles_artistes  
                                      INNER JOIN styles ON styles_artistes.style_id = styles.id 
                                      WHERE artiste_id=" . $arrArtistesSuggestionPotentiel[$i]['id'] . " ORDER BY styles.nom";

        $pdoResultatStyleParArtiste = $pdoConnexion->query($strRequeteStyleParArtiste);

        $strResultatStyleParArtiste = array();
        $strStyles = "";
        for($j = 0; $ligneStyle = $pdoResultatStyleParArtiste->fetch(); $j++){
             if($strStyles != ""){
                 $strStyles = $strStyles . ", ";    //ajout d'une virgule lorsque nécessaire
             }
             else{
                 $strStyles = "";
             }
             $strStyles = $strStyles . $ligneStyle['nom'];
        }
         $arrArtistesSuggestionPotentiel[$i]['styles'] = $strStyles;

        $pdoResultatStyleParArtiste->closeCursor();
    }

    //Fermeture de la requête
    $pdoResultatSuggestion->closeCursor();

    //---------------------------------------------------
    $arrArtistesSuggestion = array();
    if(count($arrArtistesSuggestionPotentiel) > 0){
        for($i = 0; $i < $nbSuggestions; $i++){
            //Sélection aléatoire d'un artiste
            $artisteChoisi = rand(0, count($arrArtistesSuggestionPotentiel) - 1);
            //Ajout de l'artiste dans le tableau
            array_push($arrArtistesSuggestion, $arrArtistesSuggestionPotentiel[$artisteChoisi]);
            //Suppression de l'artiste du tableau potentiel
            array_splice($arrArtistesSuggestionPotentiel, $artisteChoisi, 1);
        }
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
    <meta name="description" content="
        <?php foreach($arrInfosArtiste as $strInfosArtiste){ 
            echo $strInfosArtiste[1];
        } ?>
     " />
    <meta name="keywords" content="Festival, Festival OFF, Musique, Artistes, Concert, <?php
            foreach($arrInfosArtiste as $strInfosArtiste){ 
            echo $strInfosArtiste['styles'];
        } ?>
    " />
    <meta name="author" content="Daoud Coulibaly" />
    <?php include($niveau . "liaisons/fragments/headlinks.inc.php") ?>
</head>
<body class="body">
    <a href="#contenu" class="screen-reader-only focusable">Aller au contenu</a>
    <?php include($niveau . "liaisons/fragments/entete.inc.php") ?>
    <main class="ficheArtiste-main" role="main">
        <section class="infoArtiste">
            <h1 class="infoArtiste__nom" id="contenu">
                <?php foreach($arrInfosArtiste as $strInfosArtiste){ 
                    echo $strInfosArtiste[0];
                } ?>    
            </h1>
            <p class="infoArtiste__styles">
                <?php foreach($arrInfosArtiste as $strInfosArtiste){
                    echo $strInfosArtiste['styles'];
                } ?>
            </p>
            <?php foreach($arrInfosArtiste as $strInfosArtiste){ ?>
                <picture>
                    <source class="infoArtiste__image--table" media="(min-width: 801px)" srcset="<?php echo $niveau ?>liaisons/images/artistes/rect/<?php echo $id_artiste ?>_0__w830_rect.jpg 1x, <?php echo $niveau ?>liaisons/images/artistes/rect/<?php echo $id_artiste ?>_0__w1660_rect.jpg 2x" type="image/jpg">
                    <source class="infoArtiste__image--mobile" media="(max-width: 800px)" srcset="<?php echo $niveau ?>liaisons/images/artistes/carre/<?php echo $id_artiste ?>_0__w320_carre.jpg 1x, <?php echo $niveau ?>liaisons/images/artistes/carre/<?php echo $id_artiste ?>_0__w640_carre.jpg 2x" type="image/jpg">
                    <img class="infoArtiste__image" src="<?php echo $niveau ?>liaisons/images/artistes/carre/<?php echo $id_artiste ?>_0__w640_carre.jpg" alt="Image de l'artiste <?php echo $strInfosArtiste[0]?>" title="Image de l'artiste <?php echo $strInfosArtiste[0]?>">
                </picture>
                <div class="infoArtiste__carrousselControls">
                    <button id="btn-precedent" class="bouton__precedent">
                        <img class="bouton__precedent__image" src="<?php echo $niveau ?>liaisons/images/fiche_artiste/button_carroussel-precedent.svg" alt="Afficher l'image précédente" title="Afficher l'image précédente">
                    </button>
                    <button id="btn-suivant" class="bouton__suivant">
                        <img class="bouton__suivant__image" src="<?php echo $niveau ?>liaisons/images/fiche_artiste/button_carroussel-suivant.svg" alt="Afficher l'image suivante" title="Afficher l'image suivante">
                    </button>
                </div>
            <?php } ?>
            <?php foreach($arrInfosArtiste as $strInfosArtiste){ ?>
                <p class="infoArtiste__provenance">Provenance : <span class="infoArtiste__provenance--smallSize"><?php echo $strInfosArtiste[2]?>, <?php echo $strInfosArtiste[3] ?></span></p>
                <p class="infoArtiste__biographie"><?php echo $strInfosArtiste[1] ?></p>
            <?php } ?>
            <div class="infoArtiste__conteneurMediasRepresentations">
                <div class="infoArtiste__medias">
                    <h2 class="infoArtiste__medias__titre">Liens de contact</h2>
                    <hr>
                    <ul class="infoArtiste__medias__liste">
                        <?php foreach($arrInfosArtiste as $strInfosArtiste){ ?>
                            <?php if($strInfosArtiste[4] != null){ ?>
                                <li class="infoArtiste__medias__item">
                                    <a class="infoArtiste__medias__lien" href="<?php echo $strInfosArtiste[4] ?>" target="_blank">
                                        <span class="infoArtiste__medias__iconeInternet"></span><?php echo $strInfosArtiste[0] ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if($strInfosArtiste[5] != null){ ?>
                                <li class="infoArtiste__medias__item">
                                    <a class="infoArtiste__medias__lien" href="<?php echo $strInfosArtiste[5] ?>">
                                        <span class="infoArtiste__medias__iconeMyspace"></span>http://www.myspace.com/
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if($strInfosArtiste[4] == null && $strInfosArtiste[5] == null){ ?>
                                <li class="infoArtiste__medias__retroaction">Aucun lien n'est disponible</li>
                            <?php } ?>
                        <?php } ?>        
                    </ul>
                </div>
                <div class="infoArtiste__representations">
                    <h2 class="infoArtiste__representations__titre">Représentations</h2>
                    <hr>
                    <ul class="infoArtiste__representations__liste">
                        <?php foreach($arrDateLieu as $strDateLieu){ 
                            for($i = 0; $i < count($arrJours); $i++){
                                for($j = 0; $j < count($arrJours); $j++){
                                    if(intval($strDateLieu['jour_semaine']) === $i + 1 && intval($strDateLieu['mois']) === $j + 1){?>
                                        <li class="infoArtiste__representations__item">
                                            <div class="infoArtiste__representations__emplacements">
                                                <p class="infoArtiste__representations__emplacements__date">
                                                    <span class="infoArtiste__representations__emplacements__iconeDate"></span><?php echo $arrJours[$i] . " " . $strDateLieu['jour'] . " " . $arrMois[$j] . " " . $strDateLieu['annee'] ?>
                                                </p>
                                                <p class="infoArtiste__representations__emplacements__lieu">
                                                    <span class="infoArtiste__representations__emplacements__iconeLieu"></span><?php echo $strDateLieu['lieu'] ?> 
                                                </p>
                                            </div>
                                            <div class="infoArtiste__representations__heure">
                                                <img class="infoArtiste__representations__iconeHeure" src="<?php echo $niveau ?>liaisons/images/fiche_artiste/icons_heure.svg" alt="" title="">
                                                <p class="infoArtiste__representations__texteHeure">
                                                    <?php echo $strDateLieu['heure'] ?>h<?php
                                                     
                                                    if($strDateLieu['minute']=="0"){
                                                        echo "00";
                                                    } 
                                                    else{
                                                        echo $strDateLieu['minute']; 
                                                    }?>
                                                </p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </section>
        <section class="seggestionArtiste">
            <h2 class="seggestionArtiste__titre">Autres artistes à découvrir</h2>
            <hr>
            <ul class="seggestionArtiste__liste">
                <?php foreach($arrArtistesSuggestion as $artiste){ ?>
                    <li class="seggestionArtiste__item">
                        <div class="seggestionArtiste__infos">
                            <img class="seggestionArtiste__infos__image" src="<?php echo $niveau ?>liaisons/images/artistes/carre/<?php echo $artiste['id'] ?>_0__w185_carre.jpg" alt="Image de l'artiste <?php echo $artiste['nom'] ?>" title="Image de l'artiste <?php echo $artiste['nom'] ?>">
                            <h3 class="seggestionArtiste__infos__nom">
                                <?php echo $artiste['nom'] ?>
                            </h3>
                            <p class="seggestionArtiste__infos__style">
                                <?php echo $artiste['styles'] ?>
                            </p>
                        </div>
                        <a class="bouton bouton__lien" href="<?php echo $niveau ?>artistes/fiches/index.php?id_artiste=<?php echo $artiste['id'] ?>">
                            Voir l'artiste
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </section>
    </main>
        <?php include($niveau . "liaisons/fragments/piedDePage.inc.php") ?>

</body>
</html>