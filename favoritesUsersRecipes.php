<?php

$mysqli = mysqli_connect($host, $user, $pass, $base);

$res =query ($mysqli,"SELECT * FROM user ORDER BY login");

echo "<h2>Liste des utilisateurs :</h2>";
echo "<ul>";
while ($row = mysqli_fetch_assoc($res)){
    echo "<li>".$row['login']."</li>";
}
echo "</ul>";

mysqli_close($mysqli);
?>