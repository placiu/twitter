<?php

session_start();
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Message.php';

if (isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

    $idReceiver = $_GET['id'];
    $userReceiver = User::loadUserById($conn, $idReceiver);

    echo '<h3>Send Message to <u>' . $userReceiver->getUsername() . '</u></h3>';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['text']) AND !empty($_POST['text'])) {

            (isset($_GET['refid']) AND !empty($_GET['refid'])) ? $refId = $_GET['refid'] : $refId = 0;
            $msg = $_POST['text'];
            $message = new Message();
            $refId = $message->setRefId($refId);
            $idReceiver = $message->setIdReceiver($idReceiver);
            $msg = $message->setMsg($msg);
            $date = date('Y-m-d H:i:s', time());
            $creationDate = $message->setCreationDate($date);
            if ($message->saveToDB($conn)) {
                echo 'Message send!';
            }
            else {
                echo 'Error';
            }

        }

    }
    else {

        ?>

        <form action="" method="post">
            <p><textarea rows="4" cols="50" name="text" maxlength="140"></textarea></p>
            <p><input type="submit" value="Send"></p>

        </form>

        <?php

    }

}
else {
    header("location:index.php");
}