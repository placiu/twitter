<?php
session_start();
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';

if (isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

    $tweetId = $_GET['id'];

    $tweet = Tweet::loadTweetById($conn, $tweetId);
    $tweetUserId = $tweet->getUserId();
    $tweetText = $tweet->getText();
    $tweetCreationDate = $tweet->getCreationDate();

    $tweetUser = User::loadUserById($conn, $tweetUserId);
    $tweetUserName = $tweetUser->getUsername();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['text']) AND !empty($_POST['text'])) {

            $comment = new Comment();
            $postId = $comment->setPostId($tweetId);
            $date = date('Y-m-d H:i:s', time());
            $creationDate = $comment->setCreationDate($date);
            $text = $comment->setText($_POST['text']);
            $comment->saveToDB($conn);

        }

    }

    echo '<h2>Tweet</h2>';
    echo $tweetText . '<br>';
    echo $tweetCreationDate . ' - ' . $tweetUserName . '<br>';
    echo '<h3>Comments</h3>';

    $comments = Comment::loadAllCommentsByPostId($conn, $tweetId);

    foreach ($comments as $comment) {
        $commentUser = User::loadUserById($conn, $comment->getUserId());
        echo "<p>" . $comment->getText() . '<br>' . $comment->getCreationDate();
        echo " - <a href=\"user.php?id=" . $commentUser->getId() . " \">" . $commentUser->getUsername() . "</a>";
    }

    ?>

    <form action="" method="post">
        <p><textarea rows="4" cols="50" name="text" maxlength="140"></textarea></p>
        <p><input type="submit" value="Send"></p>
    </form>

    <?php

}