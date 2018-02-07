<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Wiadomości</title>
</head>
<body>

<?php
session_start();
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Message.php';

if (isset($_SESSION['user']) AND isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

    // nav --------------
    include ('menu.php');

    $idReceiver = $_GET['id'];
    $userReceiver = User::loadUserById($conn, $idReceiver);
    $userReceiverUserName = $userReceiver->getUsername();

    echo "<h3>Send Message to <u>$userReceiverUserName</u></h3>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['text']) AND !empty($_POST['text'])) {

            $msg = $_POST['text'];
            (isset($_GET['refid'])) ? $refId = $_GET['refid'] : $refId = 0;

            $message = new Message();
            $message->setRefId($refId);
            $message->setIdSender($userId);
            $message->setIdReceiver($idReceiver);
            $message->setMsg($msg);
            $date = date('Y-m-d H:i:s', time());
            $message->setCreationDate($date);
            $send = $message->saveToDB($conn);
            if ($send) {
                echo 'Message send!';
            } else {
                echo 'Error';
            }

        }

    } else {

        ?>

        <form action="" method="post">
            <p><textarea rows="4" cols="50" name="text" maxlength="140"></textarea></p>
            <p><input type="submit" value="Send"></p>

        </form>

        <?php

    }

    if(strpos($_SERVER['HTTP_REFERER'], 'refid')) {
        echo "<p><a href=\"msg.php\">back</a></p>";
    } else {
        echo "<p><a href=\"user.php?id=$idReceiver\">back</a></p>";
    }


} else {
    header("location:index.php");
}

?>
</body>
</html>