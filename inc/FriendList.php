<?php
$DB = new DB();
$tempuser = $DB->getUser($_SESSION["SessionUserName"]);
$friendlist = $DB->getFriendList($tempuser->getUserID());
for ($i = 0; !empty($friendlist[$i]); $i++) {

    echo $friendlist[$i]->getUserName();
    echo "<hr/>";
}