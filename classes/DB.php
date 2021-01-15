<?php
class DB {

    public $host;
    public $user;
    public $password;
    public $database;
    public $connect;

    function __construct() {

        $this->host = 'localhost';
        $this->user = 'Kudernatsch';
        $this->password = 'kudi';
        $this->database = 'bennikudidb';

        $this->connect = new mysqli($this->host, $this->user, $this->password, $this->database);

        if($this->connect->connect_error) {

            return 'error';
        }
    }


    function getUserList() {

        $stmt = $this->connect->prepare("SELECT Username FROM tableuebung9");
        $stmt->execute();
        $result = $stmt->get_result();
        $i = 0;

        while($row = $result->fetch_assoc()) {

            $username = $row['Username'];
            $userarray[$i] = $this->getUser($username);
            $i++;
        }

        $stmt->close();
        $this->connect->close();
        return $userarray;
    }


    function getUserListEmails() {

        $stmt = $this->connect->prepare("SELECT EMailAdresse FROM tableuebung9");
        $stmt->execute();
        $result = $stmt->get_result();
        $i = 1;

        while($row = $result->fetch_assoc()) {

            $emailarray[$i] = $row['EMailAdresse'];
            $i++;
        }

        $stmt->close();
        $this->connect->close();
        return $emailarray;
    }


    function getUser($username) {

        try {

            $stmt = $this->connect->prepare("SELECT * FROM tableuebung9 WHERE Username=?");

            if($stmt === false) {

                die("Mysql Error: ".$this->connect->error);
            }

            $stmt->bind_param('s', $bindusername);

            $bindusername = $username;

            if($stmt->execute()) {

                $stmt->bind_result($id, $anrede, $vorname, $nachname, $adresse, $plz, $ort, $username, $passwort, $email, $date,$time);
                $stmt->fetch();
                include_once "model/User.class.php";
                $userObjekt = new User($anrede, $vorname, $nachname, $adresse, $plz, $ort, $username, $passwort, $email);
                $userObjekt->setId($id);
                $userObjekt->setLogindate($date);
                $userObjekt->setLogintime($time);
                return $userObjekt;
            }

            else {

                $userObjekt = NULL;
            }

            $stmt->close();

        } catch(Exception $e) {

        }
        return $userObjekt;
    }


    function getUserwithEmail($useremail) {

        $stmt = $this->connect->prepare("SELECT * FROM tableuebung9 WHERE EMailAdresse=?");

        if($stmt === false) {

            die("Mysql Error: ".$this->connect->error);
        }

        $stmt->bind_param('s', $bindemail);

        $bindemail = $useremail;

        if($stmt->execute()) {

            $stmt->bind_result($id, $anrede, $vorname, $nachname, $adresse, $plz, $ort, $username, $passwort, $email, $date, $time);
            $stmt->fetch();
            include_once "model/User.class.php";
            $userObjekt = new User($anrede, $vorname, $nachname, $adresse, $plz, $ort, $username, $passwort, $email);
            $userObjekt->setId($id);
            $userObjekt->setLogindate($date);
            $userObjekt->setLogintime($time);
            return $userObjekt;
        }

        else {

            $userObjekt = NULL;
        }

        $stmt->close();
    }


