<?php

class DB
{
    public $host;
    public $user;
    public $password;
    public $database;
    public $connect;

    function __construct()
    {

        $this->host = 'localhost';
        $this->user = 'Kudernatsch';
        $this->password = 'kudi';
        $this->database = 'bennikudidb';

        $this->connect = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->connect->connect_error) {

            return 'error';
        }
    }

    function getUserList()
    {
        $users = array();
        $result = $this->connect->query("SELECT * FROM usertable");
        while ($user = $result->fetch_assoc()) {

             $tempuser = new User($user["Gender"], $user["FirstName"], $user["LastName"], $user["UserImage"], $user["UserBirthDay"], $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"]);
             $tempuser->setUserID($user["UserID"]);
             $users[]=$tempuser;
        }
        return $users;
    }

    function getUserListEmails()
    {

        $stmt = $this->connect->prepare("SELECT EMailAddress FROM usertable");
        $stmt->execute();
        $result = $stmt->get_result();
        $i = 1;

        while ($row = $result->fetch_assoc()) {

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
        $tempuser = new User($user["Gender"], $user["FirstName"], $user["LastName"], $user["UserImage"], date('Y-m-d', $user["UserBirthDay"]), $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"]);
        //echo $user["UserBirthDay"];
        //echo $tempuser->getUserBirthday();
        $tempuser->setUserID($user["UserID"]);
        return $tempuser;
    }

    function getUserWithID($user_id) {

        $sql = "SELECT * FROM usertable WHERE UserID = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $tempuser = new User($user["Gender"], $user["FirstName"], $user["LastName"], $user["UserImage"], date('Y-m-d', $user["UserBirthDay"]), $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"]);
        //echo $user["UserBirthDay"];
        //echo $tempuser->getUserBirthday();
        $tempuser->setUserID($user["UserID"]);
        return $tempuser;
    }

    function getUserImage($userid)
    {
        $stmt = $this->connect->prepare("SELECT UserImage FROM usertable WHERE UserID=?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($image);
        $stmt->fetch();
        return $image;
    }

    function getUserMail($mail)
    {
        $sql = "SELECT * FROM usertable WHERE EMailAddress = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('s', $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return new User($user["Gender"], $user["FirstName"], $user["LastName"], $user["UserImage"], $user["UserBirthDay"], $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"]);
    }

    function uploadImage($image, $userid)
    {
        echo $userid;
        echo $image;
        $imagename = $image["name"];
        $imagetmpname = $image["tmp_name"];
        $imagetype = $image["type"];
        echo $imagetmpname;
        echo $imagetype;
        echo $imagename;
        $sql = "UPDATE usertable SET UserImage=? WHERE UserID = " . $userid . ";";
        $stmt = $this->connect->prepare($sql);
        $null = "NULL";
        $stmt->bind_param("b", $null);
        $stmt->send_long_data(0, file_get_contents($imagetmpname));
        $ergebnis = $stmt->execute();
        return $ergebnis;

    }

    function registerUser(User $user_object)
    {

        $sql = "INSERT INTO usertable (Gender,FirstName,LastName,UserBirthDay, Username, Password, EMailAddress,City,PLZ,UserAddress) VALUES (?,?,?,?,?,?,?,?,?,?);";

        $stmt = $this->connect->prepare($sql);

        $gender = $user_object->getUserGender();
        $firstname = $user_object->getUserFirstName();
        $lastname = $user_object->getUserLastName();
        $birthday = $user_object->getUserBirthday();
        $image = $user_object->getUserImage();
        $image = file_get_contents($image['tmp_name']);

        //echo $image;
        //echo '<img src="data:image/png;base64,'.base64_encode( $image ).'"/>';
        $username = $user_object->getUserName();
        $password = password_hash($user_object->getUserPassword(), PASSWORD_DEFAULT);
        $email = $user_object->getUserEmail();
        $city = $user_object->getUserCity();
        $plz = $user_object->getUserPLZ();
        $address = $user_object->getUserAddress();

        $stmt->bind_param("ssssssssis", $gender, $firstname, $lastname, $birthday, $username, $password, $email, $city, $plz, $address);


        $ergebnis = $stmt->execute();

        return $ergebnis;


    }


    function updateUser($user_object)
    {
        $sql = "UPDATE usertable SET Gender = ?,FirstName = ?,LastName = ?,UserBirthDay = ?, UserImage = ?, Username = ?, EMailAddress = ?,City = ?,PLZ = ?,UserAddress = ? WHERE UserID = ?;";

        $stmt = $this->connect->prepare($sql);

        $gender = $user_object->getUserGender();
        $firstname = $user_object->getUserFirstName();
        $lastname = $user_object->getUserLastName();
        $birthday = $user_object->getUserBirthday();
        $image = $user_object->getUserImage();
        $username = $user_object->getUserName();
        $email = $user_object->getUserEmail();
        $city = $user_object->getUserCity();
        $plz = $user_object->getUserPLZ();
        $address = $user_object->getUserAddress();
        $id = $user_object->getUserID();

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


    function uploadFile($uploadfile)
    {

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

    function user_liked($userid, $liketype, $fileID)
    {
        $sql = "SELECT * FROM liketable where userid = ? AND liketype = ? AND fileID = ?";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("iii", $userid, $liketype, $fileID);

        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        return $rowcount;
    }

    function getLikeNumber($liketype, $fileID)
    {
        $sql = "SELECT * FROM liketable where liketype = ? AND fileID = ?";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("ii", $liketype, $fileID);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        return $rowcount;

    }

    function addLike($liketype, $userid, $fileid)
    {
        $sql = "INSERT INTO liketable (liketype,userid,fileid) VALUES (?,?,?);";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("iii", $liketype, $userid, $fileid);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }

    function removeLike($liketype, $userid, $fileid)
    {
        $sql = "DELETE FROM liketable WHERE liketype = ? AND userid = ? AND fileID = ?;";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("iii", $liketype, $userid, $fileid);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }


    function requestFriend($sender, $receiver, $status)
    {
        $sql = "INSERT INTO friendtable (senderid,receiverid,status) VALUES (?,?,?);";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("iis", $sender, $receiver, $status);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }

    function is_requested($sender, $receiver, $status)
    {
        $sql = "SELECT * FROM friendtable where senderid = ? AND receiverid = ? AND status = ?";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("iis", $sender, $receiver, $status);

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
        $sql = "UPDATE friendtable SET status = ? WHERE senderid = ? AND receiverid = ?;";

        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("sii", $status, $sender, $receiver);

        $ergebnis = $stmt->execute();


        return $ergebnis;

    }

    function declineFriend($sender, $receiver)
    {
        $sql = "DELETE FROM friendtable WHERE senderid = ? AND receiverid = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("ii", $sender, $receiver);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }

    function isFriend($friend1, $friend2)
    {
        $sql = "SELECT * FROM friendtable where sender = ? AND receiver = ? AND status = ?";
        $stmt = $this->connect->prepare($sql);
        $status = "accepted";

        $stmt->bind_param("sss", $friend1, $friend2, $status);
        $stmt->execute();
        $stmt->store_result();
        $rowcount1 = $stmt->num_rows();

        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("sss", $friend2, $friend1, $status);
        $stmt->execute();
        $stmt->store_result();
        $rowcount2 = $stmt->num_rows();

        if ($rowcount1 == 1 || $rowcount2 == 1) {
            return true;
        } else {
            return false;
        }
    }

    function getFriendList($user_id) {

        $friendarray = NULL;
        $sql = "SELECT SenderID FROM friendtable WHERE ReceiverID = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $friendcounter = 0;

        while($row = $result->fetch_assoc()) {

            $friendID = $row['SenderID'];
            $friendarray[$friendcounter] = $this->getUserWithID($friendID);
            $friendcounter++;
        }

        $sql = "SELECT ReceiverID FROM friendtable WHERE SenderID = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()) {

            $friendID = $row['SenderID'];
            $friendarray[$friendcounter] = $this->getUserWithID($friendID);
            $friendcounter++;
        }
        return $friendarray;
    }


    function addComment($comment, $userid, $fileid)
    {
        $sql = "INSERT INTO commenttable (commenttext,userid,fileID) VALUES (?,?,?);";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("sii", $comment, $userid, $fileid);

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

        $result = $this->connect->query("SELECT * FROM commenttable");

        while ($comment = $result->fetch_assoc()) {
             $tempcomment = new Comment($comment["CommentText"], $comment["UserID"], $comment["FileID"]);
             $tempcomment->setCommentID($comment["CommentID"]);
            $comments[] = $tempcomment;
        }
        return $comments;
    }

    function deleteComment($commentID)
    {
        $sql = "DELETE FROM commenttable WHERE commentID = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("i", $commentID);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }

    function editComment($commentID, $comment)
    {
        $sql = "UPDATE commenttable SET CommentText = ? WHERE CommentID = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("si", $comment, $commentID);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }

    function getCommentNumber($fileID)
    {
        $sql = "SELECT * FROM commenttable where fileID = ?";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("i",$fileID);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        return $rowcount;
    }
}