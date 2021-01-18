<?php
$DB = new DB();

if (!empty($_SESSION["SessionUserName"])) {

    $EditUser = $DB->getUser($_SESSION["SessionUserName"]);

    /*
    echo $EditUser->getUserGender();
    echo $EditUser->getUserFirstName();
    echo $EditUser->getUserLastName();
    //echo $EditUser->getUserBirthday();
    echo $EditUser->getUserName();
    echo $EditUser->getUserEMail();
    echo $EditUser->getUserCity();
    echo $EditUser->getUserPLZ();
    echo $EditUser->getUserAddress();
    */
}

if (isset($_POST['DeleteSubmit'])) {

    //echo "<script language='JavaScript'>confirm('Are you sure to delete your account?')</script>";
    echo "<script language='JavaScript'>alert('Error | Passwords must be same')</script>";
}
else if (isset($_POST['ChangeSubmit'])) {

    @$_GET['ChangeValue'] = 1;
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

        if (!empty($_SESSION["SessionUserName"])) {

            $EditUser[0]->setUserGender($UserData[0]);
            $EditUser[1]->setUserFirstName($UserData[1]);
            $EditUser[2]->setUserLastName($UserData[2]);
            $EditUser[3]->setUserBirthday($UserData[3]);
            $EditUser[4]->setUserImage($UserData[4]);
            $EditUser[5]->setUserName($UserData[5]);
            $EditUser[7]->setUserEMail($UserData[7]);
            $EditUser[8]->setUserCity($UserData[8]);
            $EditUser[9]->setUserPLZ($UserData[9]);
            $EditUser[10]->setUserAddress($UserData[10]);

            for ($i = 0; $i < 11; $i++) {

                echo "<p>$EditUser[$i]</p>";
            }

            if ($DB->updateUser($EditUser)) {

                echo "<script language='JavaScript'>alert('Account details changed successfully')</script>";
            } else {

                echo "<script language='JavaScript'>alert('Error | Change account details failed')</script>";
            }
            header("Location: index.php?page=UserForm");
        } else {


            $User = new User($UserData[0], $UserData[1], $UserData[2], $UserData[3], $_FILES["blob"], $UserData[5], $UserData[6], $UserData[7], $UserData[8], $UserData[9], $UserData[10]);
            if ($DB->registerUser($User)) {
                $tempuser = $DB->getUser($UserData[5]);
                $tempuserid = $tempuser->getUserID();
                echo $tempuserid;

                $DB->uploadImage($_FILES['blob'], $tempuserid);
                $DB->getUserImage($tempuserid);

                echo "<script language='JavaScript'>alert('Account created successfully')</script>";
            } else {

                echo "<script language='JavaScript'>alert('Error | Create account failed')</script>";
            }
            //header("Location: index.php");
        }
    }
}
?>
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
                                                echo "<input type='file' id='upload' style='display:none'>";
                                            }
                                        ?>
                                    </label
                                        <input class="addfile" type="file" name="blob" accept=".jpg,.png,.jpeg" style="padding-top: 15%">
                                    <div class="image_inner_container">
                                        <?php
                                        $tempuser = $DB->getUser($_SESSION["SessionUserName"]);
                                        $image=$DB->getUserImage($tempuser->getUserID());
                                        if(isset($image)){
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
                            <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                        </div>
                        <input class="form-control" type="date" name="UserData[3]" id="Birthday" placeholder="1.1.2021"
                               required
                            <?php
                            if (isset($EditUser)) {
                                //echo "value='".$EditUser->getUserBirthday()."'";
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
                                        echo "value='" . $EditUser->getUserAddress() . "'";
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
                <div class="form-group" <?php if (isset($EditUser)) {
                    echo "hidden";
                } ?>>
                    <label for="Password" class="cols-sm-2 control-label">Password: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                        </div>
                        <input type="password" id="Password" name="UserData[6]" class="form-control"
                               placeholder="Password" required>
                    </div>
                </div>
                <div class="form-group" <?php if (isset($EditUser)) {
                    echo "hidden";
                } ?>>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                        </div>
                        <input type="password" id="Password2" name="PasswordCheck" class="form-control"
                               placeholder="Password (repeat)" required>
                    </div>
                </div>
                <hr
                / <?php if (isset($EditUser)) {
                    echo "hidden";
                } ?>>
                <div class="form-group">
                    <label for="EMail" class="cols-sm-2 control-label"> E-Mail-Address: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-envelope"></i> </span>
                        </div>
                        <input class="form-control" type="email" id="EMail" name="UserData[7]"
                               placeholder="email@address.com" required
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
                            echo "<input type='submit' class='btn btn-danger' name='DeleteSubmit' value='Delete Account'>";
                        } else {
                            echo "<input type='reset' class='btn btn-danger' name='Reset' value='Reset Details'>";
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-6">
                        <?php
                        if (isset($EditUser) && ($_GET['ChangeValue'] == 0 || empty($_GET['ChangeValue']))) {
                            echo "<a class='btn btn-primary' href='?page=edituser&ChangeValue=1'>Change Details</a>";
                        } else {
                            echo "<input type='submit' class='btn btn-success' name='SaveSubmit' value=";
                            if(isset($EditUser)) {
                                echo "'Save Details'>";
                            } else {
                                echo "'Create Account'>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>