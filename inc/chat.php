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
if (isset($_POST["chatwith"])) {
    $_SESSION["chatwith"] = $_POST["chatwith"];
    $DB->ReadMessage($_SESSION["chatwith"], $tempuser->getUserID());
    echo "<meta http-equiv='refresh' content='0'>";
}

?>

<div class="row">
    <div class="chatbar" style="background: #2b2b2b; color: white; border-top: #6b6b6b solid 2px; ">
        <h2 class="card-title mt-3 text-center">Direct Messages</h2>
        <hr style='border-top: solid 2px; color: #6b6b6b'/>
        <?php
        if (!empty($friendlist)) {
            foreach ($friendlist as $u) {
                if ($DB->isFriend($tempuser->getUserID(), $u->getUserID()) == true) {
                    ?>
                    <div class='row'
                         style='margin-left: 10px; margin-right: 0px; padding-top: 10px; padding-bottom: 10px;  <?php if ($_SESSION["chatwith"] == $u->getUserID()) { ?>background-color: grey;<?php } ?>'>

                        <div class='col-8 username-chat'><?= $u->getUserName() ?></div>
                        <div class='col-4'>
                            <?php if($DB->getUnreadUserMessage($u->getUserID(), $tempuser->getUserID())!=0){?>
                            <span class="button__chatbadge"><?= $DB->getUnreadUserMessage($u->getUserID(), $tempuser->getUserID()) ?></span>
                        <?php } ?>
                            <form method="post">
                                <button type="submit" class="btn btn-primary"
                                        style="background-color: transparent; border: none !important; color: #e5e5e5; outline: none !important; box-shadow: none !important;"
                                        name="chatwith" value="<?= $u->getUserID() ?>"><i
                                            class=' far fa-paper-plane fa-lg'></i></button>
                            </form>
                        </div>
                    </div>
                    <hr style='border-top: solid 2px; color: #6b6b6b'/>
                    <?php
                }
            }
        }
        ?>

    </div>
    <iframe src="./inc/userchat.php" class="col" style="border: none; height: 100vh">
    </iframe>
</div>



