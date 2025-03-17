<?php

    $mysqli = mysqli_connect($host, $user, $pass, $base);
    mysqli_set_charset($mysqli, 'utf8'); // pour eviter les problemes d'encodage

    $order = "ORDER BY ";
    $paramDesc = "";
    if (empty($_GET['orderName']))
        $order .= "name";
    else {
        $order .= mysqli_real_escape_string($mysqli, $_GET['orderName']);
    } if (empty($_GET['paramDesc'])){
        $paramDesc = "DESC";
        if ($order != "name")
            $order .=  " DESC";
    } else
        $paramDesc .= "";


    $res = query($mysqli,"SELECT  * FROM aliment ".$order); // on recupere la liste des ingredients
    // recuperation de la liste des aliments
    mysqli_close($mysqli);

    echo "<table>";
    echo "<tr>
            <th><a href='?p=foodsList&orderName=count&paramDesc=".$paramDesc."'>Nombre de recettes</a></th>
            <th><a href='?p=foodsList&paramDesc=".$paramDesc."'>Aliment</a></th>
        </tr>";
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
            <td>".$row['count']."</td>
            <td>".$row['name']."</td>
        </tr>";
    }
    echo "</table>";

?>