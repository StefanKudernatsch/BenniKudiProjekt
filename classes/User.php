<?php

class User {

    private $UserID;
    private $UserName;
    private $UserAge;
    private $UserGender;
    private $UserImage;
    private $UserEMail;
    private $UserPassword;

    
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
    public function getUserName()
    {
        return $this->UserName;
    }

    
    /**
     * @param mixed $UserName
     */
    public function setUserName($UserName)
    {
        $this->UserName = $UserName;
    }

    
    /**
     * @return mixed
     */
    public function getUserAge()
    {
        return $this->UserAge;
    }

    
    /**
     * @param mixed $UserAge
     */
    public function setUserAge($UserAge)
    {
        $this->UserAge = $UserAge;
    }

    
    /**
     * @return mixed
     */
    public function getUserGender()
    {
        return $this->UserGender;
    }

    
    /**
     * @param mixed $UserGender
     */
    public function setUserGender($UserGender)
    {
        $this->UserGender = $UserGender;
    }

    
    /**
     * @return mixed
     */
    public function getUserImage()
    {
        return $this->UserImage;
    }

    
    /**
     * @param mixed $UserImage
     */
    public function setUserImage($UserImage)
    {
        $this->UserImage = $UserImage;
    }


    /**
     * @return mixed
     */
    public function getUserEMail()
    {
        return $this->UserEMail;
    }


    /**
     * @param mixed $UserEMail
     */
    public function setUserEMail($UserEMail)
    {
        $this->UserEMail = $UserEMail;
    }


    /**
     * @return mixed
     */
    public function getUserPassword()
    {
        return $this->UserPassword;
    }


    /**
     * @param mixed $UserPassword
     */
    public function setUserPassword($UserPassword)
    {
        $this->UserPassword = $UserPassword;
    }


    public function __construct($NewUserName, $NewUserAge, $NewUserGender, $NewUserEMail, $NewUserPassword) {

        $this->setUserName($NewUserName);
        $this->setUserAge($NewUserAge);
        $this->setUserGender($NewUserGender);
        $this->setUserEMail($NewUserEMail);
        $this->setUserPassword($NewUserPassword);
    }
}