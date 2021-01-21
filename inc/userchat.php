<?php
session_start();
include_once "../classes/DB.php";
include_once "../classes/User.php";
$DB = new DB();
$tempuser = $DB->getUser($_SESSION["SessionUserName"]);

if(isset($_SESSION["chatwith"])){
    $chatuser = $DB->getUserWithID($_SESSION["chatwith"]);
    ?>
    <h2>Chat with <?=$chatuser->getUserName()?></h2>
    <meta http-equiv="refresh" content="10">
    <?php
}
?>