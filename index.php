<?php
    session_start();
    include ("functions.inc.php");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Cocktails</title>
        <meta charset="utf-8" />
        <link type="text/css"  rel="stylesheet" href="style.css" />
    </head>

    <main>
        <?php
            if (isset($_SESSION['connectedUser']) && $_SESSION['connectedUser'])
                echo getHeader(true);
            else
                echo getHeader(false);
        ?>

    </main>
</html>

<?php
    include ("config.inc.php");

    if (isset($_GET['p']))  {
        if ($_GET['p'] == "install") // l'utilisateur veut installer la base de donnees
            include ("install.php");
        else if ($_GET['p'] == "onDisconnect") {
            session_destroy();
            header("Refresh: 0; url=index.php");
        } else if ($_GET['p'] == "recipesList")
            include ("recipesList.php");
        else if ($_GET['p'] == "recipeDetails")
            include ("recipeDetails.php");
        else if ($_GET['p'] == "recipeByIngredient")
            include ("recipeByIngredient.php");
        else if ($_GET['p'] == "foodsList")
            include ("foodsList.php");
        else if ($_GET['p'] == "favoritesUsersRecipes")
            include ("favoritesUsersRecipes.php");
    }
?>