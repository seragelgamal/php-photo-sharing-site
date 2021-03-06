<?php

session_start();

require('misc/header.php');

// get all photos from database
$stmt = $pdo->query('SELECT * FROM photos');
$photos = $stmt->fetchAll();

?>

<h2>Shared Photos</h2>
<?php if (sizeof($photos) > 0) { ?>
  <h3>Click on an individual photo for more information</h3>
  <?php foreach ($photos as $photo) { ?>
    <a href="photoInfo.php?photoId=<?= $photo->id ?>" style="text-decoration: none; color: none">
      <div style="display: inline-block; margin: 5px">
        <img src="data:image/<?= $photo->file_type ?>;base64, <?= base64_encode($photo->photo_blob) ?>" width="225px">
      </div>
    </a>
  <?php }
} else { ?>
  <h3>No one has uploaded photos yet. You can be the first by <a href="uploadPhoto.php">uploading your own photo</a></h3>
<?php }

require('misc/footer.php'); ?>