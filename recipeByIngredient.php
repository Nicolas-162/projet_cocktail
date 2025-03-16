<?php

$mysqli = mysqli_connect($host, $user, $pass, $base);
mysqli_set_charset($mysqli, 'utf8'); // pour eviter les problemes d'encodage

if (!isset($_GET['name'])) {
    include ("error.php");
} else {

    $ingredient = mysqli_real_escape_string($mysqli, $_GET['name']);
    $request = "SELECT * FROM recette WHERE ingredients LIKE '%".$ingredient."%'";
    $res = query($mysqli, $request);

    if (mysqli_num_rows($res) > 0) {
        echo "<h2>Toutes les recettes contenant l'ingrédient : ".$ingredient."</h2>";
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<li><a href='?p=recipeDetails&id=" . $row['id'] . "'>" . $row["titre"] . " :</a> ";
            echo liste_ingredients_recette_vers_elm_html($row["ingredients"]) . "</li>";

        }
        echo "</ul>";
    } else include("error.php");
}

mysqli_close($mysqli);


?>