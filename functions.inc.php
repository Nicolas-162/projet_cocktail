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
    $htmlCode = "<header>
    <h1>The Cocktail Workshop</h1>
    <nav>";
    if ($isConnected){
        $htmlCode .= "<li><a href='log.php?p=onAccount'>Accéder aux paramètres de compte</a></li>
                    <li><a href='index.php?p=onDisconnect'>Se déconnecter</a></li>";
    } else {
        $htmlCode .= "<li><a href='log.php?p=onLogIn'>Se connecter</a></li>
                     <li><a href='log.php?p=onSignIn'>S'inscrire</a></li>";
    }
    $htmlCode .= "</nav></header>";
    return $htmlCode;
}


?>