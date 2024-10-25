<?php $niveau="../";?>
<?php
	// Inclusion du fichier de configuration de l'endroit
	include($niveau.'liaisons/php/config.inc.php');

    if (isset($_GET['id']) == true) {
        $strId = $_GET['id'];
    } else {
        $strId = 0;
    }

	// Requete pour obtenire : Les ID des artistes et les Noms des artistes
	$strRequeteUn =  'SELECT DISTINCT DAY(ti_evenement.date_et_heure) AS date_jour, MONTH(ti_evenement.date_et_heure) AS date_mois
                        FROM ti_evenement
                        ORDER BY ti_evenement.date_et_heure';

    $strRequeteDeux = 'SELECT t_lieu.id_lieu, t_lieu.nom_lieu
                        FROM t_lieu
                        ORDER BY t_lieu.nom_lieu';

	// Artistes Suggerés -------------------------------------------------------------


    $pdosResultat = $pdoConnexion->prepare($strRequeteUn);
    $pdosResultat->execute(); //Stockage dans une array
    $arrDates = [];

    $ligne = $pdosResultat->fetch();

    for ($cpt = 0; $cpt < $pdosResultat->rowCount(); $cpt++) {
        $arrDates[$cpt]['id_date'] = $cpt;
        $arrDates[$cpt]['date_jour'] = $ligne['date_jour'];
        $arrDates[$cpt]['date_mois'] = $ligne['date_mois'];
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
                $pdosResultatEvenement = $pdoConnexion->prepare('SELECT t_lieu.id_lieu, t_artiste.id_artiste, t_lieu.nom_lieu, t_artiste.nom_artiste, DAY(ti_evenement.date_et_heure) AS date_jour, HOUR(ti_evenement.date_et_heure) AS date_heure, MINUTE(ti_evenement.date_et_heure) AS date_minute
                                                                FROM ti_evenement INNER JOIN t_lieu ON ti_evenement.id_lieu = t_lieu.id_lieu
                                                                INNER JOIN t_artiste ON ti_evenement.id_artiste = t_artiste.id_artiste
                                                                WHERE t_lieu.id_lieu =' . $arrLieux[$cpt]['id_lieu']);
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
                        $pdosResultatStyle = $pdoConnexion->prepare('SELECT t_style.id_style, t_style.nom_style, ti_style_artiste.id_artiste, ti_style_artiste.id_style AS ti_id_style
                                                                        FROM ti_style_artiste INNER JOIN t_style ON ti_style_artiste.id_style = t_style.id_style 
                                                                        WHERE ti_style_artiste.id_artiste =' . $arrEvenements[$cpt2]['id_artiste']);
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
                $pdosResultatEvenement = $pdoConnexion->prepare('SELECT t_lieu.id_lieu, t_artiste.id_artiste, t_lieu.nom_lieu, t_artiste.nom_artiste, DAY(ti_evenement.date_et_heure) AS date_jour, HOUR(ti_evenement.date_et_heure) AS date_heure, MINUTE(ti_evenement.date_et_heure) AS date_minute
                                                                FROM ti_evenement INNER JOIN t_lieu ON ti_evenement.id_lieu = t_lieu.id_lieu
                                                                INNER JOIN t_artiste ON ti_evenement.id_artiste = t_artiste.id_artiste
                                                                WHERE t_lieu.id_lieu =' . $arrLieux[$cpt]['id_lieu'] . ' AND DAY(ti_evenement.date_et_heure) =' . $strId . ' ORDER BY DAY(ti_evenement.date_et_heure)');
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
                        $pdosResultatStyle = $pdoConnexion->prepare('SELECT t_style.id_style, t_style.nom_style, ti_style_artiste.id_artiste, ti_style_artiste.id_style AS ti_id_style
                                                                        FROM ti_style_artiste INNER JOIN t_style ON ti_style_artiste.id_style = t_style.id_style 
                                                                        WHERE ti_style_artiste.id_artiste =' . $arrEvenements[$cpt2]['id_artiste']);
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
                <h1 class="main_title">Programmation</h1>
                <div class="dropdown">
                    <button class="dropbtn">Afficher les lieux</button>
                    <div class="dropdown-content">
                        <a href="#">Link 1</a>
                        <a href="#">Link 2</a>
                        <a href="#">Link 3</a>
                    </div>
                </div>
            </div>
            <div class="main_main">
                <section class="dates_section">
                    <a class="dates_button previous" href=""></a>
                    <ul class="main_dates-liste">
                        <li class="dates_liste-item">
                            <p class="dates_list-jour">Lun</p>
                            <p class="dates_list-date">11</p>
                            <p class="dates_list-mois">Juillet</p>
                        </li>
                        <li class="dates_liste-item">
                            <p class="dates_list-jour">Mar</p>
                            <p class="dates_list-date">12</p>
                            <p class="dates_list-mois">Juillet</p>
                        </li>
                        <li class="dates_liste-item dates_item-active">
                            <p class="dates_list-jour">Mer</p>
                            <p class="dates_list-date">13</p>
                            <p class="dates_list-mois">Juillet</p>
                        </li>
                        <li class="dates_liste-item">
                            <p class="dates_list-jour">Jeu</p>
                            <p class="dates_list-date">14</p>
                            <p class="dates_list-mois">Juillet</p>
                        </li>
                    </ul>
                    <a class="dates_button next" href=""></a>
                </section>
                <section class="horaires_section">
                    <div class="artistes_section">
                        <div class="artistes_section-titre">
                            <h2 class="h2 main_artiste-titre">Ninkasi du Faubourg</h2>
                            <h3 class="h3 main_artiste-soustitre">801-811 rue Saint-Jean, Québec</h3>
                        </div>
                        <div class="artiste_horaire">
                            <img class="ariste_horaire-image" src="../liaisons/images/Image.png" alt="">
                            <div class="artiste_horaire-information">
                                <h4 class="h4 main_artiste-nom">Jah & I</h4>
                                <p class="style_artiste">Rap</p>
                                <p class="permissionSlip">Laisser Passer Requis</p>
                                <p class="artiste_horaire-time">18h00</p>
                            </div>
                        </div>
                        <div class="artiste_horaire">
                            <img class="ariste_horaire-image" src="../liaisons/images/Image.png" alt="">
                            <div class="artiste_horaire-information">
                                <h4 class="h4 main_artiste-nom">Jah & I</h4>
                                <p class="style_artiste">Rap</p>
                                <p class="permissionSlip">Laisser Passer Requis</p>
                                <p class="artiste_horaire-time">18h00</p>
                            </div>
                        </div>
                        <div class="artiste_horaire">
                            <img class="ariste_horaire-image" src="../liaisons/images/Image.png" alt="">
                            <div class="artiste_horaire-information">
                                <h4 class="h4 main_artiste-nom">Jah & I</h4>
                                <p class="style_artiste">Rap</p>
                                <p class="permissionSlip">Laisser Passer Requis</p>
                                <p class="artiste_horaire-time">18h00</p>
                            </div>
                        </div>
                        <div class="artiste_horaire">
                            <img class="ariste_horaire-image" src="../liaisons/images/Image.png" alt="">
                            <div class="artiste_horaire-information">
                                <h4 class="h4 main_artiste-nom">Jah & I</h4>
                                <p class="style_artiste">Rap</p>
                                <p class="permissionSlip">Laisser Passer Requis</p>
                                <p class="artiste_horaire-time">18h00</p>
                            </div>
                        </div>
                    </div>
                    
                </section>
            </div>
        </main>
        <?php include($niveau . "liaisons/fragments/piedDePage.inc.php") ?>
    </body>
</html>
