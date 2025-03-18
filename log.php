<?php

session_start();

if (isset($_GET['p'])){
    switch ($_GET['p']) {
        case "onLogIn":
            echo getFormLogIn();
            break;
        case "onSignUp":
            echo getFormSignIn();
            break;
        case "onAccount":
            echo getFormAccount();
            break;
        default:
            include("error.php");
    }
} else if (isset($_POST['logParameter'])) {

    include ("config.inc.php");
    include ("functions.inc.php");

    $mysqli = mysqli_connect($host, $user, $pass, $base)
    or die ("Erreur de connexion : " . mysqli_error($mysqli));

    if ($_POST['logParameter'] == "accountData"){
        // pas besoin de verifier que le compte existe deja
        $params = array();
        if (!empty($_POST['password'])){
            $params['password'] = mysqli_real_escape_string($mysqli,$_POST['password']);
        }
        if (!empty ($_POST['forename'])){
            $params['forename'] = mysqli_real_escape_string($mysqli,$_POST['forename']);
        }
        if (!empty($_POST['age'])){
            $params['age'] = mysqli_real_escape_string($mysqli,$_POST['age']);
        }

        if (count ($params) > 0) {
            $fields = "";
            $values = "";
            $request = "UPDATE user SET ";
            foreach ($params as $key => $value) {
                $fields .= $key . ",";
                $values .= "'" . $value . "',";

                $request.= $key."='$value',";
            }
            $request = substr($request, 0, strlen($request) - 1);
            $request .= " WHERE id=".$_SESSION['user']['id'];

            query ($mysqli, $request);

            if (mysqli_affected_rows($mysqli) > 0)
                echo "Les modifications ont été appliquées avec succès";
            else echo "Aucune modifiation effectuées";
            echo "<a href='index.php'>Retourner à l'accueil</a>";
        } else echo "Aucune modification effectuées. <a href='index.php'>Retourner à l'accueil</a>";
    }
    else{
            // l'utilisateur desire se connecter a son compte
            if (!isset($_POST["login"]) || !isset($_POST["password"]))
                include("error.php");

            else {
                $login = mysqli_real_escape_string($mysqli, $_POST["login"]);
                $passwd = mysqli_real_escape_string($mysqli, $_POST["password"]);

                if ($_POST['logParameter'] == "logIn") {
                    $query = "SELECT * FROM user WHERE login='$login' AND password='$passwd'";
                    $res = query($mysqli, $query);

                    if (mysqli_num_rows($res) == 0) {
                        echo "Identifiant ou mot de passe incorrect, ou compte inexistant"; // id ou pwd incorrect !

                        echo getFormLogIn();
                    } else {
                        $row = mysqli_fetch_assoc($res);
                        $_SESSION["user"] = $row;
                        $_SESSION["connectedUser"] = true;
                        echo "Authentification reussie, content de vous revoir " . $row['forename'] . ".";
                        echo "<a href='index.php'>Retourner à l'accueil</a>";
                    }
                } else if ($_POST['logParameter'] == "signIn") {
                    if (!isset($_POST["forename"]) || !isset($_POST["age"]))
                        include("error.php");
                    else {
                        $forename = mysqli_real_escape_string($mysqli, $_POST["forename"]);
                        $age = mysqli_real_escape_string($mysqli, $_POST["age"]);

                        $request = "INSERT INTO user (login, password, forename, age) VALUES ('".$login."', '".$passwd."', '".$forename."', ".$age.")";
                        query($mysqli, $request);

                        if (mysqli_affected_rows($mysqli) == 0) {
                            echo "Echec de la creation de votre compte veuillez recommencer.";

                            echo getFormSignIn();
                        } else {
                            $_SESSION["user"] = array( 'login' => $login, 'password' => $passwd, 'forename' => $forename, 'age' => $age);
                            $_SESSION["connectedUser"] = true;
                            echo "Authentification reussie, bienvenu(e) " . $forename . ".";
                            echo "<a href='index.php'>Retourner à l'accueil</a>";
                        }
                    }
            }
                else
                    include ("error.php");
        }
    }

    mysqli_close($mysqli);


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
                <form method='post' action='log.php'>
                    <fieldset>
                        <legend>Connexion</legend>
                        <label for='1'>Login : </label>
                        <input type='text' id='1' name='login' required='required' pattern='".getRegexLogin()."' />
                        <br />
                        <label for='2'>Mot de passe : </label>
                        <input type='password' id='2' name='password' required='required'/>
                        <br />
                    </fieldset>
                    <input type='submit' value='Se connecter' />
                    <input type='text' name='logParameter' value='logIn' hidden='hidden'/>
                </form> 
                Pas de compte ?  <a href='log.php?p=onSignUp'>S'inscrire</a>";
    $_SESSION['connectedUser'] = true;
    return $htmlCode;
}
function getFormSignIn(){
    return "<h1>S'inscrire</h1>
                <form method='post' action='log.php'>
                    <fieldset>
                        <legend>Inscription</legend>
                        <label for='1'>Login : </label>
                        <input type='text' id='1' name='login' pattern='".getRegexLogin()."' required='required'/>
                        <br />
                        <label for='2'>Mot de passe : </label>
                        <input type='password' id='2' name='password' required='required'/>
                        <br />
                        <label for='3'>Prénom : </label>
                        <input type='text' id='3' name='forename' pattern='".getRegexForename()."' required='required'/>
                        <br />
                        <label for='4'>Âge : </label>
                        <input type='text' id='4' name='age' pattern='".getRegexAge()."' required='required'/>
                    </fieldset>
                    <input type='submit' value='S`inscrire' />
                     <input type='text' name='logParameter' value='signIn' hidden='hidden'/>

                </form> ";
}
function getFormAccount(){
    return "<h1>Informations de compte</h1>
                <form method='post' action='log.php'>
                <fieldset>
                    <legend>Modifier des informations</legend>
                    <label for='1'>Mot de passe : </label>
                    <input type='password' id='1' name='password'/> 
                    <br />
                    <label for='2'>Prénom : </label>
                    <input type='input' id='2' pattern='".getRegexForename()."' name='forename'/>
                    <br />
                    <label for='3'>Âge</label>
                    <input type='input' id='3' pattern='".getRegexAge()."' name='age'/>
                </fieldset>
                <input type='submit' value='Appliquer les modifications' />
                <input type='text' name='logParameter' value='accountData' hidden='hidden'/>

                </form>";
}



function getRegexLogin(){
    return "[A-Za-z0-9]+";
}
function getRegexForename(){
    return "[A-Za-zéèàâêîôûãõäëüïö\- ]+";
}
function getRegexAge(){
    return "[0-9]{1,3}";
}
?>