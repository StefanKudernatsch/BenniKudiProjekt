<!--
Navbar for header with logo. Login button when no Session ist set with Impressum and Help. Logout button when Session isset + Friends + Chats + Profile
-->

<?php
$admin = "admin";
$db = new DB();
$tempuser = $db->getUser($_SESSION["SessionUserName"]);
if (isset($_POST['ResetPWSubmit'])) {


}
?>

<div class="title">
    <div class="row">
        <div class="col-12 col-md-2" style="justify-content: center; display: flex; flex-direction: row;">
            <a href="?page=home" style="margin-left: 10px">
                <img  class="logo" src="res/img/KaraNatsch-Logo.png" alt="Logo">
            </a>
        </div>
        <div class="col-6 col-md-8" style="justify-content: center; display: flex; flex-direction: row;">
            <nav class="navbar navbar-expand-md navbar-dark">
                <ul class="navbar-nav dots">
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
                            <div class="button">
                                <li><a href="?page=friends" class="nav-item nav-link">Friends</a></li>
                                <?php
                                if ($db->getPendingNumber($tempuser->getUserID()) != 0) {
                                    ?>
                                    <span class="button__badge"><?= $db->getPendingNumber($tempuser->getUserID()) ?></span>
                                <?php } ?>
                            </div>
                            <div class="button">
                                <li><a href="?page=chat" class="nav-item nav-link">Chats</a></li>
                                <?php
                                if ($db->getAllUnreadMessages($tempuser->getUserID()) != 0) {
                                    ?>
                                    <span class="button__badge"><?= $db->getAllUnreadMessages($tempuser->getUserID()) ?></span>
                                <?php } ?>
                            </div>
                            <li><a href="?page=edituser" class="nav-item nav-link"><i class="fas fa-users"></i>
                                    <span>Profile</span></a></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </nav>
        </div>
        <div class="col-6 col-md-2" style="justify-content: center; display: flex; flex-direction: row;">
            <?php
            if (isset($_SESSION["SessionUserName"])) {

                ?><div style="display: flex; flex-direction: column;">
                <span><a style='margin-top: 2px; margin-left: -10px' href="?page=logout" class="float-right btn btn-danger"><i
                                class="fas fa-sign-out-alt"></i> Log Out</a></span>
                <p style="font-size: 9px;text-align: right; margin-right: 5%">Logged In as: <?= $_SESSION["SessionUserName"]?></p>
                </div>
                <?php
            } else {
                ?>

                <span><a style='margin-top: 5px; margin-left: -10px' href="#" class="btn btn-primary float-right" data-toggle="dropdown"><i
                            class="fas fa-sign-in-alt"></i>
                    Log In</a><ul id="login-dp" class="dropdown-menu dropdown-menu-right">
                    <li>
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center">Log in</h2>
                                <hr/>
                                <div class="login-form">
                                    <form method="post">

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
                                            <a href='#resetUserPW' data-toggle='modal'
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
                </ul></span>


                <?php
            }
            ?>
        </div>
    </div>
</div>






