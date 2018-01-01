<?php

session_start();
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';

if (isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

    $userId = $_GET['id'];
    $user = User::loadUserById($conn, $userId);
    $userEmail = $user->getEmail();
    $userName = $user->getUsername();

    echo "<h2>Tweety użytkownika $userName ($userEmail)</h2>";

    if ($_SESSION['user'] != $_GET['id']) {
        echo "<a href=\"sendmsg.php?id=" . $userId . "\">Send Message to this user</a><br><br>";
    }

    $tweets = Tweet::loadAllTweetsByUserId($conn, $userId);
    foreach ($tweets as $tweet) {
        $commentQuantity = Comment::loadCommentQuantityByPostId($conn, $tweet->getId());
        echo "<p><a href=\"tweet.php?id=" . $tweet->getId() . "\">" . $tweet->getText() . ' ' . $tweet->getCreationDate() . '(' . $commentQuantity . ')' . "</a>";
    }

}
else {
    header("location:index.php");
}