<?php
$DB = new DB();

$tempuser = $DB->getUser($_SESSION["SessionUserName"]);

$friendlist = $DB->getFriendList($tempuser->getUserID());

function sortbyusername($a, $b)
{
    return strcmp($a->getUserName(), $b->getUserName());
}


if (!empty($friendlist)) {
    usort($friendlist, "sortbyusername");
}?>
<div class="container">
    <div class="main-login main-center">
        <h2 class="card-title mt-3 text-center">

                Friends</h2>

            <table class="table table-striped">
                <?php
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
                    <?php }
                 ?>
            </table>
    </div>
</div>
