<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
</head>
<body>
<a href='index.php'>home</a>

<?php
require_once '../src/connection.php';
require_once '../src/User.php';

if ('POST' === $_SERVER['REQUEST_METHOD']) {

    $errors = '';
    (isset($_POST['username']) AND !empty($_POST['username'])) ? $errors .= '' : $errors .= 'Nie podałeś loginu! <br>';
    (isset($_POST['email']) AND !empty($_POST['email'])) ? $errors .= '' : $errors .= 'Nie podałeś adresu e-mail! <br>';
    (isset($_POST['password']) AND !empty($_POST['password'])) ? $errors .= '' : $errors .= 'Nie podałeś hasła! <br>';
    echo '<p>' . $errors . '</p>';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // walidacja

    if ($errors == '') {

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        if (!$user->saveToDB($conn)) {
            echo "Register Error!";
        }

    }
}

?>

<form method="POST" action="">
    <p><label>Login: </label><input name="username" type="text"></label></p>
    <p><label>Email: <input name="email" type="email"></label></p>
    <p><label>Hasło: <input name="password" type="password"></label></p>
    <p><label><input type="submit" value="Register"></label></p>
</form>

</body>
</html>
