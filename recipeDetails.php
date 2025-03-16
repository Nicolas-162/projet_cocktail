<?php

if (!isset($_GET['id'])) {
    include ("error.php");
} else {

    $mysqli = mysqli_connect($host, $user, $pass, $base);
    mysqli_set_charset($mysqli, 'utf8'); // pour eviter les problemes d'encodage

    $res = query($mysqli, "SELECT id,titre,ingredients,preparation FROM recette WHERE id = " . mysqli_real_escape_string($mysqli,$_GET['id']));
    if (mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);

        echo "<h2>".$row['titre'] . " : </h2> ";
        echo "<h3>Liste des ingrédients : </h3>";
        echo getIngredientList($row['ingredients']);
        echo "</ul>";
        echo "<h3>Préparation :</h3> <p>".$row['preparation']."</p>";
    } else include ("error.php");

    mysqli_close($mysqli);

}

?>