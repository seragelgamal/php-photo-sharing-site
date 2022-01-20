<?php

session_start();

require('misc/header.php');

$nameErrors = $commentErrors = [];

if (isset($_GET['photoId']) && $_GET['photoId'] !== '') {
  // get the photo with the given id
  $stmt = $pdo->query("SELECT * FROM photos WHERE id = {$_GET['photoId']}");
  $photo = $stmt->fetch();

  // get the photo's comments
  $stmt = $pdo->query("SELECT * FROM comments WHERE photo_id = {$_GET['photoId']}");
  $comments = $stmt->fetchAll();

  // get the poster's username
  $stmt = $pdo->query("SELECT * FROM user_credentials WHERE id = {$photo->user_id}");
  $poster = $stmt->fetchColumn(1);
} else {
  header('Location: index.php');
}

// form action
if (isset($_POST['post']) && $_POST['post'] == 'Post') {
  $comment = $_POST['comment'];
  $commentErrors = captionCommentErrorArray($comment, 'Comment');

  if (sizeof($commentErrors) == 0) {
    $stmt = $pdo->prepare("INSERT INTO comments (photo_id, user_id, text) VALUES (:photoId, :userId, :text)");
    $stmt->execute(['photoId' => $_GET['photoId'], 'userId' => $_POST['commenterId'], 'text' => $_POST['comment']]);
    header('Refresh: 0');
    exit;
  }
}

?>

<h2>Photo Info</h2>
<img src="data:image/jpeg;base64, <?= base64_encode($photo->photo_blob) ?>" width="1000px" style="display: block; margin: auto;">
<?php if ($photo->caption != '') { ?>
  <p style="text-align: center;"><?= $photo->caption ?></p>
<?php } ?>
<p style="text-align: center; text-decoration: underline;">Posted by: <b><?= $poster ?></b></p>
<p style="text-align: center; text-decoration: underline;">Posted on: <b><?= $photo->uploaded_at ?></b></p>
<hr>

<h2>Comments</h2>
<h3>Add a comment:</h3>
<form action="<?= htmlspecialchars("{$_SERVER['PHP_SELF']}?photoId={$_GET['photoId']}") ?>" method="POST">
  <?php $commenter = $_SESSION['username'] ?? 'Guest'; ?>
  <p>Posting as <b><?= $commenter ?></b><?php if ($commenter == 'Guest') { ?> (not logged in) <?php } ?></p>
  <input type="hidden" name="commenterId" value="<?= $_SESSION['userId'] ?? 0 ?>">
  <?= echoErrors($commentErrors) ?>
  <textarea name="comment" placeholder="Type a comment here..." cols="70" rows="10" maxlength="500" style="font-family: 'Times New Roman', Times, serif;"></textarea>
  <p><input type="submit" name="post" value="Post"></p>
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