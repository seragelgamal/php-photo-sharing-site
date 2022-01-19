<?php

// MULTI-USE FUNCTIONS
// echoes errors from a specified error array with formatting
function echoErrors(array $errorArray) { ?>
  <p class="errors"><?php foreach ($errorArray as $error) {
                      echo ("$error <br>");
                    } ?></p>
<?php }
// pushes appropriate error if something is blank
function pushErrorIfBlank(mixed $input, array &$errorArray, string $fieldName) {
  if ($input == '') {
    array_push($errorArray, "$fieldName is required");
    return true;
  }
  return false;
}
// returns an array of errors for a user-entered photo caption or comment
function captionCommentErrorArray(string &$textVariable, string $fieldName) {
  $errorArray = [];
  $textVariable = trim($textVariable);
  if (strlen($textVariable) == 0) {
    array_push($errorArray, "$fieldName field is empty");
  }
  return $errorArray;
}

$pdo = new PDO('mysql:host=localhost;dbname=photo_sharing', 'photosharing', 'photosharing');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

// form action
// if (isset($_POST['logOut']) && $_POST['logOut'] == 'Log out') {

// }

?>

<!DOCTYPE html>
<html>

<head>
  <title>Photo Sharing</title>
  <link rel="stylesheet" href="misc/style.css">
</head>

<body>
  <a href="index.php" style="text-decoration: none; color: black">
    <h1 style="text-align: center;">Photo Sharing</h1>
  </a>
  <!-- run login/signup button code only if the page doesnt have a redirect set (aka if the user is on any page other than login/signup pages) -->
  <?php if (!isset($_GET['redirect'])) {
    // if user is logged in
    if (isset($_SESSION['username'])) { ?>
      <div style="text-align: center;">
        <p style="text-align: center;">Logged in as <b><?= $_SESSION['username'] ?></b></p>
        <!-- put the upload photo button if the user isnt already on the upload page -->
        <?php if ($_SERVER['PHP_SELF'] != '/php-photo-sharing-site/uploadPhoto.php') { ?>
          <p><a href="uploadPhoto.php"><button>Upload Photo</button></a></p>
        <?php } ?>
        <p>
        <form action="misc/logOut.php" method="post">
          <input type="submit" name="logOut" value="Log out">
        </form>
        </p>
      </div>
    <?php } else { ?>
      <!-- if user isnt logged in -->
      <p style="text-align: center;"><a href="login.php?redirect=<?= $_SERVER['PHP_SELF'] ?>"><button>Log in</button></a>
        <a href="signup.php?redirect=<?= $_SERVER['PHP_SELF'] ?>"><button>Sign up</button></a>
      </p>
    <?php } ?>
  <?php } ?>

  <hr style="display: block;">