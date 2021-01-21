<?php
session_start();
include_once "../classes/DB.php";
include_once "../classes/User.php";
include_once "../classes/Message.php";
$DB = new DB();
$tempuser = $DB->getUser($_SESSION["SessionUserName"]);

$messagelist = $DB->getMessages($tempuser->getUserID(), $_SESSION["chatwith"]);
function sortbyid($a, $b)
{
    return strcmp($a->getMessageID(), $b->getMessageID());
}

if (!empty($messagelist)) {
    usort($messagelist, "sortbyid");
}

if(isset($_POST["sendmessage"])){
    $DB->addMessage($tempuser->getUserID(),$_POST["sendmessage"],$_POST["message"]);
}
var_dump($messagelist);
?>
    <!doctype html>
    <html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
              integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
              crossorigin="anonymous">

        <!-- Fonts Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="../res/css/ProjectCss.css">


    </head>
    <body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
            crossorigin="anonymous"></script>
    <?php
    if (isset($_SESSION["chatwith"])) {
        $chatuser = $DB->getUserWithID($_SESSION["chatwith"]);
        ?>

            <div class="chatwindow">
                <h2>Chat with <?= $chatuser->getUserName() ?></h2>
                <?php
                foreach ($messagelist as $message){
                    ?>
                    <div class="<?php if($message->getReceiverID() == $_SESSION["chatwith"]){?>float-left receivermsg<?php } else { ?> float-right sendermsg <?php } ?>">
                    <?=$message->getMessageText();?>
                    </div>
                    <br>
                <?php
                }
                ?>



                <div class="messagefooter">
                    <form method="post">
                        <div class="input-group">
                            <input class="form-control" type="text" name="message" id="message"
                                   placeholder="Add a comment...">

                            <button type="submit" class="btn btn-success" name="sendmessage"
                                    value="<?= $chatuser->getUserID() ?>">
                                <i class="fas fa-share"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        <?php
    }
    ?>
    </body>
    </html>
<meta http-equiv="refresh" content="60">
