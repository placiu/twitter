<?php
session_start();
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';

if (isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

    $tweet_id = $_GET['id'];
    $tweet = Tweet::loadTweetById($conn, $tweet_id);
    $tweet_id = $tweet->getId();
    $tweet_userId = $tweet->getUserId();
    $tweet_text = $tweet->getText();
    $tweet_creation_date = $tweet->getCreationDate();
    $user = User::loadUserById($conn, $tweet_userId);
    $userName = $user->getUsername();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['text']) AND !empty($_POST['text'])) {

            $comment = new Comment();
            $postId = $comment->setPostId($tweet_id);
            $creationDate = $comment->setCreationDate('2017-12-22 07:13:26');   // USTAWIC DATE !!!!!!
            $text = $comment->setText($_POST['text']);
            $comment->saveToDB($conn);

        }

    }

    echo $tweet_text . '<br>';
    echo $tweet_creation_date . ' - ' . $userName . '<br><br>';

    $comments = Comment::loadAllCommentsByPostId($conn, $tweet_id);
    foreach ($comments as $comment) {
        $user = User::loadUserById($conn, $comment->getUserId());
        $userName = $user->getUsername();
        echo "<p>" . $comment->getText() . '<br>' . $comment->getCreationDate() . " - $userName</p>";
    }


    ?>

    <p>
        <form action="" method="post">
            <textarea rows="4" cols="50" name="text" maxlength="140"></textarea>
    <p><input type="submit" value="Send"></p>
    </form>
    </p>

    <?php


}