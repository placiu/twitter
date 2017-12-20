<?php

require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['text']) AND !empty($_POST['text'])) {

        $text = $_POST['text'];
        $userId = $_SESSION['user'];
        $date = date('Y-m-d');

        $tweet = new Tweet();
        $tweet->setUserId($userId);
        $tweet->setText($text);
        $tweet->setCreationDate($date);
        $tweet->saveToDB($conn);

    } else {
        echo "Nie wpisales nic!";
    }

}

?>

    <p>
    <form action="" method="post">
    <textarea rows="4" cols="50" name="text" maxlength="140"></textarea>
    <p><input type="submit" value="Tweet"></p>
    </form>
    </p>

<?php

$tweets = Tweet::loadAllTweets($conn);
foreach ($tweets as $tweet) {
    $user = User::loadUserById($conn, $tweet->getUserId());
    $commentQuantity = Comment::loadCommentQuantityByPostId($conn, $tweet->getId());
    echo "<p><a href=\"tweet.php?id=" . $tweet->getId() . "\">" . $user->getUsername() . ' ' . $tweet->getText() . ' ' . $tweet->getCreationDate() . '(' . $commentQuantity . ')' . "</a>";
}

?>