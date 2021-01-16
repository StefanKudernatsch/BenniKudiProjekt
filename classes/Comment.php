<?php

class Comment {

    private $CommentID;
    private $CommentText;
    private $UserID;
    private $FileID;

    /**
     * @return mixed
     */
    public function getCommentID()
    {
        return $this->CommentID;
    }

    /**
     * @param mixed $CommentID
     */
    public function setCommentID($CommentID)
    {
        $this->CommentID = $CommentID;
    }

    /**
     * @return mixed
     */
    public function getCommentText()
    {
        return $this->CommentText;
    }

    /**
     * @param mixed $CommentText
     */
    public function setCommentText($CommentText)
    {
        $this->CommentText = $CommentText;
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

    public function __construct($newCommenText, $newUserID, $newFileID) {

        $this->setCommentText($newCommenText);
        $this->setUserID($newUserID);
        $this->setFileID($newFileID);
    }
}