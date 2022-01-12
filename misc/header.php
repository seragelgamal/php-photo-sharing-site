<?php

$pdo = new PDO('mysql:host=localhost;dbname=photo_sharing', 'photosharing', 'photosharing');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html>

<head>
  <title>Photo Sharing</title>
</head>

<body>
  <a href="index.php" style="text-decoration: none; color: black">
    <h1>Photo Sharing</h1>
  </a>
  <hr>