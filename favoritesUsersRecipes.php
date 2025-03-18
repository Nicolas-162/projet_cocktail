<?php

$mysqli = mysqli_connect($host, $user, $pass, $base);
mysqli_set_charset($mysqli, 'utf8'); // pour eviter les problemes d'encodage


$request = "SELECT favorites.user_id as user_id, favorites.recipesNumber AS nb, favorites.login AS login, favorite_recipe.recipe_id, recette.titre AS titre
            FROM (
                SELECT favorite_recipe.user_id, favorite_recipe.recipe_id, count(*) AS recipesNumber, user.login AS login
                    FROM favorite_recipe, user	
                    WHERE favorite_recipe.user_id = user.id
                    GROUP BY user_id
                 ) favorites, favorite_recipe, recette
            WHERE favorites.user_id = favorite_recipe.user_id
                AND favorite_recipe.recipe_id = recette.id
            ORDER BY login";

$res =query ($mysqli,$request);

echo "<h2>Liste des utilisateurs :</h2>";
echo "<ul>";
if (!($row = mysqli_fetch_array($res)))
    echo "Aucun résultat.";
else {
    $userid = $row['user_id'];
    echo "<li><em>" . $row['login'] . "</em>, Recettes préférées (" . $row['nb'] . ") : " . $row['titre'];
    do {
        $end = false;
        while (!$end && $row = mysqli_fetch_assoc($res)) {
            if ($row['user_id'] == $userid)
                echo " ; " . $row['titre'];
            else {
                $userid = $row['user_id'];
                $end = true;
                echo "</li>";
                echo "<li><em>" . $row['login'] . "</em>, Recettes préférées (" . $row['nb'] . ") : " . $row['titre'];

            }
        }

    } while ($row);
}
echo "</ul>";

mysqli_close($mysqli);
?>