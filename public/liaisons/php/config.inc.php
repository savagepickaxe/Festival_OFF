<?php

// Verifier si l'exécution se fait sur le serveur de développement (local) ou celui de la production:
if (stristr($_SERVER['HTTP_HOST'], 'local') || (substr($_SERVER['HTTP_HOST'], 0, 7) == '192.168')) {
    $blnLocal = TRUE;
} else {
    $blnLocal = FALSE;
}

//var_dump($_SERVER['HTTP_HOST']);

// Selon l'environnement d'exécution (développement ou en ligne)
if ($blnLocal) {
    //Local
    $strHost = 'localhost';
    $strBD='rpni1_projet1_off';
    $strUser = 'rpni1_projet1_off';
    $strPassword= 'rpni1_projet1_off';
    error_reporting(E_ALL);
} else {
    //en ligne
    $strHost = 'localhost';
    $strBD='20_rpni1_OFF';
    $strUser = '20_rpni1_user';
    $strPassword = '20_rpni1_mdp';
    error_reporting(E_ALL & ~E_NOTICE);
    //error_reporting(0);
}

//Data Source Name pour l'objet PDO
$strDsn = 'mysql:dbname='.$strBD.';host='.$strHost;
//Tentative de connexion
$pdoConnexion = new PDO($strDsn, $strUser, $strPassword);
//Changement d'encodage de l'ensemble des caractères pour UTF-8
$pdoConnexion->exec("SET CHARACTER SET utf8");
//Pour obtenir des rapports d'erreurs et d'exception avec errorInfo()
$pdoConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$pdoConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

?>