<?php

class Tag {

    private $TagID;
    private $TagText;

    /**
     * Tag constructor.
     * @param $TagText
     */
    public function __construct($TagText)
    {
        $this->TagText = $TagText;
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
    public function getTagText()
    {
        return $this->TagText;
    }

    /**
     * @param mixed $TagText
     */
    public function setTagText($TagText)
    {
        $this->TagText = $TagText;
    }
}