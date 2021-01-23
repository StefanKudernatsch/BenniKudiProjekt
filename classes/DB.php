<?php
require_once ("File.php");

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
        $result = $this->connect->query("SELECT UserID FROM usertable");
        while ($user = $result->fetch_assoc()) {
            if($user["UserID"]!=1) {
                $tempuser = $this->getUserWithID($user["UserID"]);
                $tempuser->setUserID($user["UserID"]);
                $users[]=$tempuser;
            }
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

            if($row['EMailAdresse']!='admin@admin.com'){
                $emailarray[$i] = $row['EMailAdresse'];
                $i++;
            }
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
        $tempuser = new User($user["Gender"], $user["FirstName"], $user["LastName"], $user["UserBirthDay"], $user["UserImage"],  $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"], $user["UserActive"]);
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
        $tempuser = new User($user["Gender"], $user["FirstName"], $user["LastName"], $user["UserBirthDay"], $user["UserImage"], $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"], $user["UserActive"]);
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
        return new User($user["Gender"], $user["FirstName"], $user["LastName"], $user["UserImage"], $user["UserBirthDay"], $user["Username"], $user["Password"], $user["EMailAddress"], $user["City"], $user["PLZ"], $user["UserAddress"], $user["UserActive"]);
    }

    function uploadImage($image, $userid)
    {
        $sql = "UPDATE usertable SET UserImage=? WHERE UserID = " . $userid . ";";
        $stmt = $this->connect->prepare($sql);
        $null = "NULL";
        $stmt->bind_param("b", $null);
        $stmt->send_long_data(0, file_get_contents($image));
        $ergebnis = $stmt->execute();
        return $ergebnis;

    }

    function registerUser(User $user_object)
    {

        $sql = "INSERT INTO usertable (Gender,FirstName,LastName,UserBirthDay, Username, Password, EMailAddress,City,PLZ,UserAddress,UserActive) VALUES (?,?,?,?,?,?,?,?,?,?,?);";

        $stmt = $this->connect->prepare($sql);

        $gender = $user_object->getUserGender();
        $firstname = $user_object->getUserFirstName();
        $lastname = $user_object->getUserLastName();
        $birthday = $user_object->getUserBirthday();
        $username = $user_object->getUserName();
        $password = password_hash($user_object->getUserPassword(), PASSWORD_DEFAULT);
        $email = $user_object->getUserEmail();
        $city = $user_object->getUserCity();
        $plz = $user_object->getUserPLZ();
        $address = $user_object->getUserAddress();
        $active = $user_object->getUserActive();

        $stmt->bind_param("ssssssssisi", $gender, $firstname, $lastname, $birthday, $username, $password, $email, $city, $plz, $address, $active);


        $ergebnis = $stmt->execute();

        return $ergebnis;


    }


    function updateUser($user_object)
    {
        $sql = "UPDATE usertable SET Gender = ?,FirstName = ?,LastName = ?,UserBirthDay = ?,Username = ?,EMailAddress = ?,City = ?,PLZ = ?,UserAddress = ? WHERE UserID = ?;";
        $stmt = $this->connect->prepare($sql);

        $gender = $user_object->getUserGender();
        $firstname = $user_object->getUserFirstName();
        $lastname = $user_object->getUserLastName();
        $birthday = $user_object->getUserBirthday();
        $username = $user_object->getUserName();
        $email = $user_object->getUserEmail();
        $city = $user_object->getUserCity();
        $plz = $user_object->getUserPLZ();
        $address = $user_object->getUserAddress();
        $id = $user_object->getUserID();

        $stmt->bind_param("sssssssisi", $gender, $firstname, $lastname, $birthday, $username, $email, $city, $plz, $address, $id);

        $ergebnis = $stmt->execute();

        if ($_SESSION["SessionUserName"] == "admin") {
            header("Location: ");
        } else {
            $_SESSION["SessionUserName"] = $username;
        }

        return $ergebnis;
    }


    function deleteUser($user_id)
    {
        /*
         * 0 - successful removal of all data
         * 1 - failed at removing all related comments
         * 2 - failed at removing all related files
         * 3 - failed at removing all related friends
         * 4 - failed at removing all related likes
         * 5 - failed at removing all related messages
         * 6 - failed at removing all account details
         */
        $sql = "DELETE FROM commenttable WHERE UserID = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('i', $user_id);
        if($stmt->execute()) {
            $sql = "DELETE FROM filetable WHERE UserID = ?;";
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param('i', $user_id);
            if($stmt->execute()) {
                $sql = "DELETE FROM friendtable WHERE SenderID = ? OR ReceiverID = ?;";
                $stmt = $this->connect->prepare($sql);
                $stmt->bind_param('ii', $user_id, $user_id);
                if($stmt->execute()) {
                    $sql = "DELETE FROM liketable WHERE UserID = ?;";
                    $stmt = $this->connect->prepare($sql);
                    $stmt->bind_param('i', $user_id);
                    if($stmt->execute()) {
                        $sql = "DELETE FROM messagetable WHERE SenderID = ? OR ReceiverID = ?;";
                        $stmt = $this->connect->prepare($sql);
                        $stmt->bind_param('ii', $user_id, $user_id);
                        if($stmt->execute()) {
                            $sql = "DELETE FROM usertable WHERE UserID = ?;";
                            $stmt = $this->connect->prepare($sql);
                            $stmt->bind_param('i', $user_id);
                            if($stmt->execute()) {
                                return 0;
                            } else {
                                return 6;
                            }
                        } else {
                            return 5;
                        }
                    } else {
                        return 4;
                    }
                } else {
                    return 3;
                }
            } else {
                return 2;
            }
        } else {
            return 1;
        }
    }


    function changeUserActive($user_active,$user_id) {

        $sql = "UPDATE usertable SET UserActive = ? WHERE UserID = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("ii", $user_active, $user_id);
        $ergebnis = $stmt->execute();

        return $ergebnis;
    }


    function resetPassword($user_id, $user_email) {

        $sql = "UPDATE usertable SET Password = ? WHERE EMailAddress = ?;";
        $stmt = $this->connect->prepare($sql);
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $password = implode($password);
        //sendemail($password, $user_email);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("si", $password, $user_id);
        $ergebnis = $stmt->execute();

        return $ergebnis;
    }


    function loginUser($username, $password)
    {
        $user = $this->getUser($username);

        if (password_verify($password, $user->getUserPassword())) {
            $_SESSION["SessionUserName"] = $user->getUserName();

            return true;
        } else {

            return false;
        }
    }

    function updateUserPW($userid, $oldPW, $newPW)
    {
        if (password_verify($oldPW, $this->getUserWithID($userid)->getUserPassword())) {

            $sql = "UPDATE usertable SET Password = ? WHERE UserID = ?;";
            $stmt = $this->connect->prepare($sql);
            $password = password_hash($newPW, PASSWORD_DEFAULT);
            $stmt->bind_param("si", $password, $userid);
            if($stmt->execute()) {

                return 0;
            } else {

                return 1;
            }
        } else {
            return 2;
        }
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
        $stmt->execute();

        $sql = "DELETE FROM friendtable WHERE senderid = ? AND receiverid = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("ii", $receiver, $sender);
        $stmt->execute();
        return true;
    }

    function getRequestedList($user_id){
        $status = "pending";
        $friendarray = NULL;
        $sql = "SELECT SenderID FROM friendtable WHERE ReceiverID = ? AND status = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('is', $user_id, $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $friendcounter = 0;

        while($row = $result->fetch_assoc()) {
            if($row['SenderID']!=1) {
                $friendID = $row['SenderID'];
                $friendarray[$friendcounter] = $this->getUserWithID($friendID);
                $friendcounter++;
            }
        }
        return $friendarray;
    }

    function sentRequest($friend1, $friend2){
        $status = "pending";
        $sql = "SELECT * FROM friendtable where SenderID = ? AND ReceiverID = ? AND Status = ?";
        $stmt = $this->connect->prepare($sql);


        $stmt->bind_param("iis", $friend1, $friend2, $status);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        if($rowcount == 1){
            return true;
        }
        else{
            return false;
        }
    }

    function receivedRequest($friend1, $friend2){
        $status = "pending";
        $sql = "SELECT * FROM friendtable where ReceiverID = ? AND SenderID = ? AND Status = ?";
        $stmt = $this->connect->prepare($sql);


        $stmt->bind_param("iis", $friend1, $friend2, $status);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        if($rowcount == 1){
            return true;
        }
        else{
            return false;
        }
    }



    function isFriend($friend1, $friend2)
    {
        $status = "accepted";
        $sql = "SELECT * FROM friendtable where SenderID = ? AND ReceiverID = ? AND Status = ?";
        $stmt = $this->connect->prepare($sql);


        $stmt->bind_param("iis", $friend1, $friend2, $status);
        $stmt->execute();
        $stmt->store_result();
        $rowcount1 = $stmt->num_rows();


        $stmt2 = $this->connect->prepare($sql);
        $stmt2->bind_param("iis", $friend2, $friend1, $status);
        $stmt2->execute();
        $stmt2->store_result();
        $rowcount2 = $stmt2->num_rows();

        if ($rowcount1 == 1 || $rowcount2 == 1) {
            return true;
        } else {
            return false;
        }
    }

    function getFriendList($user_id) {
        $status = "accepted";
        $friendarray = NULL;
        $sql = "SELECT SenderID FROM friendtable WHERE ReceiverID = ? AND status = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('is', $user_id, $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $friendcounter = 0;

        while($row = $result->fetch_assoc()) {
            if($row['SenderID']!=1) {
                $friendID = $row['SenderID'];
                $friendarray[$friendcounter] = $this->getUserWithID($friendID);
                $friendcounter++;
            }
        }

        $sql = "SELECT ReceiverID FROM friendtable WHERE SenderID = ? AND status = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('is', $user_id, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()) {
            if($row['ReceiverID']!=1) {
                $friendID = $row['ReceiverID'];
                $friendarray[$friendcounter] = $this->getUserWithID($friendID);
                $friendcounter++;
            }
        }
        return $friendarray;
    }

    function getPendingNumber($receiverid){
        $status = "pending";
        $sql = "SELECT * FROM friendtable where receiverID = ? AND status = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("is",$receiverid, $status);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        return $rowcount;
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

    function addMessage($senderid, $receiverid, $messagetext){
        $time = date('Y-m-d H:i:s');
        $status = false;
        $sql = "INSERT INTO messagetable (messagetext,senderid,receiverid,status,TimeSent) VALUES (?,?,?,?,?);";
        $stmt = $this->connect->prepare($sql);

        $stmt->bind_param("siiis", $messagetext, $senderid, $receiverid, $status, $time);

        $ergebnis = $stmt->execute();

        return $ergebnis;
    }

    function ReadMessage($senderid, $receiverid){
        $status = true;
        $sql = "UPDATE messagetable SET Status = ? WHERE SenderID = AND ReceiverID = ?;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("iii",$status, $senderid, $receiverid);
        $ergebnis = $stmt->execute();
        return $ergebnis;
    }


    function getMessages($senderid, $receiverid){
        $messages = array();
        /*$sql= "SELECT * from comments where fileID = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("i",  $fileID);

        $stmt->execute();*/

        $sql="SELECT * FROM messagetable WHERE SenderID = ? AND ReceiverID = ?;";

        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('ii', $senderid, $receiverid);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($message = $result->fetch_assoc()) {
            $tempmessage = new Message($message["MessageText"], $message["SenderID"], $message["ReceiverID"], $message["Status"], $message["TimeSent"]);
            $tempmessage->setMessageID($message["MessageID"]);
            $messages[] = $tempmessage;
        }

        $sql2 = "SELECT * FROM messagetable WHERE SenderID = ? AND ReceiverID = ?;";
        $stmt = $this->connect->prepare($sql2);
        $stmt->bind_param('ii', $receiverid, $senderid);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($message = $result->fetch_assoc()) {
            $tempmessage = new Message($message["MessageText"], $message["SenderID"], $message["ReceiverID"], $message["Status"], $message["TimeSent"]);
            $tempmessage->setMessageID($message["MessageID"]);
            $messages[] = $tempmessage;
        }
        return $messages;
    }

    function getPublicFiles() {
        $result = $this->connect->query("SELECT * FROM filetable WHERE ShowType = 1 ORDER BY FileDate DESC;");

        while ($file = $result->fetch_assoc()) {
            $temp_file = new File($file["FileName"], $file["UserID"], $file["FileDate"], $file["TagID"], $file["ShowType"], $file["FileType"], $file["FileText"], $file["FilePath"]);
            $temp_file->setFileID($file["FileID"]);
            $files[] = $temp_file;
        }
        return $files;
    }

    function getAllFiles() {
        $result = $this->connect->query("SELECT * FROM filetable ORDER BY FileDate DESC;");

        while ($file = $result->fetch_assoc()) {
            $temp_file = new File($file["FileName"], $file["UserID"], $file["FileDate"], $file["TagID"], $file["ShowType"], $file["FileType"], $file["FileText"], $file["FilePath"]);
            $temp_file->setFileID($file["FileID"]);
            $files[] = $temp_file;
        }
        return $files;
    }

    function getUserFiles($user_id) {
        $sql = "SELECT * FROM filetable WHERE UserID = ? ORDER BY FileDate DESC;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($file = $result->fetch_assoc()) {
            $temp_file = new File($file["FileName"], $file["UserID"], $file["FileDate"], $file["TagID"], $file["ShowType"], $file["FileType"], $file["FileText"], $file["FilePath"]);
            $temp_file->setFileID($file["FileID"]);
            $files[] = $temp_file;
        }
        return $files;
    }

    function getFriendFiles() {
        $sql = "SELECT * FROM filetable WHERE UserID = ? AND ShowType = 0 ORDER BY FileDate DESC;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($file = $result->fetch_assoc()) {
            $temp_file = new File($file["FileName"], $file["UserID"], $file["FileDate"], $file["TagID"], $file["ShowType"], $file["FileType"], $file["FileText"], $file["FilePath"]);
            $temp_file->setFileID($file["FileID"]);
            $files[] = $temp_file;
        }
        return $files;
    }

    function getTagFiles() {
        $sql = "SELECT * FROM filetable WHERE TagID = ? ORDER BY FileDate DESC;";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($file = $result->fetch_assoc()) {
            $temp_file = new File($file["FileName"], $file["UserID"], $file["FileDate"], $file["TagID"], $file["ShowType"], $file["FileType"], $file["FileText"], $file["FilePath"]);
            $temp_file->setFileID($file["FileID"]);
            $files[] = $temp_file;
        }
        return $files;
    }
}