<?php

require('header.php');

$photos = [];

array_push($photos, ['photo' => file_get_contents('sunsetWithAntelope.jpg'), 'caption' => 'A photo of an antelope with a sunset in the background', 'user_id' => 2]);

array_push($photos, ['photo' => file_get_contents('cat.jpg'), 'caption' => 'A cat', 'user_id' => 1]);

array_push($photos, ['photo' => file_get_contents('colourful.jpeg'), 'caption' => 'A colourful photo', 'user_id' => 1]);

foreach ($photos as $photo) {
    $stmt = $pdo->prepare('INSERT INTO photos(photo, caption, user_id) VALUES(:photo, :caption, :user_id)');
    $stmt->execute($photo);
}
