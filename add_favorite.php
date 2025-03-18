<?php
   session_start();
   if (!isset($_SESSION['connectedUser'])) {
      die("Unauthorized access");
   }

   require 'config.inc.php'; // Create this file with your DB credentials

   $mysqli = mysqli_connect($host, $user, $pass, $base);

   if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
   }

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $recipe_id = (int)$_POST['recipe_id'];
      $user_id = $_SESSION['user_id'];

      // Check if already favorited
      $stmt = $mysqli->prepare("
         SELECT COUNT(*) FROM favorite_recipe 
         WHERE user_id = ? AND recipe_id = ?
      ");
      $stmt->bind_param("ii", $user_id, $recipe_id);
      $stmt->execute();
      $stmt->bind_result($count);
      $stmt->fetch();
      $stmt->close();

      if ($count === 0) {
         // Insert new favorite
         $insert = $mysqli->prepare("
               INSERT INTO favorite_recipe (user_id, recipe_id) 
               VALUES (?, ?)
         ");
         $insert->bind_param("ii", $user_id, $recipe_id);
         
         if ($insert->execute()) {
               // Success
         } else {
               die("Error: " . $insert->error);
         }
         $insert->close();
      }
   }

   $mysqli->close();
   header("Location: " . $_SERVER['HTTP_REFERER']);
   exit();
?>