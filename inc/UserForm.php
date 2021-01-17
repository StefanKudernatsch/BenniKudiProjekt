<?php
$DB = new DB();

if(!empty($_SESSION["SessionUserName"])) {

    $EditUser = $DB->getUser($_SESSION["SessionUserName"]);
}

if(isset($_POST['Submit'])) {

    $UserData = $_POST['UserData'];
    $CheckInput = true;

    if($UserData[6] != $_POST['PasswordCheck']) {

        $CheckInput = false;
        echo "<script language='JavaScript'>alert('Error | Passwords must be same')</script>";
    }

    if(!isset($_POST['blob'])) {

        if($_FILES['blob']['error'] != 0) {

            $CheckInput = false;
            echo "<script language='JavaScript'>alert('Error | Image Upload failed')</script>";
        }

        else {

            if($_FILES['blob']['type'] == "image/jpeg" || $_FILES['blob']['type'] == "image/jpg" || $_FILES['blob']['type'] == "image/png") {

                //$blob = file_get_contents($_FILES['blob']['tmp_name']);
                //$blob = file_get_contents(addslashes($_FILES['blob']['tmp_name']));
                //echo $blob;
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
        if($i != 7 && $i != 4 && $i != 6 && $i != 3) {

            if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬;-]/', $UserData[$i])) {

                $CheckInput = false;
                echo "<script language='JavaScript'>alert('Error1 | Special characters are not allowed')</script>";
                break;
            }
        }

        if(empty($UserData[$i]) == true && ($i == 8 || $i == 9 || $i == 10)) {

            $UserData[$i] = "NULL";
        }
    }

    if(preg_match('/[\'^£$%&*()}{#~?><>,|=_+¬;-]/', $UserData[7])) {

        $CheckInput = false;
        echo "<script language='JavaScript'>alert('Error2 | Special characters are not allowed')</script>";
    }

    if($CheckInput == true) {

        if(!empty($_SESSION["SessionUserName"])) {

            $EditUser->setUserGender($UserData[0]);
            $EditUser->setUserFirstName($UserData[1]);
            $EditUser->setUserLastName($UserData[2]);
            $EditUser->setUserBirthday($UserData[3]);
            $EditUser->setUserImage($UserData[4]);
            $EditUser->setUserName($UserData[5]);
            $EditUser->setUserEMail($UserData[7]);
            $EditUser->setUserCity($UserData[8]);
            $EditUser->setUserPLZ($UserData[9]);
            $EditUser->setUserAddress($UserData[10]);

            if($DB->updateUser($EditUser)) {

                echo "<script language='JavaScript'>alert('Account details changed successfully')</script>";
            }

            else {

                echo "<script language='JavaScript'>alert('Error | Change account details failed')</script>";
            }
            header("Location: index.php?page=UserForm");
        }

        else {

            $User = new User($UserData[0], $UserData[1], $UserData[2], $UserData[3], $blob, $UserData[5], $UserData[6], $UserData[7], $UserData[8], $UserData[9], $UserData[10]);

            if($DB->registerUser($User)) {

                echo "<script language='JavaScript'>alert('Account created successfully')</script>";
            }

            else {

                echo "<script language='JavaScript'>alert('Error | Create account failed')</script>";
            }
            //header("Location: index.php");
        }
    }
}
?>
<div class="container">
    <div class="main-login main-center">
        <h1 class="card-title mt-3 text-center">Create Account</h1>
        <div class="container formtop col-md-12 col-sm-12">

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="blob" accept=".jpg,.png,.jpeg">
                </div>
                <div class="form-group">
                    <label for="Gender" class="cols-sm-2 control-label">Gender: </label>
                    <div class="form-row">
                        <div class="input-group col-md-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fas fa-venus-mars"></i> </span>
                            </div>
                            <select name="UserData[0]" id="Gender" class="form-control">
                                <option value="NULL">Select...</option>
                                <option value="Herr">Herr</option>
                                <option value="Frau">Frau</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Birthday" class="cols-sm-2 control-label">Birthday: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                        </div>
                        <input class="form-control" type="date" name="UserData[3]" id="Birthday" placeholder="1.1.2021" required>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="FirstName" class="cols-sm-2 control-label">Name: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                        </div>
                        <input class="form-control" type="text" name="UserData[1]" id="FirstName" placeholder="First Name" required>
                    </div>
                </div>

                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                    </div>
                    <input class="form-control" type="text" name="UserData[2]" id="LastName" placeholder="Last Name" required>
                </div>
                <hr/>
                <div>
                    <label for="Address">Address: </label>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                        </div>
                        <input class="form-control" type="text" id="Address" name="UserData[10]" placeholder="Straße 123/4">
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
                            <input class="form-control" type="number" id="PLZ" name="UserData[9]" placeholder="1200" min="1000" max="9999">
                        </div>

                        <div class="col-md-6 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            </div>
                            <input class="form-control" type="text" id="City" name="UserData[8]" placeholder="Vienna">
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
                        <input type="text" id="Username" name="UserData[5]" class="form-control" placeholder="Username" required>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="Password" class="cols-sm-2 control-label">Password: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                        </div>
                        <input type="password" id="Password" name="UserData[6]" class="form-control" placeholder="Password" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                        </div>
                        <input type="password" id="Password2" name="PasswordCheck" class="form-control" placeholder="Password (repeat)" required">
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="EMail" class="cols-sm-2 control-label"> E-Mail-Address: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-envelope"></i> </span>
                        </div>
                        <input class="form-control" type="email" id="EMail" name="UserData[7]" placeholder="email@address.com" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="reset" class="btn btn-danger" name="Reset" value="Reset Details">
                    </div>
                    <div class="form-group col-md-6">
                        <input type="submit" class="btn btn-success" name="Submit" value="Create Account">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>