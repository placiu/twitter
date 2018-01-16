<?php

class Message
{

    private $id;
    private $refId;
    private $idSender;
    private $idReceiver;
    private $msg;
    private $creationDate;
    private $read;

    public function __construct()
    {
        $this->id = -1;
        $this->refId = 0;
        $this->idSender = 0;
        $this->idReceiver = 0;
        $this->msg = '';
        $this->creationDate = 0;
        $this->read = 0;

    }

    static public function loadUnreadMsgQuantityByUserId($conn, $userId)
    {
        $stmt = $conn->prepare('SELECT * FROM messages WHERE id_receiver = :userId AND `read` = 0');
        $result = $stmt->execute(['userId' => $userId]);
        if ($result === true) {
            return $quantity = $stmt->rowCount();
        }
    }

    static public function markAsRead($conn, $userId)
    {

        $sql = 'UPDATE messages SET `read` = 1 WHERE id_receiver = :id AND `read` = 0';
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            'id' => $userId,
        ]);
        if ($result !== false) {
            return true;
        }
        return false;

    }

    static public function loadMainMsgByUserId(PDO $conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM messages WHERE (id_receiver = :user_id OR id_sender = :user_id) AND ref_id = 0 ORDER BY creation_date DESC');
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetchAll();
        if ($result !== false && $stmt->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->ref_id = $row['ref_id'];
                $loadedMessage->idSender = $row['id_sender'];
                $loadedMessage->idReceiver = $row['id_receiver'];
                $loadedMessage->msg = $row['msg'];
                $loadedMessage->creationDate = $row['creation_date'];
                $loadedMessage->read = $row['read'];
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    }

    static public function loadRefMsgByMsgId(PDO $conn, $msgId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM messages WHERE ref_id = :ref_id ORDER BY creation_date ASC');
        $stmt->execute(['ref_id' => $msgId]);
        $result = $stmt->fetchAll();
        if ($result !== false && $stmt->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->ref_id = $row['ref_id'];
                $loadedMessage->idSender = $row['id_sender'];
                $loadedMessage->idReceiver = $row['id_receiver'];
                $loadedMessage->msg = $row['msg'];
                $loadedMessage->creationDate = $row['creation_date'];
                $loadedMessage->read = $row['read'];
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    }

    static public function loadSendMsgByUserId(PDO $conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM messages WHERE id_sender = :user_id ORDER BY creation_date DESC');
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetchAll();
        if ($result !== false && $stmt->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->ref_id = $row['ref_id'];
                $loadedMessage->id_sender = $row['id_sender'];
                $loadedMessage->id_receiver = $row['id_receiver'];
                $loadedMessage->msg = $row['msg'];
                $loadedMessage->creationDate = $row['creation_date'];
                $loadedMessage->read = $row['read'];
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    }

    public function saveToDB(PDO $conn)
    {
        $sql = 'INSERT INTO messages(id, ref_id, id_sender, id_receiver, msg, creation_date, `read`) VALUES(null, :ref_id, :id_sender, :id_receiver, :msg, :creationDate, :read)';
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            'ref_id' => $this->getRefId(),
            'id_sender' => $_SESSION['user'],
            'id_receiver' => $this->getIdReceiver(),
            'msg' => $this->getMsg(),
            'creationDate' => $this->getCreationDate(),
            'read' => $this->getRead()
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

    public function getRefId()
    {
        return $this->refId;
    }

    public function setRefId($refId)
    {
        $this->refId = $refId;
    }

    public function getIdSender()
    {
        return $this->idSender;
    }

    public function setIdSender($idSender)
    {
        $this->idSender = $idSender;
    }

    public function getIdReceiver()
    {
        return $this->idReceiver;
    }

    public function setIdReceiver($idReceiver)
    {
        $this->idReceiver = $idReceiver;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getRead()
    {
        return $this->read;
    }

    public function setRead($read)
    {
        $this->read = $read;
    }

}