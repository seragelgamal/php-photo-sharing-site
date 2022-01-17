<?php

session_start();

require('misc/header.php');

// FUNCTIONS:
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
// return an array of all the errors found for an admin-entered password
function pwdErrorArray(string $pwdVariable) {
  $errorArray = [];
  if (!pushErrorIfBlank($pwdVariable, $errorArray, 'Password')) {
    if ($pwdVariable[0] == ' ' || $pwdVariable[strlen($pwdVariable) - 1] == ' ') {
      array_push($errorArray, "Password can't start or end with a space");
    }
  }
  return $errorArray;
}

$signupErrors = $usernameErrors = $pwdErrors = [];

// form action
if (isset($_POST['createAccount'])) {
  $username = $_POST['username'];
  $usernameErrors = usernameErrorArray($username);

  // check if there's already an account with the specified username
  $stmt = $pdo->prepare("SELECT 1 FROM user_credentials WHERE username = :username");
  $stmt->execute(['username' => $username]);
  if ($stmt->rowCount() > 0) {
    // an account already exists with that username
    array_push($usernameErrors, 'Username is taken');
  }

  $pwd1 = $_POST['pwd1'];
  $pwd2 = $_POST['pwd2'];
  $pwdErrors = pwdErrorArray($pwd1);

  // push error if passwords don't match
  if ($pwd1 !== $pwd2) {
    array_push($pwdErrors, "Passwords don't match");
  }

  if (sizeof($usernameErrors) == 0 && sizeof($pwdErrors) == 0) {
    $username = trim($username);

    // create the new account and log the user in
    $stmt = $pdo->prepare("INSERT INTO user_credentials (username, pwd) VALUES (:username, :pwd)");
    $stmt->execute(['username' => $username, 'pwd' => $pwd1]);

    $_SESSION['username'] = $username;
    $_SESSION['pwd'] = $pwd;
    header("Location: {$_POST['redirect']}");
    exit;
  }
}

var_dump($_GET);
var_dump($_POST);

?>

<h2>Signup</h2>
<?= echoErrors($signupErrors) ?>
<form action='<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?redirect={$_SERVER['PHP_SELF']}" ?>' method="post">
  <p>Username: <input type="text" name="username" value="<?php if (isset($_POST['username'])) {
                                                            echo ($_POST['username']);
                                                          } ?>"></p>
  <?php echoErrors($usernameErrors); ?>
  <p>Password: <input type="password" name="pwd1"> Confirm password: <input type="password" name="pwd2"></p>
  <?php echoErrors($pwdErrors); ?>
  <p><input type="submit" name="createAccount" value="Create account"></p>
  <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?>">
</form>