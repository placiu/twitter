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

if (isset($_SESSION['user'])) {

    // nav --------------
    include ('menu.php');

    echo '<h3>Wiadomosci:</h3>';

    $messages = Message::loadMainMsgByUserId($conn, $userId);
    foreach ($messages as $message) {
        $messageId = $message->getId();
        $messageIdSender = $message->getIdSender();
        $messageIdReceiver = $message->getIdReceiver();
        $messageMsg = $message->getMsg();
        $messageCreationDate = $message->getCreationDate();
        $messageRead = $message->getRead();

        $messageSender = User::loadUserById($conn, $messageIdSender);
        $senderUserName = $messageSender->getUsername();
        $receiver = User::loadUserById($conn, $messageIdReceiver);
        $receiverUserName = $receiver->getUsername();

        ($messageRead == 0 AND $messageIdReceiver == $userId) ? $new = "<div style = \"background-color: lightgreen; width: 330px; margin-top: 10px; margin-bottom: 10px; padding: 10px\">" : $new = '<div style = "width: 330px; margin-top: 10px; margin-bottom: 10px;">';
        ($messageIdReceiver == $userId) ? $respond = " <a href='user.php?id=$messageIdSender'>$senderUserName</a> - <a href=\"sendmsg.php?id=$messageIdSender&refid=$messageId\">Odpowiedz</a>" : $respond = " do: <u><a href='user.php?id=$messageIdReceiver'>$receiverUserName</a></u>";

        echo "$new $messageMsg<br><small>$messageCreationDate - $respond</small></div>";

        $refMsgs = Message::loadRefMsgByMsgId($conn, $messageId);
        foreach ($refMsgs as $refMsg) {
            $refMsgIdSender = $refMsg->getIdSender();
            $refMsgIdReceiver = $refMsg->getIdReceiver();
            $refMsgMsg = $refMsg->getMsg();
            $refMsgCreationDate = $refMsg->getCreationDate();
            $refMsgRead = $refMsg->getRead();

            $refMsgSender = User::loadUserById($conn, $refMsgIdSender);
            $refMsgSenderUserName = $refMsgSender->getUsername();

            ($refMsgRead == '0' AND $refMsgIdReceiver == $userId) ? $new = "<div style = \"background-color: lightgreen; width: 300px; margin-left: 30px; margin-top: 10px; margin-bottom: 10px; padding: 10px\">" : $new = "<div style=\"margin-left: 30px; margin-top: 10px; margin-bottom: 10px\">";
            ($refMsgIdReceiver == $userId) ? $respond = " - <a href=\"sendmsg.php?id=$refMsgIdSender&refid=$messageId\">Odpowiedz</a>" : $respond = '';

            echo "$new $refMsgMsg<br><small>$refMsgCreationDate - $refMsgSenderUserName $respond</small></div>";
        }
    }

    Message::markAsRead($conn, $userId);

}

?>

</body>
</html>
