<?php

require('misc/header.php');

if (isset($_GET['photoId']) && $_GET['photoId'] != '') {
  // get the photo with the given id
  $stmt = $pdo->query('SELECT * FROM photos WHERE id=:id');
  $photos = $stmt->fetchAll();

  // get the photo's comments
  
} else {
  header('Location: index.php');
}
