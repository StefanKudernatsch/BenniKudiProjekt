<?php
$admin = "admin";
$db = new DB();
$tempuser = $db->getUser($_SESSION["SessionUserName"]);
if(isset($_POST['ResetPWSubmit'])) {


}
?>
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
                        <input type="email" id="email" name="email" class="form-control" placeholder="example@email.com" required="required">
                    </div>
                </div>
                <div class='modal-footer'>
                    <input type='submit' class='btn btn-danger btn-block' name='ResetPWSubmit' value='Reset Password'>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="title">
    <div class="row">
        <div class="col-2">
            <a href="?page=home" style="margin-left: -20px">
                <img src="res/img/KaraNatsch-Logo.png" alt="Logo">
            </a>
        </div>
        <div class="col-8 ">
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
                            <li><a href="?page=like" class="nav-item nav-link">Like</a></li>
                            <div class="button">
                                <li><a href="?page=friends" class="nav-item nav-link">Friends</a></li>
                                <?php
                                if($db->getPendingNumber($tempuser->getUserID()) != 0){
                                ?>
                                <span class="button__badge"><?= $db->getPendingNumber($tempuser->getUserID()) ?></span>
                                    <?php }?>
                            </div>
                        <li><a href="?page=edituser" class="nav-item nav-link"><i class="fas fa-users"></i>
                                <span>Profile</span></a></li>
                        <?php } ?>
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
                </ul>

                <?php
            }
            ?>
        </div>
    </div>
</div>





