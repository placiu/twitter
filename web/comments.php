<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Komentarze do Tweeta</title>
</head>
<body>

<?php
session_start();
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';
require_once '../src/Message.php';

if (isset($_SESSION['user'])) {

    // nav --------------
    include ('menu.php');
    // ------------------

    if (isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

        $tweetId = $_GET['id'];
        $tweet = Tweet::loadTweetById($conn, $tweetId);
        $tweetUserId = $tweet->getUserId();
        $tweetUser = User::loadUserById($conn, $tweetUserId);
        $tweetUserName = $tweetUser->getUsername();
        $tweetText = $tweet->getText();
        $tweetCreationDate = $tweet->getCreationDate();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['text']) AND !empty($_POST['text'])) {

                $comment = new Comment();
                $comment->setTweetId($tweetId);
                $date = date('Y-m-d H:i:s', time());
                $comment->setCreationDate($date);
                $comment->setText($_POST['text']);
                $send = $comment->saveToDB($conn);
                if (!$send) {
                    echo 'Error';
                }

            }

        }

        echo '<h3>Tweet</h3>';
        echo "<div style='width: 300px; background-color: lightblue; margin-bottom: 10px; margin-top: 10px; padding: 5px'>" . $tweetText . "<br>";
        echo "<small>" . $tweetCreationDate . ' - ' . $tweetUserName . "</small></div>";

        echo '<h3>Comments</h3>';

        $comments = Comment::loadAllCommentsByTweetId($conn, $tweetId);
        foreach ($comments as $comment) {
            $commentUserId = $comment->getUserId();
            $commentUser = User::loadUserById($conn, $commentUserId);
            $commentUserUserName = $commentUser->getUsername();
            $commentText = $comment->getText();
            $commentCreationDate = $comment->getCreationDate();

            echo "<div style = \"width: 300px; background-color: lightgray; margin-bottom: 10px; margin-top: 10px; padding: 5px\">$commentText<br><small>$commentCreationDate";
            echo " - <a href=\"user.php?id=$commentUserId\">$commentUserUserName</a></small></div>";
        }

        ?>

        <form action="" method="post">
            <p><textarea rows="4" cols="50" name="text" maxlength="140"></textarea></p>
            <p><input type="submit" value="Send"></p>
        </form>

        <?php

    }
    else {
        header("location:index.php");
    }

} else {
    header("location:index.php");
}
?>

</body>
</html>
