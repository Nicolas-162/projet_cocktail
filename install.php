<?php
    include ($filename);
    $script = "
        DROP DATABASE IF EXISTS ".$base.";
        CREATE DATABASE IF NOT EXISTS ".$base.";
        
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
    `titre` varchar(50) NOT NULL,
    `ingredients` varchar(200) NOT NULL,
    `preparation` varchar(200) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `recette`
--
    ALTER TABLE `recette`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `titre` (`titre`);
    COMMIT;
    
    --
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(3) NOT NULL,
  `login` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `forename` varchar(20) NOT NULL,
  `age` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;


    INSERT INTO recette  (id, titre, ingredients, preparation) VALUES 
    ";

    $mysqli = mysqli_connect($host, $user, $pass, $base)
    or die ("Erreur de connexion : ".mysqli_error($mysqli));


    $borne = count($Recettes)-1;
    for ($i= 0 ; $i<$borne ; ++$i)
        $script .= "(".$i.",'".mysqli_real_escape_string($mysqli,$Recettes[$i]['titre'])."','".mysqli_real_escape_string($mysqli,$Recettes[$i]['ingredients'])."','".mysqli_real_escape_string($mysqli,$Recettes[$i]['preparation'])."'),";
    $script .= "(".$borne.",'".mysqli_real_escape_string($mysqli,$Recettes[$borne]['titre'])."','".mysqli_real_escape_string($mysqli,$Recettes[$borne]['ingredients'])."','".mysqli_real_escape_string($mysqli,$Recettes[$borne]['preparation'])."');";
    $script .= "COMMIT;
    /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
    /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
    /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
    ";


    $res = multi_query($mysqli,$script.$base);
    mysqli_close($mysqli);
    echo "<span class='info-block'>Chargement réussie </span>";
?>