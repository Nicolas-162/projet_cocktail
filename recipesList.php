<?php

    session_start();

    $mysqli = mysqli_connect($host, $user, $pass, $base);
    mysqli_set_charset($mysqli, 'utf8'); // pour eviter les problemes d'encodage


    $res = query($mysqli, "SELECT id,titre,ingredients FROM recette");

    echo "Toutes les recettes : ";
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($res)) {
        echo  "<li><a href='?p=recipeDetails&id=".$row['id']."'>".$row["titre"]." :</a> ";
        echo liste_ingredients_recette_vers_elm_html($row["ingredients"]);

        // Add this right after echo liste_ingredients_recette_vers_elm_html(...);
        if (isset($_SESSION['connectedUser'])) { // Check if user is logged in
            echo "<form style='display:inline;' method='post' action='add_favorite.php'>
                    <input type='hidden' name='recipe_id' value='{$row['id']}'>
                    <button type='submit' style='background:none;border:none;color:#f00;cursor:pointer;'>🖤</button>
                </form></li>";
        } else {
            echo "<a href='login.php' style='color:#00f;text-decoration:underline;'>Login to favorite</a></li>";
        }

    }
    echo "</ul>";
    mysqli_close($mysqli);

?>