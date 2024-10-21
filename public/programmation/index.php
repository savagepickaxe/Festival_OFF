<?php $niveau="../";?>
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
