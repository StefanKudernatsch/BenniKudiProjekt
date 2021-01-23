<?php

class Message {

    private $MessageID;
    private $MessageText;
    private $SenderID;
    private $ReceiverID;
    private $Status;
    private $TimeSent;

    /**
     * Message constructor.
     * @param $MessageText
     * @param $SenderID
     * @param $ReceiverID
     * @param $Status
     */
    public function __construct($MessageText, $SenderID, $ReceiverID, $Status, $TimeSent)
    {
        $this->MessageText = $MessageText;
        $this->SenderID = $SenderID;
        $this->ReceiverID = $ReceiverID;
        $this->Status = $Status;
        $this->TimeSent = $TimeSent;
    }

    /**
     * @return mixed
     */
    public function getMessageID()
    {
        return $this->MessageID;
    }

    /**
     * @param mixed $MessageID
     */
    public function setMessageID($MessageID)
    {
        $this->MessageID = $MessageID;
    }

    /**
     * @return mixed
     */
    public function getMessageText()
    {
        return $this->MessageText;
    }

    /**
     * @param mixed $MessageText
     */
    public function setMessageText($MessageText)
    {
        $this->MessageText = $MessageText;
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

    public function getTimeSent()
    {
        return $this->TimeSent;
    }

    /**
     * @param mixed $TimeSent
     */
    public function setTimeSent($TimeSent)
    {
        $this->TimeSent = $TimeSent;
    }
}