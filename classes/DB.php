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


    function getUserList()
    {
        $users = array();
        $result = $this->connect->query("SELECT * FROM usertable");
        while ($user = $result->fetch_assoc()) {
            $users[] = new User($user["Gender"], $user["FirstName"], $user["LastName"],$user["UserImage"], $user["UserBirthDay"], $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"]);
        }
        return $users;
    }


    function getUserListEmails() {

        $stmt = $this->connect->prepare("SELECT EMailAddress FROM usertable");
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


    function getUser($username)
    {
        $sql = "SELECT * FROM usertable WHERE Username = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return new User($user["Gender"], $user["FirstName"], $user["LastName"],$user["UserImage"], $user["UserBirthDay"], $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"]);
    }


    function getUserMail($mail)
    {
        $sql = "SELECT * FROM usertable WHERE EMailAddress = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('s', $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return new User($user["Gender"], $user["FirstName"], $user["LastName"],$user["UserImage"], $user["UserBirthDay"], $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"]);
    }


    function registerUser(User $user_object)
    {

        $sql = "INSERT INTO usertable (Gender,FirstName,LastName,UserBirthDay, UserImage, Username, Password, EMailAddress,City,PLZ,UserAddress) VALUES (?,?,?,?,?,?,?,?,?,?,?);";

        $stmt = $this->connect->prepare($sql);

        $gender=$user_object->getUserGender();
        $firstname=$user_object->getUserFirstName();
        $lastname=$user_object->getUserLastName();
        $birthday=$user_object->getUserBirthday();
        $image=$user_object->getUserImage();
        //echo $image;
        //echo '<img src="data:image/png;base64,'.base64_encode( $image ).'"/>';
        $username=$user_object->getUserName();
        $password=password_hash($user_object->getUserPassword(), PASSWORD_DEFAULT);
        $email=$user_object->getUserEmail();
        $city=$user_object->getUserCity();
        $plz=$user_object->getUserPLZ();
        $address=$user_object->getUserAddress();

        $stmt->bind_param("ssssbssssis", $gender, $firstname, $lastname, $birthday, $image, $username, $password, $email, $city, $plz, $address);

        //$stmt->send_long_data(0)
        $ergebnis = $stmt->execute();

        return $ergebnis;


    }


    function updateUser($user_object)
    {
        $sql = "UPDATE usertable SET Gender = ?,FirstName = ?,LastName = ?,UserBirthDay = ?, UserImage = ?, Username = ?, EMailAddress = ?,City = ?,PLZ = ?,UserAddress = ? WHERE UserID = ?;";

        $stmt = $this->connect->prepare($sql);

        $gender=$user_object->getUserGender();
        $firstname=$user_object->getUserFirstName();
        $lastname=$user_object->getUserLastName();
        $birthday=$user_object->getUserBirthday();
        $image=$user_object->getUserImage();
        $username=$user_object->getUserName();
        $email=$user_object->getUserEmail();
        $city=$user_object->getUserCity();
        $plz=$user_object->getUserPLZ();
        $address=$user_object->getUserAddress();
        $id=$user_object->getUserID();

        $stmt->bind_param("ssssbsssisi", $gender, $firstname, $lastname, $birthday, $image, $username, $password, $email, $city, $plz, $address, $id);

        $ergebnis = $stmt->execute();

        if ($_SESSION["user"] != "admin") {
            $_SESSION["user"] = $username;
        }

        return $ergebnis;

    }


    function deleteUser($user_id)
    {
        $sql = "DELETE FROM usertable WHERE UserID = ?;";

        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param('i', $user_id);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }


    function loginUser($username, $password)
    {
        $user = $this->getUser($username);

        if (password_verify($password, $user->getUserPassword())) {
            $_SESSION["SessionUserName"] = $user->getUserName();

            return true;
        } else if ($password == $user->getUserPassword()) {
            $_SESSION["SessionUserName"] = $user->getUserName();

            return true;
        } else {

            return false;
        }
    }


    function uploadFile($uploadfile) {

        $stmt = $this->connect->prepare("INSERT INTO tablefiles (File) VALUES (?)");
    }



    function updateUserPW($user_object)
    {
        $sql = "UPDATE usertable SET Password = ? WHERE id = ?;";

        $stmt = $this->connect->prepare($sql);

        $password = password_hash($user_object->getUserPassword(), PASSWORD_DEFAULT);

        $id = $user_object->getUserID();

        $stmt->bind_param("si", $password, $id);

        $ergebnis = $stmt->execute();


        return $ergebnis;

    }

    function user_liked($username, $liketype, $fileID)
    {
        $sql = "SELECT * FROM likes where username = ? AND liketype = ? AND fileID = ?";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("sii", $username, $liketype, $fileID);

        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        return $rowcount;
    }

    function getLikeNumber($liketype, $fileID)
    {
        $sql = "SELECT * FROM likes where liketype = ? AND fileID = ?";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("si", $liketype, $fileID);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        return $rowcount;

    }

    function addLike($liketype, $username, $fileid)
    {
        $sql = "INSERT INTO likes (liketype,username,fileID) VALUES (?,?,?);";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("isi", $liketype, $username, $fileid);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }

    function removeLike($liketype, $username, $fileid)
    {
        $sql = "DELETE FROM likes WHERE liketype = ? AND username = ? AND fileID = ?;";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("isi", $liketype, $username, $fileid);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }


    function requestFriend($sender, $receiver, $status)
    {
        $sql = "INSERT INTO friends (sender,receiver,status) VALUES (?,?,?);";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("sss", $sender, $receiver, $status);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }

    function is_requested($sender, $receiver, $status)
    {
        $sql = "SELECT * FROM friends where sender = ? AND receiver = ? AND status = ?";
        $stmt = $this->connect->prepare($sql);

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

        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("sss", $status, $sender, $receiver);

        $ergebnis = $stmt->execute();


        return $ergebnis;

    }

    function declineFriend($sender, $receiver)
    {
        $sql = "DELETE FROM friends WHERE sender = ? AND receiver = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("ss", $sender, $receiver);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }

    function isFriend($friend1, $friend2){
        $sql = "SELECT * FROM friends where sender = ? AND receiver = ? AND status = ?";
        $stmt = $this->connect->prepare($sql);
        $status="accepted";

        $stmt->bind_param("sss", $friend1,$friend2,$status );
        $stmt->execute();
        $stmt->store_result();
        $rowcount1 = $stmt->num_rows();

        $stmt = $this->connect->prepare($sql);
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
        $stmt = $this->connect->prepare($sql);

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

        $result = $this->connect->query("SELECT * FROM comments");

        while ($user = $result->fetch_assoc()) {
            $comments[] = new Comment($user["commentID"], $user["comment"], $user["username"], $user["fileID"]);
        }
        return $comments;
    }

    function deleteComment($commentID)
    {
        $sql = "DELETE FROM comments WHERE commentID = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("i", $commentID);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }

    function editComment($commentID,$comment)
    {
        $sql = "UPDATE comments SET comment = ? WHERE commentid = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("si",$comment, $commentID);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }
}