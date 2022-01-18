<?php

session_start();

require('misc/header.php');

?>

<h2>Upload a Photo</h2>
<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
  <p>Insert image file: <input type="file" name="photoFile">
  <p>Caption:</p>
  <p><textarea name="caption" cols="70" rows="10" placeholder="Write your caption here..."></textarea></p>
</form>