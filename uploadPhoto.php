<?php

session_start();

require('misc/header.php');



$fileErrors = $captionErrors = [];

// form action
if (isset($_POST['upload']) && $_POST['upload'] == 'Upload') {
  // make sure a file was uploaded (error code 4: no file was uploaded)
  if ($_FILES['file']['error'] == 4) {
    array_push($fileErrors, 'No file uploaded');
  } else if (!str_contains($_FILES['file']['type'], 'image/')) {
    // make sure the file uploaded is an image
    array_push($fileErrors, 'Invalid file type. Please upload an image file');
  }
  $caption = $_POST['caption'];
  $captionErrors = captionCommentErrorArray($caption, 'Caption');

  if (sizeof($fileErrors) == 0 && sizeof($captionErrors) == 0) {
    $blob = file_get_contents($_FILES['file']['tmp_name']);
    // upload image
    $stmt = $pdo->prepare("INSERT INTO photos (blob, caption, user_id) VALUES (:blob, :caption, :userId)");
    $stmt->execute(['blob' => $blob, 'caption' => $caption, 'userId' => $_SESSION['userId']]);
  }
}

?>

<h2>Upload a Photo</h2>
<p>Posting as <b><?= $_SESSION['username'] ?></b></p>
<form enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
  <?= echoErrors($fileErrors) ?>
  <h3>Select image file: <input type="file" accept="image/*" name="file"></h3>
  <h3>Caption:</h3>
  <?= echoErrors($captionErrors) ?>
  <textarea name="caption" cols="70" rows="10" placeholder="Write your caption here..." style="font-family:'Times New Roman', Times, serif;"></textarea>
  <p><input type="submit" name="upload" value="Upload"></p>
</form>