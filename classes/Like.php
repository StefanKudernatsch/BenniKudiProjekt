<?php

class Like {

    private $LikeID;
    private $LikeType;
    private $UserID;
    private $FileID;

    /**
     * Like constructor.
     * @param $LikeType
     * @param $UserID
     * @param $FileID
     */
    public function __construct($LikeType, $UserID, $FileID)
    {
        $this->LikeType = $LikeType;
        $this->UserID = $UserID;
        $this->FileID = $FileID;
    }

    /**
     * @return mixed
     */
    public function getLikeID()
    {
        return $this->LikeID;
    }

    /**
     * @param mixed $LikeID
     */
    public function setLikeID($LikeID)
    {
        $this->LikeID = $LikeID;
    }

    /**
     * @return mixed
     */
    public function getLikeType()
    {
        return $this->LikeType;
    }

    /**
     * @param mixed $LikeType
     */
    public function setLikeType($LikeType)
    {
        $this->LikeType = $LikeType;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->UserID;
    }

    /**
     * @param mixed $UserID
     */
    public function setUserID($UserID)
    {
        $this->UserID = $UserID;
    }

    /**
     * @return mixed
     */
    public function getFileID()
    {
        return $this->FileID;
    }

    /**
     * @param mixed $FileID
     */
    public function setFileID($FileID)
    {
        $this->FileID = $FileID;
    }
}