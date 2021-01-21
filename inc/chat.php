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
}
if(isset($_POST["chatwith"])){
    $_SESSION["chatwith"] = $_POST["chatwith"];
}

?>

    <div class="row" style="width: 100%">
        <div class="chatbar" style="background: #2b2b2b; color: white">
            <h2 class="card-title mt-3 text-center">Direct Messages</h2>
            <hr style='border-top: solid 2px; color: #6b6b6b'/>
            <?php
            if (!empty($friendlist)) {
                foreach ($friendlist as $u) {
                    if ($DB->isFriend($tempuser->getUserID(), $u->getUserID()) == true) {
?>
                        <div class='row' style='margin-left: 20px'>
                        <div class='col-8 username-chat '><?=$u->getUserName()?></div>
                        <div class='col-4'><form method="post">
                                <button type="submit" class="btn btn-primary"
                                        style="background-color: transparent; border: none !important; color: #e5e5e5; outline: none !important; box-shadow: none !important;"
                                        name="chatwith" value="<?= $u->getUserID() ?>"><i class=' far fa-paper-plane fa-lg'></i></button>
                            </form></div>
                        </div>
                        <hr style='border-top: solid 2px; color: #6b6b6b'/>
            <?php
                    }
                }
            }
            ?>

        </div>
        <iframe src="./inc/userchat.php" class="col" style="border: none">
        </iframe>
    </div>



