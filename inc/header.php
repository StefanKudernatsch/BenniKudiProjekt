<?php
$admin = "admin";
$mysqli = new mysqli('127.0.0.1', 'root', '', 'bennikudidb');
//$db = new DB($mysqli);
?>

<div class="title">
    <div class="row">
        <?php
        if (isset($_SESSION["user"])) {
            /*$user_temp = $db->getUser($_SESSION["user"]);

            $timestamp = $user_temp->get_timestamp();
            $newDate = date("d.m.Y G:i", strtotime($timestamp));*/


            ?>
            <div class="col-md-3">
                <a href="?menu=home" style="margin-left: -20px">
                    <img src="res/img/KaraNatsch-Logo.png" alt="Logo">
                </a>
            </div>
            <div class="col-md-3" style="text-align: center ">
                <h5 class="float-right" style="padding-top: 10px;">Eingeloggt als <b><?php //echo $_SESSION["user"] ?><br></b><?php //if($timestamp==NULL) {
                        //echo "<b>Erster Login</b>";
                    //} else {?> Last Login <b> <?php //echo $newDate ; ?></b><?php// } ?>
                </h5>
            </div>

            <?php
        } else {
            ?>
            <div class="col-md-6">
                <a href="?menu=home" style="margin-left: -20px">
                    <img src="res/img/KaraNatsch-Logo.png" alt="Logo">
                </a>
            </div>
            <?php
       }
        ?>
        <div class="col-md-6 ">

            <nav class="navbar navbar-expand-md navbar-dark">
                <ul class="navbar-nav ml-auto">
                    <li><a href="index.php?menu=Impressum" class="nav-item nav-link">Impressum</a></li>
                    <li><a href="index.php?menu=Hilfe" class="nav-item nav-link">Hilfe</a></li>
                    <?php
                    //if (($_SESSION["user"] == $admin)) {
                        ?>
                        <li><a href="index.php?menu=UserAdministration" class="nav-item nav-link">Administration</a></li>
                        <?php
                   // }//login
                    ?>
                    <?php
                   // if (!isset($_SESSION["user"])) {  //login
                        ?>
                        <li><a href="#" class="btn btn-primary nav-item" data-toggle="dropdown"><i class="fas fa-sign-in-alt"></i>
                                <span>Log In</span></a>
                            <ul id="login-dp" class="dropdown-menu dropdown-menu-right login-dp">
                                <li>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2 class="text-center">Log in</h2>
                                            <div class="dropdown-divider"></div>
                                            <div class="col-sm-12 login-form">
                                                <form action="index.php" method="post">

                                                    <div class="form-group">
                                                        <input type="text" id="username" name="username" class="form-control"
                                                               placeholder="Username" required="required">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="password" id="password" name="password" class="form-control"
                                                               placeholder="Password" required="required">
                                                    </div>
                                                    <div class="clearfix">
                                                        <input type="checkbox" name="rememberme" id="checkbox">
                                                        <label for="checkbox" class="form-check-label">Remember me</label>

                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" name="login"
                                                                class="btn btn-primary btn-block float-left">Log in
                                                        </button>
                                                    </div>
                                                    <div class="form-group">
                                                        <a href="?menu=reset-password" class="btn btn-danger btn-block float-left">Passwort
                                                            vergessen</a>
                                                    </div>
                                                    <div class="form-group">
                                                        <a href="?menu=register" class="btn btn-info btn-block float-left">Registrieren</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul></li>
                    <?php// } else {  //logout

                      //  if (($_SESSION["user"] != $admin)) {?>
                            <li><a href="index.php?menu=Like1" class="nav-item nav-link">Like1</a></li>
                            <li><a href="index.php?menu=Friends" class="nav-item nav-link">Freundesliste</a></li>
                            <li><a href="index.php?menu=Namelist" class="nav-item nav-link">Namensliste</a></li><?php// }?>
                        <li><a href="index.php?menu=EditUser" class="nav-item nav-link"><i class="fas fa-users"></i> <span>User verwalten</span></a></li>
                        <span><li><a href="?menu=logout" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i>Log Out</a></li></span>

                    <?php //} ?>
                </ul>
            </nav>
        </div>

    </div>
</div>
