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

$loginErrors = $usernameErrors = $pwdErrors = [];

// form action
if (isset($_POST['logIn'])) {
  $username = $_POST['username'];
  $usernameErrors = usernameErrorArray($username);

  $pwd = $_POST['pwd'];
  $pwdErrors = pwdErrorArray($pwd);

  if (sizeof($usernameErrors) == 0 && sizeof($pwdErrors) == 0) {
    $username = trim($username);

    // attempt login
    $stmt = $pdo->prepare("SELECT 1 FROM user_credentials WHERE username = :username && pwd = :pwd");
    $stmt->execute(['username' => $username, 'pwd' => $pwd]);

    if ($stmt->rowCount() == 0) {
      // if login fails, notify the user
      array_push($loginErrors, 'Unknown username or incorrect password');
    } else {
      // if login is successful: get user id
      $stmt = $pdo->query("SELECT * FROM user_credentials WHERE username = '$username'");
      $_SESSION['userId'] = $stmt->fetchColumn();
      $_SESSION['username'] = $username;
      header("Location: {$_POST['redirect']}");
      exit;
    }
  }
}

var_dump($_GET);
var_dump($_POST);

?>

<h2>Login</h2>
<?= echoErrors($loginErrors) ?>
<form action='<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?redirect={$_SERVER['PHP_SELF']}" ?>' method="post">
  <p>Username: <input type="text" name="username" value="<?php if (isset($_POST['username'])) {
                                                            echo ($_POST['username']);
                                                          } ?>"></p>
  <?php echoErrors($usernameErrors); ?>
  <p>Password: <input type="password" name="pwd"></p>
  <?php echoErrors($pwdErrors); ?>
  <p><input type="submit" name="logIn" value="Sign in"></p>
  <input type="hidden" name="redirect" value="<?= $_GET['redirect'] ?>">
</form>
<p>Don't have an account? Create one <a href="signup.php?redirect=<?= $_GET['redirect'] ?>">here</a></p>

<?php require('misc/footer.php'); ?>