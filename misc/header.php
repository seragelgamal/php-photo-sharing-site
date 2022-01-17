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

$pdo = new PDO('mysql:host=localhost;dbname=photo_sharing', 'photosharing', 'photosharing');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

// form action
if (isset($_POST['submit'])) {
  session_unset();
  session_destroy();
}

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
    if (isset($_SESSION['username']) && isset($_SESSION['pwd'])) { ?>
      <div style="text-align: center;">
        <p style="text-align: center;">Logged in as <b><?= $_SESSION['username'] ?></b></p>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <p><input type="submit" name="submit" value="Log out"></p>
        </form>
      </div>
    <?php } else { ?>
      <!-- if user isnt logged in -->
      <p style="text-align: center;"><a href="login.php?redirect=<?= $_SERVER['PHP_SELF'] ?>"><button>Log in</button></a>
        <a href="signup.php?redirect=<?= $_SERVER['PHP_SELF'] ?>"><button>Sign up</button></a>
      </p>
    <?php } ?>
  <?php } ?>

  <hr style="display: block;">