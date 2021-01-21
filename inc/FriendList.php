<?php
$DB = new DB();
if($_SESSION['SessionUserName'] == 'admin') {
    $userlist = $DB->getUserList();
} else {
    $tempuser = $DB->getUser($_SESSION["SessionUserName"]);
}

$friendlist = $DB->getFriendList($tempuser->getUserID());
$requestedlist = $DB->getRequestedList($tempuser->getUserID());
function sortbyusername($a, $b)
{
    return strcmp($a->getUserName(), $b->getUserName());
}

if (!empty($requestedlist)) {
    usort($requestedlist, "sortbyusername");
}

if (!empty($friendlist)) {
    usort($friendlist, "sortbyusername");
}

if(isset($_GET["ChangeActive"])) {
    $DB->changeUserActive(@$_GET["ChangeActive"],@$_GET["User"]);
    header("Location: index.php?page=UserAdministration");
}

?>
    <div class="container">
        <div class="main-login main-center" <?php if($_SESSION['SessionUserName'] == 'admin'){echo"hidden";}?>>
            <h1 class="card-title mt-3 text-center">Find a new Friend</h1>

            <div class="text-center">
                <a href='#addFriend' class='btn btn-secondary btn-block' data-toggle='modal'><i
                            class="fas fa-user-plus"></i>
                    Add Friend</a>
            </div>
        </div>
    </div>
    <div id="addFriend" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title">Add Friend</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <?php
                        //$sql = "SELECT id,anrede,vorname,nachname,adresse,plz,ort,username,passwort,emailadresse FROM user";
                        //$result = $mysqli->query($sql);
                        $result = $DB->getUserList();
                        foreach ($result as $u) {
                            if ($u->getUserName() != $tempuser->getUserName()) {

                                if ($DB->isFriend($tempuser->getUserID(), $u->getUserID()) == false) {
                                    echo "<tr>";
                                    if ($DB->receivedRequest($tempuser->getUserID(), $u->getUserID())) {
                                        echo "<td><b>" . $u->getUserName() . "</b> already wants to be your friend</td>";
                                        echo "<td >
                                        <a style='color: limegreen' href='index.php?page=friends&acceptfriend=" . $u->getUserID() . "'><span><i class='fas fa-check'></i></a>
                                        <a class='float-right' style='color: red' href='index.php?page=friends&declinefriend=" . $u->getUserID() . "'><i class='fas fa-times'></i>   </a></td>";
                                    } elseif ($DB->sentRequest($tempuser->getUserID(), $u->getUserID())) {
                                        echo "<td><b>" . $u->getUserName() . "</b></td>";
                                        echo "<td style='text-align: right'>Requested</td>";
                                    } else {
                                        echo "<td><b>" . $u->getUserName() . "</b></td>";
                                        echo "<td style='text-align: right'><a href='index.php?page=friends&addfriend=" . $u->getUserID() . "' class='btn btn-success '><i class='fas fa-user-plus' ></i>  Request</a></td>";
                                    }
                                    echo "</tr>";
                                }
                            }
                        } ?>
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="container">
                        <a class="btn btn-primary btn-block" data-dismiss="modal">
                            Cancel </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" <?php if($_SESSION['SessionUserName'] == 'admin'){echo"hidden";}?>>
        <div class="main-login main-center">
            <h2 class="card-title mt-3 text-center">Friend requests</h2>
            <table class="table table-striped">
                <?php
                //$sql = "SELECT id,anrede,vorname,nachname,adresse,plz,ort,username,passwort,emailadresse FROM user";
                //$result = $mysqli->query($sql);
                if (!empty($requestedlist)) {
                    foreach ($requestedlist as $u) {
                        echo "<tr>";
                        echo "<td><b>" . $u->getUserName() . "</b></td>";
                        echo "<td><a style='color: limegreen' href='index.php?page=friends&acceptfriend=" . $u->getUserID() . "'><span><i class='fas fa-check'></i></a></td>";
                        echo "<td><a style='color: red' href='index.php?page=friends&declinefriend=" . $u->getUserID() . "'><i class='fas fa-times'></i></a></td>";
                        echo "</tr>";
                    }
                } else { ?>
                    <p class="mt-5 text-center">There are no pending friend requests at the moment
                    </p>
                <?php } ?>
            </table>
        </div>
    </div>
    <div class="container">
        <div class="main-login main-center">
            <h2 class="card-title mt-3 text-center">
            <?php if($_SESSION['SessionUserName'] == 'admin') {
                    echo "Administration</h2>";
                } else {
                    echo "Friends</h2>";
                }?>
            <table class="table table-striped">
                <?php
                if($_SESSION['SessionUserName'] == 'admin') {
                    if (!empty($userlist)) {
                        ?>
                        <thead class="table table-striped">
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <?php
                    foreach ($userlist as $u) {
                        echo "<tr>";
                        echo "<td>" . $u->getUserName() . "</td>";
                        echo "<td><a href='index.php?page=edituser&EditUser=" . $u->getUserID() . "'><i class='fas fa-edit'></i></a></td>";
                        if($u->getUserActive() == 1) {
                            echo "<td><a style='color: green' href='index.php?page=UserAdministration&ChangeActive=0&User=" . $u->getUserID() . "'><i class='fas fa-circle'></i></a></td>";
                        } else if($u->getUserActive() == 0) {
                            echo "<td><a style='color: red' href='index.php?page=UserAdministration&ChangeActive=1&User=" . $u->getUserID() . "'><i class='fas fa-circle'></i></a></td>";
                        }
                        echo "</tr>";
                    }}
                } else {
                    if (!empty($friendlist)) {
                    foreach ($friendlist as $u) {
                        if ($DB->isFriend($tempuser->getUserID(), $u->getUserID()) == true) {
                            echo "<tr>";
                            echo "<td>" . $u->getUserName() . "</td>";
                            echo "<td><a style='color: red' href='index.php?page=friends&declinefriend=" . $u->getUserID() . "'><i class='fas fa-times'></i></a></td>";
                            echo "</tr>";
                        }
                    }
                } else { ?>
                    <p class="mt-5 text-center">Seems like you don't have friends at the moment
                    </p>
                    <a href='#addFriend' class='btn btn-secondary btn-block' data-toggle='modal'><i
                                class="fas fa-user-plus"></i>
                        Add Friend</a>
                <?php }} ?>
            </table>
        </div>
    </div>
<?php
if (isset($_GET["addfriend"])) {
    $DB->requestFriend($tempuser->getUserID(), $_GET["addfriend"], "pending");
    echo "<script>window.location.href='index.php?page=friends';</script>";
}

if (isset($_GET["acceptfriend"])) {
    $DB->acceptFriend($_GET["acceptfriend"], $tempuser->getUserID());
    echo "<script>window.location.href='index.php?page=friends';</script>";
}

if (isset($_GET["declinefriend"])) {
    $DB->declineFriend($tempuser->getUserID(), $_GET["declinefriend"]);
    echo "<script>window.location.href='index.php?page=friends';</script>";
}
