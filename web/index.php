<?php

require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Message.php';
session_start();

if (isset($_SESSION['user'])) {

    $userId = $_SESSION['user'];
    $user = User::loadUserById($conn, $userId);
    echo '<a href=\'index.php\'>Strona Główna</a> ';
    echo "<a href=\"user.php?id=$userId\">" . $user->getUsername() . "</a> ";

    $messages = Message::loadUnreadMsgQuantityByUserId($conn, $userId);

    echo "<a href=\"msg.php\">($messages)</a> ";
    echo "(<a href=\"logout.php\">Wyloguj</a>)";

    include('tweets.php');

}
else {

    include ('login.php');
    echo "<a href='register.php'>Rejestracja</a>";

}