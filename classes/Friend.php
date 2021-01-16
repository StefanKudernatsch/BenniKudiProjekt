<?php

class Friend {

    private $FriendID;
    private $SenderID;
    private $ReceiverID;
    private $Status;

    /**
     * @return mixed
     */
    public function getFriendID()
    {
        return $this->FriendID;
    }

    /**
     * @param mixed $FriendID
     */
    public function setFriendID($FriendID)
    {
        $this->FriendID = $FriendID;
    }

    /**
     * @return mixed
     */
    public function getSenderID()
    {
        return $this->SenderID;
    }

    /**
     * @param mixed $SenderID
     */
    public function setSenderID($SenderID)
    {
        $this->SenderID = $SenderID;
    }

    /**
     * @return mixed
     */
    public function getReceiverID()
    {
        return $this->ReceiverID;
    }

    /**
     * @param mixed $ReceiverID
     */
    public function setReceiverID($ReceiverID)
    {
        $this->ReceiverID = $ReceiverID;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * @param mixed $Status
     */
    public function setStatus($Status)
    {
        $this->Status = $Status;
    }

    public function __construct($newSenderID, $newReceiverID, $newStatus) {

        $this->setSenderID($newSenderID);
        $this->setReceiverID($newReceiverID);
        $this->setStatus($newStatus);
    }
}