<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tweeter</title>
</head>
<body>

<?php
session_start();
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Message.php';

if (isset($_SESSION['user'])) {

    // nav --------------
    include ('menu.php');
    // tweets -----------
    include('tweets.php');

} else {
    echo "<a href='register.php'>rejestracja</a>";
    include('login.php');

}
?>

</body>
</html>
