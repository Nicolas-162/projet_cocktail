<?php

$erreur = "Un problème est survenue. <a href='index.php'>Retourner à l'acceuil</a>";
if (!isset($_GET['p']))
    echo $erreur;
else {
    switch ($_GET['p']) {
        case "onLogIn":
            echo getFormLogIn();
            break;
        case "onSignUp":
            echo getFormSignUp();
            break;
        case "onAccount":
            echo getFormAccount();
            break;
        default:
            echo $erreur;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Connexion</title>
        <meta charset="utf-8" />
    </head>

    <body>
    </body>
</html>


<?php
function getFormLogIn(){
    $htmlCode = "<h1>Connexion</h1>
                <form method='post' action='index.php?p=log'>
                    <fieldset>
                        <legend>Connexion</legend>
                        <label for='1'>Login : </label>
                        <input type='text' id='1' name='login' required='required'/>
                        <br />
                        <label for='2'>Mot de passe : </label>
                        <input type='password' id='2' name='password' required='required'/>
                        <br />
                    </fieldset>
                    <input type='submit' value='Se connecter' />
                </form> 
                Pas de compte ?  <a href='log.php?p=onSignUp'>S'inscrire</a>";
    $_SESSION['connectedUser'] = true;
    return $htmlCode;
}
function getFormSignUp(){
    $htmlCode = "<h1>S'inscrire</h1>
                <form method='post' action='index.php?p=log'>
                    <fieldset>
                        <legend>Inscription</legend>
                        <label for='1'>Login : </label>
                        <input type='text' id='1' name='login' required='required'/>
                        <br />
                        <label for='2'>Mot de passe : </label>
                        <input type='password' id='2' name='password' required='required'/>
                        <br />
                        <label for='3'>Prénom : </label>
                        <input type='text' id='3' name='forename' required='required'/>
                        <br />
                        <label for='4'>Âge : </label>
                        <input type='text' id='4' name='age' required='required'/>
                    </fieldset>
                    <input type='submit' value='S`inscrire' />
                </form> ";
    $_SESSION['connectedUser'] = true;
    return $htmlCode;
}
function getFormAccount(){
    $htmlCode = "<h1>Informations de compte</h1>
                <form method='post' action='index.php?p=log'>
                <fieldset>
                    <legend>Modifier des informations</legend>
                    <label for='1'>Mot de passe : </label>
                    <input type='password' id='1' name='password'/> 
                    <br />
                    <label for='2'>Prénom : </label>
                    <input type='input' id='2' name='forename'/>
                    <br />
                    <label for='3'>Âge</label>
                    <input type='input' id='3' name='age'/>
                </fieldset>
                <input type='submit' value='Appliquer les modifications' />
                </form>";
    return $htmlCode;
}

?>