<?php

session_start();
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Message.php';

echo '<h2>Wiadomosci:</h2>';

$messages = Message::loadMainMsgByUserId($conn, $_SESSION['user']);

foreach ($messages as $message) {

    $idSender = $message->getIdSender();
    $idReceiver = $message->getIdReceiver();

    $sender = User::loadUserById($conn, $idSender);
    $reveiver = User::loadUserById($conn, $idReceiver);

    ($message->getRead() == '0') ? $new = '<p><b>NEW!:</b> ' : $new = '';
    ($message->getIdReceiver() == $_SESSION['user']) ? $respond = ' ' . $sender->getUsername() . ' - <a href="sendmsg.php?id=' . $idSender . '&refid=' . $message->getId() . '">Odpowiedz</a>' : $respond = ' do: <u>' . $reveiver->getUsername() . '</u>';

    echo '<p>' . $new . $message->getMsg() . '<br><small>' . $message->getCreationDate();
    echo $respond . '</small></p>';

    $refmsgs = Message::loadRefMsgByMsgId($conn, $message->getId());

    foreach ($refmsgs as $refmsg) {

        $idSender = $refmsg->getIdSender();
        $user = User::loadUserById($conn, $idSender);

        ($refmsg->getRead() == '0') ? $new = '<p><b>Unread!</b> ' : $new = '';
        ($refmsg->getIdReceiver() == $_SESSION['user']) ? $respond = ' - <a href="sendmsg.php?id=' . $idSender . '&refid=' . $message->getId() . '">Odpowiedz</a>' : $respond = '';

        echo '<p><div style="margin-left: 30">' . $new . $refmsg->getMsg() . '<br><small>' . $refmsg->getCreationDate() . ' - ' . $user->getUsername();
        echo $respond . '</small></div></p>';

    }

    Message::markAsRead($conn, $_SESSION['user']);

}
