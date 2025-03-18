<?php
    include ($filename);



    $script = "
        DROP DATABASE IF EXISTS ".$base.";
        CREATE DATABASE ".$base.";
        
        SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
        SET AUTOCOMMIT=0;
        START TRANSACTION;
        SET time_zone='+00:00';
        
        /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
        /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
        /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
        /*!40101 SET NAMES utf8mb4 */;
        
        COMMIT;
        
        USE ".$base.";
       
       COMMIT;

    CREATE TABLE `recette` (
    `id` int(3) UNSIGNED NOT NULL,
    `titre` varchar(100) NOT NULL,
    `ingredients` varchar(500) NOT NULL,
    `preparation` varchar(1000) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;


    ALTER TABLE `recette`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `titre` (`titre`);    
    
    COMMIT;

    CREATE TABLE `user` (
      `id` int(3) NOT NULL,
      `login` varchar(20) NOT NULL,
      `password` varchar(20) NOT NULL,
      `forename` varchar(20) NOT NULL,
      `age` int(3) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

    ALTER TABLE `user`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `login` (`login`);
    

    ALTER TABLE `user`
    MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;COMMIT;

    INSERT INTO recette  (id, titre, ingredients, preparation) VALUES 
    ";

    $mysqli = mysqli_connect($host, $user, $pass)
    or die ("Erreur de connexion : ".mysqli_error($mysqli));


    $borne = count($Recettes)-1;
    for ($i= 0 ; $i<$borne ; ++$i)
        $script .= "(".$i.",'".mysqli_real_escape_string($mysqli,$Recettes[$i]['titre'])."','".mysqli_real_escape_string($mysqli,$Recettes[$i]['ingredients'])."','".mysqli_real_escape_string($mysqli,$Recettes[$i]['preparation'])."'),";
    $script .= "(".$borne.",'".mysqli_real_escape_string($mysqli,$Recettes[$borne]['titre'])."','".mysqli_real_escape_string($mysqli,$Recettes[$borne]['ingredients'])."','".mysqli_real_escape_string($mysqli,$Recettes[$borne]['preparation'])."');";



$script .= "

    CREATE TABLE `aliment` (
       `id` int(3) NOT NULL,
       `name` varchar(100) NOT NULL,
       `count` int(3) UNSIGNED NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
    
    ALTER TABLE `aliment`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `name` (`name`);    

    ALTER TABLE `aliment`
    MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
    COMMIT;
    
INSERT INTO aliment (name,count) VALUES ";



    // recuperation de la liste des aliments
    $foodsList = array ();
    foreach ($Recettes as $id => $recette) {
        $ingredients = explode("|", $recette['ingredients']);
        foreach ($ingredients as $index => $ingredient) {
            $food = extractFood($ingredient);
            if ($food != "") {
                if (!array_key_exists($food, $foodsList))
                    $foodsList[$food] = 1;
                else
                    $foodsList[$food]++;
            }
        }
    }
    foreach ($foodsList as $name => $count) {
        $script .= "('".mysqli_real_escape_string($mysqli,$name)."',".mysqli_real_escape_string($mysqli,$count)."),";
    }
    $script = substr($script, 0, strlen($script) - 1);
    $script .= ";COMMIT;";

$script .="
        CREATE TABLE `favorite_recipe` (
            `user_id` int(3) NOT NULL,
            `recipe_id` int(3) UNSIGNED NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`user_id`, `recipe_id`),
            FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`recipe_id`) REFERENCES `recette`(`id`) ON DELETE CASCADE
        ) ENGINE=MyIsam ;";

        $script .= "COMMIT;
    /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
    /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
    /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
    ";

    $res = multi_query($mysqli,$script.$base);
    mysqli_close($mysqli);
    echo "<span class='info-block'>Chargement réussie </span>";
?>