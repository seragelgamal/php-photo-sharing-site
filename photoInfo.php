<?php

session_start();

require('misc/header.php');

// FUNCTIONS
// return an array of all the errors found for an admin-entered username
function usernameErrorArray(string $usernameVariable) {
  $errorArray = [];
  if (!pushErrorIfBlank($usernameVariable, $errorArray, 'Username')) {
    $usernameVariable = trim($usernameVariable);
    if (str_contains($usernameVariable, ' ')) {
      array_push($errorArray, "Username can't contain spaces");
    }
  }
  return $errorArray;
}

$nameErrors = $commentErrors = [];

if (isset($_GET['photoId']) && $_GET['photoId'] != '') {
  // get the photo with the given id
  $stmt = $pdo->query("SELECT * FROM photos WHERE id = {$_GET['photoId']}");
  $photo = $stmt->fetch();

  // get the photo's comments
  $stmt = $pdo->query("SELECT * FROM comments WHERE photo_id = {$_GET['photoId']}");
  $comments = $stmt->fetchAll();

  // get the poster's username
  $stmt = $pdo->query("SELECT * FROM user_credentials WHERE id = {$photo->user_id}");
  $poster = $stmt->fetchColumn(1);

  // var_dump($photo);
  // var_dump($comments);
  // var_dump($poster);
} else {
  header('Location: index.php');
}

// form action
// if (isset($_POST['submit'])) {
//   $username = $_POST['name'];
//   $nameErrors = usernameErrorArray()
// }

?>

<h2>Photo Info</h2>
<img src="data:image/jpeg;base64, <?= base64_encode($photo->blob) ?>" width="1000px" style="display: block; margin: auto;">
<?php if ($photo->caption != '') { ?>
  <p style="text-align: center;"><?= $photo->caption ?></p>
<?php } ?>
<p style="text-align: center; text-decoration: underline;">Posted by: <b><?= $poster ?></b></p>
<p style="text-align: center; text-decoration: underline;">Posted on: <b><?= $photo->uploaded_at ?></b></p>
<hr>

<h2>Comments</h2>
<h3>Add a comment:</h3>
<form action="photoInfo.php">
  <?= echoErrors($nameErrors) ?>
  <p>Name: <input type="text" name="name" placeholder="Type name here..." maxlength="20"></p>
  <?= echoErrors($commentErrors) ?>
  <textarea name="comment" placeholder="Type a comment here..." cols="70" rows="10" maxlength="500" style="font-family: 'Times New Roman', Times, serif;"></textarea>
  <p><input type="submit" name="submit" value="Post"></p>
</form>
<hr>
<?php if (sizeof($comments) > 0) {
  foreach ($comments as $comment) {
    // get commenter username
    $stmt = $pdo->query("SELECT * FROM user_credentials WHERE id = $comment->user_id");
    $commenter = $stmt->fetchColumn(1); ?>
    <div>
      <h3 style="display: inline;"><b><?= $commenter ?></b></h3> (<?= $comment->posted_at ?>)
      <p><?= $comment->text ?></p>
    </div>
  <?php }
} else { ?>
  <h3 style="text-align: center;">No comments have been posted yet. Be the first commenter by posting a comment above</h3>
<?php }

require('misc/footer.php');

?>