<?php
$DB = new DB();

if (!empty($_SESSION["SessionUserName"])) {
    if($_SESSION["SessionUserName"] == "admin") {
        if(!empty(@$_GET['EditUser'])) {
            $EditUser = $DB->getUserWithID(@$_GET['EditUser']);
        } else {
            header("Location: index.php?page=UserAdministration");
        }
    } else {
        $EditUser = $DB->getUser($_SESSION["SessionUserName"]);
    }
}

if(isset($_POST['DeleteSubmit'])) {
    $CheckValue = $DB->deleteUser($EditUser->getUserID());
    if($CheckValue == 0) {
        session_destroy();
        echo "<script language='JavaScript'>alert('Account deleted successfully')</script>";
        header("Location: index.php");
    } else {
        echo "<script language='JavaScript'>alert('Error #".$CheckValue." | Account deletion failed')</script>";
    }
}
else if(isset($_POST['PWSubmit'])) {
    if($_POST['password'] != $_POST['confirm_password']) {
        echo "<script language='JavaScript'>alert('Error | Passwords must be same')</script>";
    } else {
        $CheckValue = $DB->updateUserPW($EditUser->getUserID(), $_POST['old_password'], $_POST['password']);
        if($CheckValue == 0) {
            echo "<script language='JavaScript'>alert('Password changed successfully')</script>";
        } else if($CheckValue == 1) {
            echo "<script language='JavaScript'>alert('Password change failed')</script>";
        } else if($CheckValue == 2) {
            echo "<script language='JavaScript'>alert('Old password incorrect')</script>";
        }
    }
}
else if(isset($_POST['ResetPWSubmit'])) {

    if($DB->resetPassword($EditUser->getUserID(), $EditUser->getUserEMail())) {
        echo "<script language='JavaScript'>alert('Password reset successfully')</script>";
    } else {
        echo "<script language='JavaScript'>alert('Password reset failed')</script>";
    }
}
else if (isset($_POST['SaveSubmit'])) {

    $UserData = $_POST['UserData'];
    $CheckInput = true;

    if ($UserData[6] != $_POST['PasswordCheck']) {

        $CheckInput = false;
        echo "<script language='JavaScript'>alert('Error | Passwords must be same')</script>";
    }

    if (!isset($_FILES['blob'])) {

        if ($_FILES['blob']['error'] != 0) {

            $CheckInput = false;
            echo "<script language='JavaScript'>alert('Error | Image Upload failed')</script>";
        } else {

            if ($_FILES['blob']['type'] == "image/jpeg" || $_FILES['blob']['type'] == "image/jpg" || $_FILES['blob']['type'] == "image/png") {

                $blob = file_get_contents(addslashes($_FILES['blob']['tmp_name']));
            }
        }
    }

    for ($i = 0; $i < 11; $i++) {

        /*
         * 7 ... EMail
         * 4 ... UserImage
         * 6 ... Password
         * 3 ... Birthday
         */
        if ($i != 7 && $i != 4 && $i != 6 && $i != 3) {

            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬;-]/', $UserData[$i])) {

                $CheckInput = false;
                echo "<script language='JavaScript'>alert('Error1 | Special characters are not allowed')</script>";
                break;
            }
        }

        if (empty($UserData[$i]) == true && ($i == 8 || $i == 9 || $i == 10)) {

            $UserData[$i] = "NULL";
        }
    }

    if (preg_match('/[\'^£$%&*()}{#~?><>,|=_+¬;-]/', $UserData[7])) {

        $CheckInput = false;
        echo "<script language='JavaScript'>alert('Error2 | Special characters are not allowed')</script>";
    }

    if ($CheckInput == true) {

        if ($_POST['SaveSubmit'] == 'Save Details') {

            $EditUser->setUserGender($UserData[0]);
            $EditUser->setUserFirstName($UserData[1]);
            $EditUser->setUserLastName($UserData[2]);
            $EditUser->setUserBirthday($UserData[3]);
            $EditUser->setUserName($UserData[5]);
            $EditUser->setUserEMail($UserData[7]);
            $EditUser->setUserCity($UserData[8]);
            $EditUser->setUserPLZ($UserData[9]);
            $EditUser->setUserAddress($UserData[10]);

            if ($DB->updateUser($EditUser)) {

                if($_FILES['blob']['error'] == 0) {

                    $DB->uploadImage($_FILES['blob'], $EditUser->getUserID());
                }
                echo "<script language='JavaScript'>alert('Account details changed successfully')</script>";
            } else {

                echo "<script language='JavaScript'>alert('Error | Change account details failed')</script>";
            }
            header("Location: index.php?page=UserForm");
        } else {

            if($_FILES["blob"]["error"] != 0) {
                $UserData[4] = "./res/img/standard-image.png";
            } else {
                $UserData[4] = $_FILES["blob"]["tmp_name"];
            }

            $User = new User($UserData[0], $UserData[1], $UserData[2], $UserData[3], $UserData[4], $UserData[5], $UserData[6], $UserData[7], $UserData[8], $UserData[9], $UserData[10], 1);
            if ($DB->registerUser($User)) {
                $tempuser = $DB->getUser($UserData[5]);
                $tempuserid = $tempuser->getUserID();
                $DB->uploadImage($UserData[4], $tempuserid);
                $DB->getUserImage($tempuserid);

                echo "<script language='JavaScript'>alert('Account created successfully')</script>";
            } else {

                echo "<script language='JavaScript'>alert('Error | Create account failed')</script>";
            }
            header("Location: index.php");
        }
    }
}
?>
<div id='deleteUserModal' class='modal fade'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <form method='post'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Delete Account</h4>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                </div>
                <div class='modal-body'>
                    <p>Are you sure you want to delete your account?</p>
                </div>
                <div class='modal-footer'>
                    <input type='submit' class='btn btn-danger btn-block' name='DeleteSubmit' value='Delete Account'>
                </div>
            </form>
        </div>
    </div>
