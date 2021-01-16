<?php

class File {

    private $FileID;
    private $FileName;
    private $UserID;
    private $FileDate;
    private $TagID;
    private $ShowType;

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

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->FileName;
    }

    /**
     * @param mixed $FileName
     */
    public function setFileName($FileName)
    {
        $this->FileName = $FileName;
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
    public function getFileDate()
    {
        return $this->FileDate;
    }

    /**
     * @param mixed $FileDate
     */
    public function setFileDate($FileDate)
    {
        $this->FileDate = $FileDate;
    }

    /**
     * @return mixed
     */
    public function getTagID()
    {
        return $this->TagID;
    }

    /**
     * @param mixed $TagID
     */
    public function setTagID($TagID)
    {
        $this->TagID = $TagID;
    }

    /**
     * @return mixed
     */
    public function getShowType()
    {
        return $this->ShowType;
    }

    /**
     * @param mixed $ShowType
     */
    public function setShowType($ShowType)
    {
        $this->ShowType = $ShowType;
    }

    public function __construct($newFileName, $newUserID, $newFileDate, $newShowType) {

        $this->setFileName($newFileName);
        $this->setUserID($newUserID);
        $this->setFileDate($newFileDate);
        $this->setShowType($newShowType);
    }
}