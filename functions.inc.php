<?php

/******************  SQL FUNCTIONS *********************/
function query($link,$requete)
{
    $resultat=mysqli_query($link,$requete) or die("$requete : ".mysqli_error($link));
    return($resultat);
}
function multi_query($link,$requete)
{
    $resultat=mysqli_multi_query($link,$requete) or die("$requete : ".mysqli_error($link));
    return($resultat);
}




/****************************** OTHER ***************************************/


/**
 * @brief ne prend un parametre booleen. Retourne l'entete correspondant au parametre.
 * Le booleen represente si l'utilisateur est connecte ou pas
 * @param $isConnected : bool, true si l'utilisateur est connecte, false sinon
 * @return string : l'entete
 */
function getHeader($isConnected){
    $htmlCode = "<header>
    <h1>The Cocktail Workshop</h1>
    <nav><ul>";
    if ($isConnected){
        $htmlCode .= "<li><a href='log.php?p=onAccount'>Modifier le profil</a></li>
                    <li><a href='index.php?p=foodsList'>Liste des aliments</a></li>
                    <li><a href='index.php?p=onDisconnect'>Se déconnecter</a></li>";
    } else {
        $htmlCode .= "<li><a href='log.php?p=onLogIn'>Connexion</a></li>
                     <li><a href='log.php?p=onSignUp'>Inscription</a></li>";
    }
    $htmlCode .= "<li><a href='?p=install'>Installation</a></li>
                   <li><a href='?p=recipesList'>Liste des recettes</a></li>
                   <li><a href='?p=favoritesUsersRecipes'>Accès aux recettes préférées de tous les utilisateurs</a></li>
    </ul></nav></header>";
    return $htmlCode;
}


/**
 * @brief Cette fonction prend en argument une chaine representant les ingredients d'une recette
 * et retourne le code html qui represente l'enumeration de ceux ci dans une liste
 *
 * @param $ingredients : string, la liste des ingredients
 * @return : string l'element html correspondant ou une chaine vide si erreur
 */

function liste_ingredients_recette_vers_elm_html($ingredients)
{
    $ingredients_separes = explode("|", $ingredients);
    $element_html = "";

    foreach ($ingredients_separes as $indice => $ingredient)
    {
        $food = extractFood($ingredient);
        $element_html .= "<a href='index.php?p=recipeByIngredient&name=".$food."'>".$food."</a>, ";
    }

    $element_html = substr($element_html,0,strlen($element_html)-1);
    return $element_html;
}

function getIngredientList($ingredients_string){
    $html_code = "<ul>";
    $liste_ingredients = explode("|", $ingredients_string);
    foreach ($liste_ingredients as $indice  => $ingredient) {
        $html_code .= "<li><a href='?p=recipeByIngredient&name=".$ingredient."'>" . $ingredient . "</a></li>";
    }
    $html_code .= "</ul>";
    return $html_code;
}

function extractFood($ingredient){
    $ingredient_decomposes = explode(" ", $ingredient);
    // a cause de la complexite du pattern a detecte, on commence l'analyse de la chaine par la fin
    $dernier_indice_decomposition = count ($ingredient_decomposes) - 1;

    $termine = false;
    $indice_courant = $dernier_indice_decomposition;
    $aliment = "";
    while (!$termine  && $indice_courant >= 0)
    {
        $mot_courant = $ingredient_decomposes[$indice_courant];

        if (preg_match("/cl|[0-9]+|quelques/",$mot_courant))
            // alors on a trouve le composant de l'ingredient on doit arreter la boucle
            $termine = true;
        else if ($mot_courant == "de") {
            // pour de et d' il y a un test supplementaire a realiser
            // il y a forcement avant
            if ($indice_courant == 0)
                $termine = true; // il y a un probleme
            // on regarde le mot d'avant
            else {
                if (preg_match("/[Ee]nviron|[Zz]est|[Uu]n peu de|[Dd]es|[Ss]irop|[Jj]us|cl|café|g|soupe|c\.|s\.|s\.de|l|verre(s{0,1})|[0-9]+(\/[0-9]*){0,1}/", $ingredient_decomposes[$indice_courant - 1])) {
                    $termine = true; // il faut s'arreter
                } else
                    // sinon on ajoute le de pour la comprehension
                    $aliment = $mot_courant . " " . $aliment;
            }
        }

        else if (preg_match("/^(d')/",$mot_courant)){ // sinon si le mot courant commence par d'
            // on doit d'abord tester le mot d'avant
            if ($indice_courant == 0)
                $termine = true; // il y a un probleme

            else {
                if (preg_match("/[Ee]nviron|[Zz]est|[Uu]n peu de|[Dd]es[|[Ss]irop|[Jj]us|cl|café|g|soupe|c\.|s\.|s\.de|l|verre(s{0,1})|[0-9]+(\/[0-9]*){0,1}/", $ingredient_decomposes[$indice_courant - 1])) {
                    $termine = true; // il faut s'arreter
                    $aliment = substr($mot_courant, 2, strlen($mot_courant)) . " " . $aliment;
                } else
                    // sinon on ajoute le d' pour la comprehension
                    $aliment = $mot_courant . " " . $aliment;
            }

        } else { // le mot courant fait partie du composant de l'ingredient
            $aliment = $mot_courant." ".$aliment;
        }
        --$indice_courant;
    }
    // on ajoute le composant en enlevant les \ et en rajoutant une majuscule
    return  ucfirst(str_replace("\\","",$aliment));
}
?>