</div>
<div id='adminResetPW' class='modal fade'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <form method='post'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Reset Password</h4>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                </div>
                <div class='modal-body'>
                    <p>Are you sure you want to reset the password?</p>
                </div>
                <div class='modal-footer'>
                    <input type='submit' class='btn btn-danger btn-block' name='ResetPWSubmit' value='Reset Password'>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="newUserPW" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="container">
                    <div class=" main-center">
                        <div class="container formtop col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="old_password" class="cols-sm-2 control-label">Old Password: </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                                        </div>
                                        <input type="password" id="old_password" name="old_password"
                                               class="form-control" placeholder="Password" required="required">
                                    </div>
                                    <hr/>
                                    <label for="password" class="cols-sm-2 control-label">New Password: </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                                        </div>
                                        <input type="password" id="password" name="password"
                                               class="form-control" placeholder="Password" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                                        </div>
                                        <input type="password" id="confirm_password" name="confirm_password"
                                               class="form-control" placeholder="Password (repeat)"
                                               required="required">
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container">
                    <input type="submit" class="btn btn-danger btn-block" name="PWSubmit" value="Passwort ändern">
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <div class="main-login main-center">
        <div class="container formtop col-md-12 col-sm-12">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                        <div class="input-group justify-content-center">
                            <div class="d-flex justify-content-center h-100">
                                <div class="image_outer_container">
                                    <label for="upload">
                                        <?php
                                            if(@$_GET['ChangeValue'] == 1 || !isset($EditUser)) {
                                                echo "<span class='addfile' aria-hidden='true'></span>";
                                                echo "<input type='file' id='upload' name='blob' style='display:none'>";
                                            }
                                        ?>
                                    </label
                                        <input class="addfile" type="file" name="blob" accept=".jpg,.png,.jpeg" style="padding-top: 15%">
                                    <div class="image_inner_container">
                                        <?php
                                        //$tempuser = $DB->getUser($_SESSION["SessionUserName"]);
                                        //$image=$DB->getUserImage($tempuser->getUserID());

                                        if(isset($EditUser)){
                                            $image=$DB->getUserImage($EditUser->getUserID());
                                            echo '<a target="_blank" href="data:image/png;base64,'.base64_encode($image).'">
                                        <img class="thumbnail" src="data:image/png;base64,'.base64_encode($image).'"/>
                                        </a>';
                                        }
                                        else {
                                            echo '<a target="_blank" href="./res/img/standard-image.png">
                                        <img class="thumbnail" src="./res/img/standard-image.png"/>
                                        </a>';
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <h1 class="card-title mt-3 text-center"><?php if (isset($EditUser)) {
                        echo $EditUser->getUserName();
                    } else {
                        echo "Create Account";
                    } ?></h1>
                </div>
                <div class="form-group">
                    <label for="Gender" class="cols-sm-2 control-label">Gender: </label>
                    <div class="form-row">
                        <div class="input-group col-md-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fas fa-venus-mars"></i> </span>
                            </div>
                            <?php
                            if (isset($EditUser) && $_GET['ChangeValue'] == 0) {
                                echo "<input class='form-control' type='text' name='UserData[0]'";
                                echo "value='" . $EditUser->getUserGender() . "'";
                                echo "readonly>";
                            } else {
                                if(isset($EditUser)) {
                                    if($EditUser->getUserGender() == 'Herr') {
                                        $tempgender1 = 'selected';
                                    } else {
                                        $tempgender2 = 'selected';
                                    }
                                } else {
                                    $tempgender0 = 'selected';
                                }
                                echo "<select name='UserData[0]' id='Gender' class='form-control' required>
                                    <option value='NULL' disabled $tempgender0>Select...</option>
                                    <option value='Herr' $tempgender1>Herr</option>
                                    <option value='Frau' $tempgender2>Frau</option>
                                    </select>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Birthday" class="cols-sm-2 control-label">Birthday: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="far fa-calendar-alt"></i> </span>
                        </div>
                        <input class="form-control" type="date" name="UserData[3]" id="Birthday" placeholder="1.1.2021"
                               required
                            <?php
                            if (isset($EditUser)) {
                                echo "value='".$EditUser->getUserBirthday()."'";
                                if(@$_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue'])) {
                                    echo "readonly";
                                }
                            }
                            ?>>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="FirstName" class="cols-sm-2 control-label">Name: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                        </div>
                        <input class="form-control" type="text" name="UserData[1]" id="FirstName"
                               placeholder="First Name" required
                            <?php
                            if (isset($EditUser)) {
                                echo "value='" . $EditUser->getUserFirstName() . "'";
                                if(@$_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue'])) {
                                    echo "readonly";
                                }
                            }
                            ?>>
                    </div>
                </div>

                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                    </div>
                    <input class="form-control" type="text" name="UserData[2]" id="LastName" placeholder="Last Name"
                           required
                        <?php
                        if (isset($EditUser)) {
                            echo "value='" . $EditUser->getUserLastName() . "'";
                            if(@$_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue'])) {
                                echo "readonly";
                            }
                        }
                        ?>>
                </div>
                <hr/>
                <div>
                    <label for="Address">Address: </label>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                        </div>
                        <input class="form-control" type="text" id="Address" name="UserData[10]"
                            <?php
                            if (isset($EditUser)) {
                                if ($EditUser->getUserAddress() != 'NULL') {
                                    echo "value='" . $EditUser->getUserAddress() . "'";
                                }
                                if(@$_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue'])) {
                                    echo "readonly";
                                }
                            } else {
                                echo "placeholder='Straße 123/4'";
                            }
                            ?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-6 input-group">
                            <label for="PLZ">PLZ: </label>
                        </div>
                        <div class="col-md-6 input-group">
                            <label for="City">City: </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <input class="form-control" id="PLZ" name="UserData[9]"
                                <?php
                                if (isset($EditUser)) {
                                    if ($EditUser->getUserPLZ() != 0) {
                                        echo "value='" . $EditUser->getUserPLZ() . "'";
                                        echo "type='text'";
                                    }
                                    if(@$_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue'])) {

                                        echo "readonly";
                                    }
                                } else {
                                    echo "placeholder='1200'";
                                    echo "type='number' min='1000' max='9999'";
                                }
                                ?>>
                        </div>

                        <div class="col-md-6 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            </div>
                            <input class="form-control" type="text" id="City" name="UserData[8]"
                                <?php
                                if (isset($EditUser)) {
                                    if ($EditUser->getUserCity() != 'NULL') {
                                        echo "value='" . $EditUser->getUserCity() . "'";
                                    }
                                    if(@$_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue'])) {
                                        echo "readonly";
                                    }
                                } else {
                                    echo "placeholder='Vienna'";
                                }
                                ?>>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="Username" class="cols-sm-2 control-label">Username: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-user-circle"></i> </span>
                        </div>
                        <input type="text" id="Username" name="UserData[5]" class="form-control" placeholder="Username"
                               required
                            <?php
                            if (isset($EditUser)) {
                                echo "value='" . $EditUser->getUserName() . "'";
                                if(@$_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue'])) {
                                    echo "readonly";
                                }
                            }
                            ?>>
                    </div>
                </div>
                <hr/>
                <div class="form-group" <?php if (isset($EditUser) && (@$_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue']))) {
                    echo "hidden";
                } ?>>
                    <label for="Password" class="cols-sm-2 control-label">Password: </label>
                    <div class="input-group">
                        <?php
                            if(isset($EditUser) && @$_GET['ChangeValue'] == 1) {
                                if($_SESSION['SessionUserName']=='admin') {
                                    echo "<a href='#adminResetPW' data-toggle='modal' class='btn btn-danger btn-block'>Reset Password</a>";
                                } else {
                                    echo "<a href='#newUserPW' data-toggle='modal' class='btn btn-danger btn-block'>Change Password</a>";
                                }
                            } else {
                                echo "<div class='input-group-prepend'>
                                        <span class='input-group-text'> <i class='fas fa-lock'></i> </span>
                                      </div>
                                    <input type='password' id='Password' name='UserData[6]' class='form-control' placeholder='Password' required>";
                            }
                        ?>
                    </div>
                </div>
                <div class="form-group" <?php if (isset($EditUser)) {
                    echo "hidden";
                } ?>>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" id="Password2" name="PasswordCheck" class="form-control"
                               placeholder="Password (repeat)" <?php if (!isset($EditUser)) {
                                   echo "required";
                        } ?>>
                    </div>
                </div>
                <hr <?php if (isset($EditUser)) {
                    echo "hidden";
                } ?>/>
                <div class="form-group">
                    <label for="EMail" class="cols-sm-2 control-label"> E-Mail-Address: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-envelope"></i> </span>
                        </div>
                        <input class="form-control" type="email" id="EMail" name="UserData[7]" placeholder="email@address.com" required
                            <?php
                            if (isset($EditUser)) {
                                echo "value='" . $EditUser->getUserEMail() . "'";
                                if(@$_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue'])) {
                                    echo "readonly";
                                }
                            }
                            ?>>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <?php
                        if (isset($EditUser)) {
                            echo "<a href='#deleteUserModal' data-toggle='modal' class='btn btn-danger'>Delete Account</a>";
                        } else {
                            echo "<input type='reset' class='btn btn-danger' name='Reset' value='Reset Details'>";
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-6">
                        <?php
                        if (isset($EditUser) && ($_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue']))) {
                            echo "<a class='btn btn-primary float-right'";
                            if($_SESSION['SessionUserName'] == 'admin') {
                                echo "href='?page=edituser&ChangeValue=1&EditUser=".$EditUser->getUserID()."'";
                            } else {
                                echo "href='?page=edituser&ChangeValue=1'";
                            }
                            echo ">Change Details</a>";
                        } else {
                            echo "<input type='submit' class='btn btn-success float-right' name='SaveSubmit'";
                            if(isset($EditUser) && $_GET['ChangeValue'] == 1) {
                                echo "value='Save Details'>";
                            } else {
                                echo "value='Create Account'>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>