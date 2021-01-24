<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
$cookie_name = "CookieUserName";
$cookie_lifetime = 3600;
include "classes/DB.php";
include "classes/User.php";
include "classes/Comment.php";
$DB = new DB();


?>
<?php

if(isset($_POST["ResetPWSubmit"])){

    if (preg_match("/['^£$%&*()}{#~?><>,_|=+¬;-]/", $_POST["username"])) {
        $invalidchar = 1;
        //echo "username";
    } else {
        $invalidchar = 0;
    }

    if ($DB->getUserMail($_POST["email"])->getUserEMail() == NULL) {
        $exist_mail = 1;
        echo "<script language='JavaScript'>alert('Email " . $_POST["email"] . " does not exist' )</script>";
        echo "<script>window.location.href='index.php';</script>";
    }
    else{
        $exist_mail = 0;
    }
    if ($invalidchar == 1) {
        echo "<script language='JavaScript'>alert('Keine Sonderzeichen' )</script>";
        echo "<script>window.location.href='index.php';</script>";
    }
    if ($exist_mail == 0 && $invalidchar == 0) {
        $user_object = $DB->getUserMail($_POST["email"]);

        if($DB->resetPassword($_POST["email"])) {
            echo "<script language='JavaScript'>alert('Password reset successfully')</script>";
        } else {
            echo "<script language='JavaScript'>alert('Password reset failed')</script>";
        }
    }
}

if (isset($_POST["Login"])) {
    $loginUsername = $_POST["UserName"];
    $loginPassword = $_POST["Password"];
    if($DB->getUserActiveWithUsername($loginUsername)) {
        $ergebnis = $DB->loginUser($loginUsername, $loginPassword);
        if ($ergebnis == true) {
            if (isset($_POST["RememberMe"])) {
                setcookie($cookie_name, $loginUsername, time() + $cookie_lifetime);
            }
        } else {
            echo "<script language='JavaScript'>alert('Login incorrect')</script>";
        }
    } else {
        echo "<script language='JavaScript'>alert('Account deactivated')</script>";
    }

} else if (!isset($_SESSION["SessionUserName"]) && isset($_COOKIE[$cookie_name])) {
    $_SESSION["SessionUserName"] = $_COOKIE[$cookie_name];
}

if(@$_GET["page"] == "logout") {
    setcookie($cookie_name, "", time() - $cookie_lifetime);
    unset($_SESSION["SessionUserName"]);
    session_destroy();
    header("Location: index.php");
}

else if(@$_GET["page"] == "edituser") {
    $include = 'inc/UserForm.php';
}

else if(@$_GET["page"] == "home") {
    $include = 'inc/home.php';
}

else if(@$_GET["page"] == "help") {
    $include = 'inc/help.php';
}

else if(@$_GET["page"] == "imprint") {
    $include = 'inc/impressum.php';
}

else if(@$_GET["page"] == "UserForm") {
    $include = 'inc/UserForm.php';
}

else if(@$_GET["page"] == "pwforgot") {
    $include = 'inc/pwforgot.php';
}

else if(@$_GET["page"] == "friends") {
    $include = 'inc/FriendList.php';
}

else if(@$_GET["page"] == "UserAdministration") {
    $include = 'inc/FriendList.php';
}


else if(@$_GET["page"] == "chat") {
    $include = 'inc/chat.php';
}

else if(!isset($_SESSION['username'])){
    $include = 'inc/home.php';
}

else if(@$_GET["page"] == "upload") {
    $include = 'inc/upload.php';
}



else {
    $include = 'inc/home.php';
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>KaraNatsch</title>
    <link rel="icon" href="res/img/KaraNatsch-Icon.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Fonts Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="res/css/ProjectCss.css">

    <!-- Fancybox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
</head>
<body>
<header>
    <?php include "inc/header.php"?>
</header>
<main>
    <div id='resetUserPW' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form method='post'>
                    <div class='modal-header'>
                        <h4 class='modal-title'>Reset Password</h4>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    </div>
                    <div class='modal-body'>
                        <p>Please enter the accounts email</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fas fa-envelope"></i> </span>
                            </div>
                            <input type="email" id="email" name="email" class="form-control" placeholder="example@email.com"
                                   required="required">
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <input type='submit' class='btn btn-danger btn-block' name='ResetPWSubmit' value='Reset Password'>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include "$include"; ?>
</main>
<footer>

</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>


</body>
</html>
