<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tweety Użytkownika</title>
</head>
<body>

<?php
session_start();
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';
require_once '../src/Message.php';


if (isset($_SESSION['user']) AND isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

    // nav --------------
    include ('menu.php');
    // ------------------

    $id = $_GET['id'];
    $user = User::loadUserById($conn, $id);
    $userEmail = $user->getEmail();
    $userName = $user->getUsername();

    echo "<h3>Tweety użytkownika <u>$userName</u><br><small>($userEmail)</small></h3>";

    if ($_SESSION['user'] != $_GET['id']) {
        echo "<p><a href=\"sendmsg.php?id=$id\">Send Message</a></p>";
    }

    $tweets = Tweet::loadAllTweetsByUserId($conn, $id);
    foreach ($tweets as $tweet) {
        $tweetId = $tweet->getId();
        $tweetText = $tweet->getText();
        $tweetCreationDate = $tweet->getCreationDate();
        $commentQuantity = Comment::loadCommentQuantityByTweetId($conn, $tweetId);
        echo "<div style='width: 300px; background-color: lightblue; margin-bottom: 10px; padding: 5px'>";
        echo "$tweetText<br><small>$tweetCreationDate | <a href=\"comments.php?id=$tweetId\">Komentarze ($commentQuantity)</a></small></div>";
    }

} else {
    header("location:index.php");
}

?>
</body>
</html>
