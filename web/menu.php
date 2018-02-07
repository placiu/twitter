<?php
$userId = $_SESSION['user'];
$user = User::loadUserById($conn, $userId);
$userUserName = $user->getUsername();
$unread = Message::loadUnreadMsgQuantityByUserId($conn, $userId);
($unread > 0) ? $unreadmsg = "($unread)" : $unreadmsg = '';
echo "<a href=\"index.php\">home</a>";
echo " | <a href=\"msg.php\">wiadomości $unreadmsg</a> | ";
echo "<a href=\"user.php?id=$userId\">$userUserName</a> ";
echo "(<a href=\"logout.php\">wyloguj</a>)";

