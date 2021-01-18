<?php
$admin = "admin";
$db = new DB();
?>
<div class="title">
    <div class="row">
        <div class="col-2">
            <a href="?page=home" style="margin-left: -20px">
                <img src="res/img/KaraNatsch-Logo.png" alt="Logo">
            </a>
        </div>
        <div class="col-8 ">
            <nav class="navbar navbar-expand-md navbar-dark">
                <ul class="navbar-nav">
                    <li><a href="?page=imprint" class="nav-item nav-link">Impressum</a></li>
                    <li><a href="?page=help" class="nav-item nav-link">Help</a></li>
                    <?php
                    if (($_SESSION["SessionUserName"] == $admin)) {
                        ?>
                        <li><a href="?page=UserAdministration" class="nav-item nav-link">Administration</a>
                        </li>
                        <?php
                    }//login
                    ?>
                    <?php
                    if (!isset($_SESSION["SessionUserName"])) {  //login
                        ?>

                    <?php } else {  //logout

                        if (($_SESSION["SessionUserName"] != $admin)) { ?>
                            <li><a href="?page=like" class="nav-item nav-link">Like</a></li>
                            <li><a href="?page=friends" class="nav-item nav-link">Friends</a></li>
                            <li><a href="?page=namelist" class="nav-item nav-link">Userlist</a>
                            </li><?php } ?>
                        <li><a href="?page=edituser" class="nav-item nav-link"><i class="fas fa-users"></i>
                                <span>Profile</span></a></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
        <div class="col-2">
            <?php
            if (isset($_SESSION["SessionUserName"])) {

                ?>
                <span><a style='margin-top: 5px' href="?page=logout" class="float-right btn btn-danger"><i class="fas fa-sign-out-alt"></i> Log Out</a></span>
                <?php
            } else {
                ?>

                <a style='margin-top: 5px' href="#" class="btn btn-primary float-right" data-toggle="dropdown"><i class="fas fa-sign-in-alt"></i>
                    <span>Log In</span></a>
                <ul id="login-dp" class="dropdown-menu dropdown-menu-right">
                    <li>
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center">Log in</h2>
                                <hr/>
                                <div class="login-form">
                                    <form action="index.php" method="post">

                                        <div class="form-group">
                                            <input type="text" id="username" name="UserName"
                                                   class="form-control"
                                                   placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="password" name="Password"
                                                   class="form-control"
                                                   placeholder="Password" required>
                                        </div>
                                        <div class="clearfix">
                                            <input type="checkbox" name="RememberMe" id="checkbox">
                                            <label for="checkbox" class="form-check-label">Remember
                                                me</label>

                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="Login"
                                                    class="btn btn-primary btn-block float-left">Log in
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <a href="?menu=reset-password"
                                               class="btn btn-danger btn-block float-left">Forgot Password?</a>
                                        </div>
                                        <div class="form-group">
                                            <a href="?page=UserForm"
                                               class="btn btn-info btn-block float-left">Register</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <?php
            }
            ?>
        </div>
    </div>
</div>





