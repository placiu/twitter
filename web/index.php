<?php

require_once '../src/connection.php';
require_once '../src/User.php';
session_start();

if (isset($_SESSION['user'])) {

    $userId = $_SESSION['user'];
    $user = User::loadUserById($conn, $userId);
    echo '<a href=\'index.php\'>Strona Główna</a> ';
    echo $user->getUsername(). " (<a href='logout.php'>Wyloguj</a>)";

    include('tweet.php');

}
else {

    include ('login.php');
    echo "<a href='register.php'>Rejestracja</a>";

}