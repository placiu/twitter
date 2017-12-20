<?php

class Comment
{
    private $id;
    private $userId;
    private $postId;
    private $creationDate;
    private $text;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = 0;
        $this->postId = 0;
        $this->creationDate = 0;
        $this->text = '';
    }

    static public function loadCommentQuantityByPostId($conn, $postId) // połączyć z loadAllCommentsByPostId
    {
        $stmt = $conn->prepare('SELECT * FROM comment WHERE post_id = :id');
        $result = $stmt->execute(['id' => $postId]);
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
            $loadedComments->postId = $row['post_id'];
            $loadedComments->creationDate = $row['creation_date'];
            $loadedComments->text = $row['text'];
            return $loadedComments;
        }
        return null;
    }

    static public function loadAllCommentsByPostId(PDO $conn, $postId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM comment WHERE post_id = :post_id ORDER BY creation_date DESC');
        $stmt->execute(['post_id' => $postId]);
        $result = $stmt->fetchAll();
        if ($result !== false && $stmt->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedComments = new Comment();
                $loadedComments->id = $row['id'];
                $loadedComments->userId = $row['user_id'];
                $loadedComments->postId = $row['post_id'];
                $loadedComments->creationDate = $row['creation_date'];
                $loadedComments->text = $row['text'];
                $ret[] = $loadedComments;
            }
        }
        return $ret;
    }

    public function saveToDB(PDO $conn)
    {
        $sql = 'INSERT INTO comment(id, user_id, post_id, creation_date, text) VALUES(null, :userId, :postId, :creationDate, :text)';
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            'userId' => $_SESSION['user'],
            'postId' => $this->getPostId(),
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

    public function getPostId()
    {
        return $this->postId;
    }

    public function setPostId($postId)
    {
        $this->postId = $postId;
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