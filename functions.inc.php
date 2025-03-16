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
                    <li><a href='index.php?p=onDisconnect'>Se déconnecter</a></li>";
    } else {
        $htmlCode .= "<li><a href='log.php?p=onLogIn'>connexion</a></li>
                     <li><a href='log.php?p=onSignIn'>inscription</a></li>";
    }
    $htmlCode .= "<li><a href='?p=install'>installation</a></li>
                   <li><a href='?p=recipesList'>liste des recettes</a></li>
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

    $compteur = 0; // on affiche 6 ingredients max
    foreach ($ingredients_separes as $indice => $ingredient)
    {
        if ($compteur >= 6)
            break;
        $ingredient_decomposes = explode(" ", $ingredient);
        // a cause de la complexite du pattern a detecte, on commence l'analyse de la chaine par la fin
        $dernier_indice_decomposition = count ($ingredient_decomposes) - 1;

        $termine = false;
        $indice_courant = $dernier_indice_decomposition;
        $composant_ingredient = "";
        while (!$termine && $compteur<6 && $indice_courant >= 0)
        {
            $mot_courant = $ingredient_decomposes[$indice_courant];

            if (preg_match("/cl|[0-9]+|quelques/",$mot_courant))
                // alors on a trouve le composant de l'ingredient on doit arreter la boucle
                $termine = true;
            else if ($mot_courant == "de"){
                // pour de et d' il y a un test supplementaire a realiser
                // il y a forcement avant
                if ($indice_courant == 0)
                    return ""; // il y a un probleme
                // on regarde le mot d'avant
                if (preg_match ("/cl|café|g|soupe|c\.|l|verre(s{0,1})|[0-9]+(\/[0-9]*){0,1}/",$ingredient_decomposes[$indice_courant-1]))
                {
                    $termine = true; // il faut s'arreter
                }
                else
                    // sinon on ajoute le de pour la comprehension
                    $composant_ingredient = $mot_courant." ".$composant_ingredient;

            }
            else if (preg_match("/^(d')/",$mot_courant)){ // sinon si le mot courant commence par d'
                // on doit d'abord tester le mot d'avant
                if ($indice_courant == 0)
                    return ""; // il y a un probleme

                if (preg_match ("/cl|café|g|soupe|c\.|l|verre(s{0,1})|[0-9]+(\/[0-9]*){0,1}/",$ingredient_decomposes[$indice_courant-1])){
                    $termine = true; // il faut s'arreter
                    $composant_ingredient = substr($mot_courant,2,strlen($mot_courant))." ".$composant_ingredient;
                }
                else
                    // sinon on ajoute le d' pour la comprehension
                    $composant_ingredient = $mot_courant." ".$composant_ingredient;

            } else { // le mot courant fait partie du composant de l'ingredient
                $composant_ingredient = $mot_courant." ".$composant_ingredient;
            }
            --$indice_courant;
        }
        // on ajoute le composant en enlevant les \ et en rajoutant une majuscule
        $temp =  ucfirst(str_replace("\\","",$composant_ingredient));
        $element_html .= "<a href='index.php?p=recipeByIngredient&name=".$temp."'>".$temp."</a>, ";
        ++$compteur;
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
?>