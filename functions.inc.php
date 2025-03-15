<?php

/******************  SQL FUNCTIONS *********************/
function query($link,$requete)
{
    $resultat=mysqli_query($link,$requete) or die("$requete : ".mysqli_error($link));
    return($resultat);
}
function multi_query($link,$requete)
{
    $resultat=mysqli_multi_query($link,$requete) or die("$requete : ".mysqli_error($link));
    return($resultat);
}




/****************************** OTHER ***************************************/


/**
 * @brief ne prend un parametre booleen. Retourne l'entete correspondant au parametre.
 * Le booleen represente si l'utilisateur est connecte ou pas
 * @param $isConnected : bool, true si l'utilisateur est connecte, false sinon
 * @return string : l'entete
 */
function getHeader($isConnected){
    $htmlCode = "<header>";
    if ($isConnected){
        $htmlCode .= "<a href='log.php?p=onAccount'>Accéder aux paramètres de compte</a>
                    <a href='index.php?onDisconnect'>Se déconnecter</a>'";
    } else {
        $htmlCode .= "<a href='log.php?p=onLogIn'>Se connecter</a>
                     <a href='log.php?p=onSignIn'>S'inscrire</a>";
    }
    $htmlCode .= "</header>";
    return $htmlCode;
}


?>