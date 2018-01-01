<?php

class Tweet
{

    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = 0;
        $this->text = '';
    }

    static public function loadTweetById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM tweets WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['user_id'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creation_date'];
            return $loadedTweet;
        }
        return null;
    }

    static public function loadAllTweetsByUserId(PDO $conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM tweets WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetchAll();
        if ($result !== false && $stmt->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creation_date'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    static public function loadAllTweets(PDO $conn)
    {
        $tweets = [];
        $sql = "SELECT * FROM tweets ORDER BY creation_date DESC";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creation_date'];
                $tweets[] = $loadedTweet;
            }
        }
        return $tweets;
    }

    public function saveToDB(PDO $conn)
    {
        $sql = 'INSERT INTO tweets(id, user_id, text, creation_date) VALUES(null, :userId, :text, :creationDate)';
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute(['userId' => $this->userId, 'text' => $this->text, 'creationDate' => $this->creationDate]);
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

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

}