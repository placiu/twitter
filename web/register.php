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

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    $errors = '';
    registerValidation($username, $email, $password1, $password2);
    echo '<p>' . $errors . '</p>';

    if ($errors == '') {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password1);
        try {
            $user->saveToDB($conn);
            echo "Register OK";
        } catch (PDOException $ex) {
            echo "Error: " . $ex->getMessage();
        }

    }
}

function registerValidation($errors = '', $username, $email, $password1, $password2) {

    if (!isset($username) AND empty($username)) {
        $errors .= 'Nie podałeś loginu! <br>';
    } elseif (User::loadUserByUsername($conn, $username) != null) {
        $errors .= 'Użytkownik o podanym loginie już istnieje!<br>';
    } elseif (!preg_match('/^(?=[a-z]{1})(?=.{4,20})(?=[^.]*\.?[^.]*$)(?=[^_]*_?[^_]*$)[\w.]+$/iD', $username)) {
        $errors .= 'Nieprawidłowy login!<br>';
    } elseif (!isset($email) AND empty($email)) {
        $errors .= 'Nie podałeś adresu e-mail! <br>';
    } elseif (User::loadUserByEmail($conn, $email) != null) {
        $errors .= 'Użytkownik o podanym adresie email już istnieje<br>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors .= 'Nieprawidłowy adres email! <br>';
    } elseif ($password1 == '') {
        $errors .= 'Nie podałeś hasła! <br>';
    }

    if ($password1 == $password2) {
        $errors .= '';
    } else {
        $errors .= 'Hasła nie pasują do siebie!<br>';
    }

    return $errors;
}

?>
<p><small>Login: <br> * początek loginu od małej litery
                 <br> * login o długości od 4 - 20 znaków (litery i cyfry)
                 <br> * może zawierać jedną . lub _</small></p>
<form method="POST" action="">
    <p><label>Login: </label><input name="username" type="text"></label></p>
    <p><label>Email: <input name="email" type="email"></label></p>
    <p><label>Hasło: <input name="password1" type="password"></label></p>
    <p><label>Powtórne hasło: <input name="password2" type="password"></label></p>
    <p><label><input type="submit" value="Register"></label></p>
</form>

</body>
</html>