    function registerUser(User $userObjekt) {

        $stmt = $this->connect->prepare("INSERT INTO tableuebung9 (Anrede, Vorname, Nachname, Adresse, PLZ, Ort, Username, Passwort, EMailAdresse) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssissss', $bindanrede, $bindvorname, $bindnachname, $bindadresse, $bindplz, $bindort, $bindusername, $bindpasswort, $bindemail);
        $bindanrede = $userObjekt->getAnrede();
        $bindvorname = $userObjekt->getVorname();
        $bindnachname = $userObjekt->getNachname();
        $bindadresse = $userObjekt->getAdresse();
        $bindplz = $userObjekt->getPlz();
        $bindort = $userObjekt->getOrt();
        $bindusername = $userObjekt->getUsername();
        $bindpasswort = password_hash($userObjekt->getPasswort(),PASSWORD_DEFAULT);
        $bindemail = $userObjekt->getEmailadresse();

        if($stmt->execute()) {

            $returnvalue = true;
        }

        else {

            $returnvalue = false;
        }

        $stmt->close();
        $this->connect->close();
        return $returnvalue;
    }


    function updateUser(User $userObjekt) {

        $stmt = $this->connect->prepare("UPDATE tableuebung9 SET Anrede=?, Vorname=?, Nachname=?, Adresse=?, PLZ=?, Ort=?, Username=?, Passwort=?, EMailAdresse=? WHERE id=?");
        $stmt->bind_param("ssssissssi", $bindanrede, $bindvorname, $bindnachname, $bindadresse, $bindplz, $bindort, $bindusername, $bindpasswort, $bindemail, $bindid);
        $bindanrede = $userObjekt->getAnrede();
        $bindvorname = $userObjekt->getVorname();
        $bindnachname = $userObjekt->getNachname();
        $bindadresse = $userObjekt->getAdresse();
        $bindplz = $userObjekt->getPlz();
        $bindort = $userObjekt->getOrt();
        $bindusername = $userObjekt->getUsername();
        $bindpasswort = password_hash($userObjekt->getPasswort(),PASSWORD_DEFAULT);
        $bindemail = $userObjekt->getEmailadresse();
        $bindid = $userObjekt->getId();

        if($stmt->execute()) {

            $returnvalue = true;
        }

        else {

            $returnvalue = false;
        }

        $stmt->close();
        $this->connect->close();
        return $returnvalue;
    }


    function deleteUser($UserId) {

        $stmt = $this->connect->prepare("DELETE FROM tableuebung9 WHERE id=?");
        $stmt->bind_param("i", $UserId);

        if($stmt->execute()) {

            $returnvalue = true;
        }

        else {

            $returnvalue = false;
        }

        $stmt->close();
        $this->connect->close();
        return $returnvalue;
    }


    function loginUser($username, $password) {

        $stmt = $this->connect->prepare("SELECT Passwort FROM tableuebung9 WHERE Username=?");
        $stmt->bind_param("s", $username);
        if($stmt->execute()) {

            $stmt->bind_result($bindpasswort);
            $stmt->fetch();

            if(password_verify($password, $bindpasswort)) {

                $returnvalue = true;
            }

            else {

                $returnvalue = false;
            }
        }

        else {

            $returnvalue = false;
        }

        return $returnvalue;
    }


    function uploadFile($uploadfile) {

        $stmt = $this->connect->prepare("INSERT INTO tablefiles (File) VALUES (?)");
    }


    function logout($timestamp, $timestamp2, $username) {

        $stmt = $this->connect->prepare("UPDATE tableubeung9 SET `LastLogin`='?', `LastLoginTime`='?' WHERE Username=?");
        $stmt->bind_param("sss", $timestamp, $timestamp2, $username);


        if($stmt->execute()) {

            $returnvalue = true;
        }

        else {

            $returnvalue = false;
        }

        $stmt->close();
        $this->connect->close();
        return $returnvalue;
    }


    function changePassword($username, $oldpassword, $newpassword) {

        if($this->loginUser($username, $oldpassword)) {

            $stmt = $this->connect->prepare("UPDATE tableuebung9 SET Passwort=? WHERE Username=?");
            $stmt->bind_param("ss", $bindpasswort,$bindusername);
            $bindpasswort = password_hash($newpassword,PASSWORD_DEFAULT);
            $bindusername = $username;

            if($stmt->execute()) {

                $returnvalue = true;
            }

            else {

                $returnvalue = false;
            }

            $stmt->close();
            $this->connect->close();
        }

        else {

            $returnvalue = false;
        }

        return $returnvalue;
    }
}