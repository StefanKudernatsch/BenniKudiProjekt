<?php
$DB = new DB();
$tempuser = $DB->getUser($_SESSION["SessionUserName"]);
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


?>
    <div class="container">
        <div class="main-login main-center">
            <h1 class="card-title mt-3 text-center">Find a new Friend</h1>

            <div class="text-center">
                <a href='#addFriend' class='btn btn-secondary btn-block' data-toggle='modal'><i class="fas fa-user-plus"></i>
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
                                echo "<tr>";
                                if ($DB->isFriend($tempuser->getUserID(), $u->getUserID(), "pending")) {
                                    echo "<td><b>" . $u->getUserName() . "</b> already wants to be your friend</td>";
                                    echo "<td class='float-right'><a style='color: red' href='index.php?page=friends&declinefriend=" . $u->getUserID() . "'><i class='fas fa-times'></i></a></td>";
                                    echo "<td class='float-right'><a style='color: limegreen' href='index.php?page=friends&acceptfriend=" . $u->getUserID() . "'><span><i class='fas fa-check'></i></a></td>";
                                } else {
                                    echo "<td><b>" . $u->getUserName() . "</b></td>";
                                    echo "<td class='float-right'><a href='index.php?page=friends&addfriend=" . $u->getUserID() . "' class='btn btn-success '><i class='fas fa-user-plus' ></i>  Request</a></td>";
                                }
                                echo "</tr>";
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
    <div class="container">
        <div class="main-login main-center">
            <h2 class="card-title mt-3 text-center">Friend requests</h2>
            <table class="table table-striped">
                <?php
                //$sql = "SELECT id,anrede,vorname,nachname,adresse,plz,ort,username,passwort,emailadresse FROM user";
                //$result = $mysqli->query($sql);
                if (!empty($requestedlist)) {
                    foreach ($requestedlist as $u) {
                        if ($DB->isFriend($tempuser->getUserID(), $u->getUserID(), "pending") == true) {
                            echo "<tr>";
                            echo "<td><b>" . $u->getUserName() . "</b></td>";
                            echo "<td><a style='color: limegreen' href='index.php?page=friends&acceptfriend=" . $u->getUserID() . "'><span><i class='fas fa-check'></i></a></td>";
                            echo "<td><a style='color: red' href='index.php?page=friends&declinefriend=" . $u->getUserID() . "'><i class='fas fa-times'></i></a></td>";
                            echo "</tr>";
                        }
                    }
                }
                else { ?>
                    <p class="mt-5 text-center">There are no pending friend requests at the moment
                    </p>
                <?php } ?>
            </table>
        </div>
    </div>
    <div class="container">
        <div class="main-login main-center">
            <h2 class="card-title mt-3 text-center">Friends</h2>
            <table class="table table-striped">
                <?php
                if (!empty($friendlist)) {
                    foreach ($friendlist as $u) {
                        if ($DB->isFriend($tempuser->getUserID(), $u->getUserID(), "accepted") == true) {
                            echo "<tr>";
                            echo "<td>" . $u->getUserName() . "</td>";
                            echo "<td><a style='color: red' href='index.php?page=friends&declinefriend=" . $u->getUserID() . "'><i class='fas fa-times'></i></a></td>";
                            echo "</tr>";
                        }

                    }
                } else { ?>
                    <p class="mt-5 text-center">Seems like you don't have friends at the moment
                    </p>
                    <a href='#addFriend' class='btn btn-secondary btn-block' data-toggle='modal'><i class="fas fa-user-plus"></i>
                        Add Friend</a>
                <?php } ?>
            </table>
        </div>
    </div>
<?php
if (isset($_GET["addfriend"])) {
    $DB->requestFriend($tempuser->getUserID(), $_GET["addfriend"], "pending");
    echo "<script>window.location.href='index.php?menu=Friends';</script>";
}

if (isset($_GET["acceptfriend"])) {
    $DB->acceptFriend($_GET["acceptfriend"], $tempuser->getUserID());
    echo "<script>window.location.href='index.php?page=friends';</script>";
}

if (isset($_GET["declinefriend"])) {
    $DB->declineFriend($tempuser->getUserID(), $_GET["declinefriend"]);
    echo "<script>window.location.href='index.php?page=friends';</script>";
}
