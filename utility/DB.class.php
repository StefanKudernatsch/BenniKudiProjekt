<?php

class DB
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }


    function getUserList()
    {
        $users = array();
        $result = $this->db->query("SELECT * FROM usertable");
        while ($user = $result->fetch_assoc()) {
            $users[] = new User($user["UserGender"], $user["UserFirstname"], $user["UserLastName"], $user["UserBirthday"], $user["UserName"], $user["UserPassword"], $user["UserEMail"]);
        }
        return $users;
    }

    function getUser($username)
    {
        $sql = "SELECT * FROM user WHERE username = ?;";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return new User($user["UserGender"], $user["UserFirstname"], $user["UserLastName"], $user["UserBirthday"], $user["UserName"], $user["UserPassword"], $user["UserEMail"]);
    }

    function getUserMail($mail)
    {
        $sql = "SELECT * FROM user WHERE emailaddress = ?;";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return new User($user["UserGender"], $user["UserFirstname"], $user["UserLastName"], $user["UserBirthday"], $user["UserName"], $user["UserPassword"], $user["UserEMail"]);
    }


    function registerUser($user_object)
    {

        $sql = "INSERT INTO user (Gender,FirstName,LastName,UserBirthday, UserImage, Username, Password, EMailaddress,City,PLZ,UserAddress) VALUES (?,?,?,?,?,?,?,?,?,?,?);";

        $stmt = $this->db->prepare($sql);

        $gender=$user_object->getUserGender();
        $firstname=$user_object->getUserFirstName();
        $lastnamer=$user_object->getUserLastName();
        $birthday=$user_object->getUserBirthday();
        $image=$user_object->getUserImage();
        $username=$user_object->getUserName();
        $password=password_hash($user_object->getUserPasssword(), PASSWORD_DEFAULT);

        //$stmt->bind_param("ssssissss", $anrede, $vorname, $nachname, $adresse, $plz, $ort, $username, $password, $emailadresse);

        $ergebnis = $stmt->execute();

        return $ergebnis;


    }

    function updateUser($user_object)
    {
        $sql = "UPDATE user SET Anrede= ? ,Vorname = ?,Nachname = ?,Adresse = ?,PLZ = ?,Ort = ?,Username = ?,EMailAdresse = ? WHERE id = ?;";

        $stmt = $this->db->prepare($sql);

        $anrede = $user_object->get_anrede();
        $vorname = $user_object->get_vorname();
        $nachname = $user_object->get_nachname();
        $adresse = $user_object->get_adresse();
        $plz = $user_object->get_plz();
        $ort = $user_object->get_ort();
        $username = $user_object->get_username();
        $emailadresse = $user_object->get_emailadresse();
        $id = $user_object->get_id();

        $stmt->bind_param("ssssisssi", $anrede, $vorname, $nachname, $adresse, $plz, $ort, $username, $emailadresse, $id);

        $ergebnis = $stmt->execute();

        if ($_SESSION["user"] != "admin") {
            $_SESSION["user"] = $username;
        }

        return $ergebnis;

    }

    function updateUserPW($user_object)
    {
        $sql = "UPDATE user SET Passwort = ? WHERE id = ?;";

        $stmt = $this->db->prepare($sql);

        $password = password_hash($user_object->get_password(), PASSWORD_DEFAULT);

        $id = $user_object->get_id();

        $stmt->bind_param("si", $password, $id);

        $ergebnis = $stmt->execute();


        return $ergebnis;

    }

    function deleteUser($user_id)
    {
        $sql = "DELETE FROM user WHERE id = ?;";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('i', $user_id);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }

    function loginUser($username, $password)
    {
        $user = $this->getUser($username);

        if (password_verify($password, $user->get_password())) {
            //echo "Valides Passwort";
            $_SESSION["user"] = $user->get_username();

            return true;
        } else if ($password == $user->get_password()) {
            $_SESSION["user"] = $user->get_username();

            return true;
        } else {

            //echo "Ungültiges Passwort";
            return false;
        }
    }

    function fileUpload($username, $file)
    {
        $sql = "INSERT INTO user_files (file,username) VALUES (?,?);";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("bs", $file, $username);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }


    function createUsertimestamp($timestamp, $id)
    {
        $sql = "UPDATE user SET timestamp = ? WHERE id = ?;";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("si", $timestamp, $id);

        $ergebnis = $stmt->execute();


        return $ergebnis;

    }

    function user_liked($username, $liketype, $fileID)
    {
        $sql = "SELECT * FROM likes where username = ? AND liketype = ? AND fileID = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("sii", $username, $liketype, $fileID);

        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        return $rowcount;
    }

    function getLikeNumber($liketype, $fileID)
    {
        $sql = "SELECT * FROM likes where liketype = ? AND fileID = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("si", $liketype, $fileID);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        return $rowcount;

    }

    function addLike($liketype, $username, $fileid)
    {
        $sql = "INSERT INTO likes (liketype,username,fileID) VALUES (?,?,?);";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("isi", $liketype, $username, $fileid);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }

    function removeLike($liketype, $username, $fileid)
    {
        $sql = "DELETE FROM likes WHERE liketype = ? AND username = ? AND fileID = ?;";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("isi", $liketype, $username, $fileid);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }


    function requestFriend($sender, $receiver, $status)
    {
        $sql = "INSERT INTO friends (sender,receiver,status) VALUES (?,?,?);";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("sss", $sender, $receiver, $status);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }

    function is_requested($sender, $receiver, $status)
    {
        $sql = "SELECT * FROM friends where sender = ? AND receiver = ? AND status = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("sss", $sender, $receiver, $status);

        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        if ($rowcount >= 1) {
            return true;
        } else {
            return false;
        }
    }

    function acceptFriend($sender, $receiver)
    {
        $status = "accepted";
        $sql = "UPDATE friends SET status = ? WHERE sender = ? AND receiver = ?;";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("sss", $status, $sender, $receiver);

        $ergebnis = $stmt->execute();


        return $ergebnis;

    }

    function declineFriend($sender, $receiver)
    {
        $sql = "DELETE FROM friends WHERE sender = ? AND receiver = ?;";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $sender, $receiver);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }

    function isFriend($friend1, $friend2){
        $sql = "SELECT * FROM friends where sender = ? AND receiver = ? AND status = ?";
        $stmt = $this->db->prepare($sql);
        $status="accepted";

        $stmt->bind_param("sss", $friend1,$friend2,$status );
        $stmt->execute();
        $stmt->store_result();
        $rowcount1 = $stmt->num_rows();

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $friend2,$friend1,$status );
        $stmt->execute();
        $stmt->store_result();
        $rowcount2 = $stmt->num_rows();

        if ($rowcount1 == 1 || $rowcount2==1) {
            return true;
        }
        else {
            return false;
        }
    }


    function addComment($comment, $username, $fileid)
    {
        $sql = "INSERT INTO comments (comment,username,fileID) VALUES (?,?,?);";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("ssi", $comment, $username, $fileid);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }

    function getCommentList()
    {

        $comments = array();
        /*$sql= "SELECT * from comments where fileID = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("i",  $fileID);

        $stmt->execute();*/

        $result = $this->db->query("SELECT * FROM comments");

        while ($user = $result->fetch_assoc()) {
            $comments[] = new Comment($user["commentID"], $user["comment"], $user["username"], $user["fileID"]);
        }
        return $comments;
    }

    function deleteComment($commentID)
    {
        $sql = "DELETE FROM comments WHERE commentID = ?;";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $commentID);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }

    function editComment($commentID,$comment)
    {
        $sql = "UPDATE comments SET comment = ? WHERE commentid = ?;";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si",$comment, $commentID);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }



}
