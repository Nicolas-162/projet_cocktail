<?php
    session_start();
    include ("functions.inc.php");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title></title>
        <meta charset="utf-8" />
    </head>

    <main>
        <?php
            if (isset($_SESSION['connectedUser']) && $_SESSION['connectedUser'])
                echo getHeader(true);
            else
                echo getHeader(false);
        ?>

        <a href="?p=install">Créer la base de données</a>

    </main>
</html>

<?php
    include ("config.inc.php");

    if (isset($_GET['p']))  {
        if ($_GET['p'] == "install")
            include ("install.php");
        else if ($_GET['p'] == "onDisconnect")
            session_destroy();
    }
?>