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

    if (!isset($username) AND empty($username)) {
        $errors .= 'Nie podałeś loginu! <br>';
        $tmp = 1;
    }




    (isset($username) AND !empty($username)) ? $errors .= '' : $errors .= 'Nie podałeś loginu! <br>';
    (User::loadUserByUsername($conn, $username) == null) ? $errors .= '' : $errors .= 'Użytkownik o podanym loginie już istnieje!<br>';
    (preg_match('/^(?=[a-z]{1})(?=.{4,20})(?=[^.]*\.?[^.]*$)(?=[^_]*_?[^_]*$)[\w.]+$/iD', $username)) ? $errors .= '' : $errors .= 'Nieprawidłowy login!<br>';

    (isset($email) AND !empty($email)) ? $errors .= '' : $errors .= 'Nie podałeś adresu e-mail! <br>';
    (User::loadUserByEmail($conn, $email) == null) ? $errors .= '' : $errors .= 'Użytkownik o podanym adresie email już istnieje<br>';
    (filter_var($email, FILTER_VALIDATE_EMAIL)) ? $errors .= '' : $errors .= 'Nieprawidłowy adres email! <br>';

    (isset($password1) AND !empty($password1)) ? $errors .= '' : $errors .= 'Nie podałeś hasła! <br>';
    if ($password1 == $password2) {
        $password = $password1;
        $errors .= '';
    } else {
        $errors .= 'Hasła nie pasują do siebie!<br>';
    }

    echo '<p>' . $errors . '</p>';

    if ($errors == '') {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        try {
            $user->saveToDB($conn);
            echo "Register OK";
        } catch (PDOException $ex) {
            echo "Error: " . $ex->getMessage();
        }

    }
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
