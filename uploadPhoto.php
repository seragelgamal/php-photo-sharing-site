<?php

session_start();

require('misc/header.php');

$fileErrors = [];

// form action
if (isset($_POST['upload']) && $_POST['upload'] == 'Upload') {
  // make sure a file was uploaded (error code 4 = no file was uploaded)
  if ($_FILES['file']['error'] == 4) {
    array_push($fileErrors, 'No file uploaded');
  } else if (!str_contains($_FILES['file']['type'], 'image/')) {
    // make sure the file uploaded is an image
    array_push($fileErrors, 'Invalid file type. Please upload an image file');
  }
  $caption = $_POST['caption'];

  if (sizeof($fileErrors) == 0) {
    $photoBlob = file_get_contents($_FILES['file']['tmp_name']);
    $fileType = explode('/', $_FILES['file']['type'])[1];
    // upload image
    $stmt = $pdo->prepare("INSERT INTO photos (photo_blob, file_type, caption, user_id) VALUES (:photoBlob, :fileType, :caption, :userId)");
    $stmt->execute(['photoBlob' => $photoBlob, 'fileType' => $fileType, 'caption' => $caption, 'userId' => $_SESSION['userId']]);
    header('Location: index.php');
    exit;
  }
}

?>

<h2>Upload a Photo</h2>
<p>Posting as <b><?= $_SESSION['username'] ?></b></p>
<form enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
  <?= echoErrors($fileErrors) ?>
  <h3>Select image file: <input type="file" accept="image/*" name="file"></h3>
  <h3>Caption: <span style="font-weight: 500; font-size: 12pt">(optional)</span></h3>
  <textarea name="caption" cols="70" rows="10" placeholder="Write your caption here..." style="font-family:'Times New Roman', Times, serif;"></textarea>
  <p><input type="submit" name="upload" value="Upload"></p>
</form>

<?php require('misc/footer.php'); ?>