<?php
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';

if (isset($_SESSION['user'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['text']) AND !empty($_POST['text'])) {
            $text = $_POST['text'];
            $userId = $_SESSION['user'];
            $date = date('Y-m-d H:i:s');

            $tweet = new Tweet();
            $tweet->setUserId($userId);
            $tweet->setText($text);
            $tweet->setCreationDate($date);
            $send = $tweet->saveToDB($conn);
            if (!$send) echo 'Error';
        }
    }

    ?>

    <form action="" method="post">
        <p><textarea rows="4" cols="50" name="text" maxlength="140"></textarea></p>
        <p><input type="submit" value="Tweet"></p>
    </form>

    <?php

    $tweets = Tweet::loadAllTweets($conn);
    foreach ($tweets as $tweet) {
        $tweetUser = User::loadUserById($conn, $tweet->getUserId());
        $tweetUserId = $tweetUser->getId();
        $tweetUserUserName = $tweetUser->getUsername();
        $tweetId = $tweet->getId();
        $tweetCreationDate = $tweet->getCreationDate();
        $tweetText = $tweet->getText();
        $commentQuantity = Comment::loadCommentQuantityByTweetId($conn, $tweet->getId());

        echo "<div style='width: 300px; background-color: lightblue; margin-bottom: 10px; padding: 5px'>";
        echo "<div style='padding-bottom: 10px; padding-top: 10px'>$tweetText</div>";
        echo "<div><small>$tweetCreationDate</small> - ";
        echo "<a href=\"user.php?id=$tweetUserId\">$tweetUserUserName</a></div>";
        echo "<small><a href=\"comments.php?id=$tweetId\">Komentarze</a> ($commentQuantity)</small></div>";
    }

} else {
    header("location:index.php");
}
?>