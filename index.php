<?php
include "classes/DB.php";
include "classes/User.php";
session_start();
if(@$_GET["SessionPage"] == "logout") {

    session_destroy();
    header("Location: index.php");
}

else if(!isset($_SESSION['SessionUserName']) && @$_GET["page"] == "home"){

    $include = 'inc/login.php';
}

else if(@$_GET["page"] == "home") {

    $include = 'inc/home.php';
}

else if(@$_GET["page"] == "help") {

    $include = 'inc/help.php';
}

else if(@$_GET["page"] == "imprint") {

    $include = 'inc/imprint.php';
}

else if(@$_GET["page"] == "UserForm") {

    $include = 'inc/UserForm.php';
}

else if(@$_GET["page"] == "pwforgot") {

    $include = 'inc/pwforgot.php';
}

else if(@$_GET["page"] == "pwchange") {

    $include = 'inc/pwchange.php';
}

else if(!isset($_SESSION['username'])){

    $include = 'inc/login.php';
}

else if(@$_GET["page"] == "namelist") {

    $include = 'inc/namelist.php';
}

else if(@$_GET["page"] == "userAdministration") {

    $include = 'inc/userAdministration.php';
}

else if(@$_GET["page"] == "upload") {

    $include = 'inc/upload.php';
}

else {

    $include = 'inc/login.php';
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>KaraNatsch</title>
    <link rel="icon" href="res/img/KaraNatsch-Icon.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    <link rel="stylesheet" type="text/css" href="res/css/ProjectCss.css">

    <title>Index.php</title>
</head>
<body>
<?php
include "inc/header.php";
include "$include";
?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
</body>
</html>
