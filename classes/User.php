<?php

class User {

    private $UserID;
    private $UserGender;
    private $UserFirstName;
    private $UserLastName;
    private $UserBirthday;
    private $UserImage;
    private $UserName;
    private $UserPassword;
    private $UserEMail;
    private $UserCity;
    private $UserPLZ;
    private $UserAddress;

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
    public function getUserFirstName()
    {
        return $this->UserFirstName;
    }

    /**
     * @param mixed $UserFirstName
     */
    public function setUserFirstName($UserFirstName)
    {
        $this->UserFirstName = $UserFirstName;
    }

    /**
     * @return mixed
     */
    public function getUserLastName()
    {
        return $this->UserLastName;
    }

    /**
     * @param mixed $UserLastName
     */
    public function setUserLastName($UserLastName)
    {
        $this->UserLastName = $UserLastName;
    }

    /**
     * @return mixed
     */
    public function getUserBirthday()
    {
        return $this->UserBirthday;
    }

    /**
     * @param mixed $UserBirthday
     */
    public function setUserBirthday($UserBirthday)
    {
        $this->UserBirthday = $UserBirthday;
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
    public function getUserCity()
    {
        return $this->UserCity;
    }

    /**
     * @param mixed $UserCity
     */
    public function setUserCity($UserCity)
    {
        $this->UserCity = $UserCity;
    }

    /**
     * @return mixed
     */
    public function getUserPLZ()
    {
        return $this->UserPLZ;
    }

    /**
     * @param mixed $UserPLZ
     */
    public function setUserPLZ($UserPLZ)
    {
        $this->UserPLZ = $UserPLZ;
    }

    /**
     * @return mixed
     */
    public function getUserAddress()
    {
        return $this->UserAddress;
    }

    /**
     * @param mixed $UserAddress
     */
    public function setUserAddress($UserAddress)
    {
        $this->UserAddress = $UserAddress;
    }


    public function __construct($newUserGender, $newUserFirstName, $newUserLastName, $newUserBirthday, $newUserName, $newUserPassword, $newUserEMail) {

        $this->setUserGender($newUserGender);
        $this->setUserFirstName($newUserFirstName);
        $this->setUserLastName($newUserLastName);
        $this->setUserBirthday($newUserBirthday);
        $this->setUserName($newUserName);
        $this->setUserPassword($newUserPassword);
        $this->setUserEMail($newUserEMail);
    }
}