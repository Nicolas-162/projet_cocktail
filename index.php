<!DOCTYPE html>
<html lang="fr">
    <head>
        <title></title>
        <meta charset="utf-8" />
    </head>

    <main>

        <a href="?p=install">Install</a>

    </main>
</html>

<?php

    include ("config.inc.php");

    if (isset($_GET['p']))  {
        include ("install.php");
    }
?>