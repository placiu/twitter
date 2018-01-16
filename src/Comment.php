<?php

class Comment
{
    private $id;
    private $userId;
    private $tweetId;
    private $creationDate;
    private $text;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = 0;
        $this->tweetId = 0;
        $this->creationDate = 0;
        $this->text = '';
    }

    static public function loadCommentQuantityByTweetId($conn, $tweetId)
    {
        $stmt = $conn->prepare('SELECT * FROM comment WHERE tweet_id = :tweetId');
        $result = $stmt->execute(['tweetId' => $tweetId]);
        if ($result === true) {
            return $quantity = $stmt->rowCount();
        }
    }

    static public function loadCommentById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM comment WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedComments = new Comment();
            $loadedComments->id = $row['id'];
            $loadedComments->userId = $row['user_id'];
            $loadedComments->tweetId = $row['tweet_id'];
            $loadedComments->creationDate = $row['creation_date'];
            $loadedComments->text = $row['text'];
            return $loadedComments;
        }
        return null;
    }

    static public function loadAllCommentsByTweetId(PDO $conn, $tweetId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM comment WHERE tweet_id = :tweet_id ORDER BY creation_date ASC');
        $stmt->execute(['tweet_id' => $tweetId]);
        $result = $stmt->fetchAll();
        if ($result !== false && $stmt->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedComments = new Comment();
                $loadedComments->id = $row['id'];
                $loadedComments->userId = $row['user_id'];
                $loadedComments->tweetId = $row['tweet_id'];
                $loadedComments->creationDate = $row['creation_date'];
                $loadedComments->text = $row['text'];
                $ret[] = $loadedComments;
            }
        }
        return $ret;
    }

    public function saveToDB(PDO $conn)
    {
        $sql = 'INSERT INTO comment(id, user_id, tweet_id, creation_date, text) VALUES(null, :userId, :tweetId, :creationDate, :text)';
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            'userId' => $_SESSION['user'],
            'tweetId' => $this->getTweetId(),
            'creationDate' => $this->getCreationDate(),
            'text' => $this->getText()
        ]);
        if ($result !== false) {
            $this->id = $conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getTweetId()
    {
        return $this->tweetId;
    }

    public function setTweetId($tweetId)
    {
        $this->tweetId = $tweetId;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }


}