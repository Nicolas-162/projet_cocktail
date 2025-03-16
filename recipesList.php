<?php

    $mysqli = mysqli_connect($host, $user, $pass, $base);
    mysqli_set_charset($mysqli, 'utf8'); // pour eviter les problemes d'encodage


    $res = query($mysqli, "SELECT id,titre,ingredients FROM recette");

    echo "Toutes les recettes : ";
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($res)) {
        echo  "<li><a href='?p=recipeDetails&id=".$row['id']."'>".$row["titre"]." :</a> ";
        echo liste_ingredients_recette_vers_elm_html($row["ingredients"])."</li>";

    }
    echo "</ul>";
    mysqli_close($mysqli);

?>