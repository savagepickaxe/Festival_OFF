<header class="entete">
  

<nav class="menu" id="menu">

<div class="toggle-bouton">
    <button class="burger" onclick="this.classList.toggle('active')" id="burger"></button>
</div>


    <ul class="menu__liste hidden">
        <li class="menu__listeItem"><a href="<?php echo $niveau;?>index.php" class="menu__lien">Accueil</a></li>
        <li class="menu__listeItem"><a href="<?php echo $niveau;?>programmation/index.php" class="menu__lien">Programmation</a></li>
        <li class="menu__listeItem"><a href="<?php echo $niveau;?>artistes/index.php" class="menu__lien">Liste des artistes</a></li>
        <li class="menu__listeItem"><a href="<?php echo $niveau;?>lieux/index.php" class="menu__lien">Lieux du festival</a></li>
    </ul>
</nav>

</header>  