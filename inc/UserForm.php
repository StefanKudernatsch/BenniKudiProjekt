<?php
$DB = new DB();

if(!empty(@$_GET["username"])) {

    $edituser = $DB->getUser(@$_GET["username"]);
}

if(isset($_POST['submit'])) {

    $userdata = $_POST['userdata'];
    $checkinput = true;

    if($userdata[7] != $userdata[8]) {

        $checkinput = false;
        echo "<script language='JavaScript'>alert('Inkorrekte Eingabe | Passwörter müssen gleich sein')</script>";
    }

    for ($i = 0; $i < 10; $i++) {

        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬;-]/', $userdata[$i]) && $i != 9) {

            $checkinput = false;
            echo "<script language='JavaScript'>alert('Inkorrekte Eingabe | Sonderzeichen nicht erlaubt')</script>";
            break;
        }

        else if(preg_match('/[\'^£$%&*()}{#~?><>,|=_+¬;-]/', $userdata[$i])) {

            $checkinput = false;
            echo "<script language='JavaScript'>alert('Inkorrekte Eingabe | Sonderzeichen nicht erlaubt')</script>";
            break;
        }

        if(empty($register[$i]) == true) {

            $register[$i] = "NULL";
        }
    }

    if($checkinput == true) {

        if(!empty(@$_GET["username"])) {

            $edituser->setAnrede($register[0]);
            $edituser->setVorname($register[1]);
            $edituser->setNachname($register[2]);
            $edituser->setAdresse($register[3]);
            $edituser->setPlz($register[4]);
            $edituser->setOrt($register[5]);
            $edituser->setUsername($register[6]);
            $edituser->setPasswort($register[7]);
            $edituser->setEmailadresse($register[9]);

            if($DB->updateUser($edituser)) {

                echo "<script language='JavaScript'>alert('User erfolgreich bearbeitet')</script>";
            }

            else {

                echo "<script language='JavaScript'>alert('Fehler beim bearbeiten')</script>";
            }
            header("Location: index.php?page=userAdministration");
        }

        else {

            $user = new User($userdata[0], $userdata[1], $userdata[2], $userdata[3], $userdata[4], $userdata[5], $userdata[6], $userdata[7], $userdata[9]);

            if($DB->registerUser($user)) {

                echo "<script language='JavaScript'>alert('User erfolgreich hinzugefügt')</script>";
            }

            else {

                echo "<script language='JavaScript'>alert('Fehler beim hinzufügen')</script>";
            }
            header("Location: index.php");
        }
    }
}
?>
<div class="container">
    <div class="main-login main-center">
        <h1 class="card-title mt-3 text-center">Create Account</h1>
        <div class="container formtop col-md-12 col-sm-12">

            <form method="post">
                <div class="form-group">
                    <label for="Gender" class="cols-sm-2 control-label">Gender: </label>
                    <div class="form-row">
                        <div class="input-group col-md-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fas fa-venus-mars"></i> </span>
                            </div>
                            <select name="Gender" id="Gender" class="form-control">
                                <option value="NULL">Select...</option>
                                <option value="Herr">Herr</option>
                                <option value="Frau">Frau</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="FirstName" class="cols-sm-2 control-label">Name: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                        </div>
                        <input class="form-control" type="text" name="FirstName" id="FirstName" placeholder="First Name" required>
                    </div>
                </div>

                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                    </div>
                    <input class="form-control" type="text" name="LastName" id="LastName" placeholder="Last Name" required>
                </div>
                <hr/>
                <div>
                    <label for="Address">Address: </label>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                        </div>
                        <input class="form-control" type="text" id="Address" name="Address" placeholder="Straße 123/4">
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
                            <input class="form-control" type="number" id="PLZ" name="PLZ" placeholder="1200" min="1000"
                                   max="9999">
                        </div>

                        <div class="col-md-6 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            </div>
                            <input class="form-control" type="text" id="City" name="City" placeholder="Vienna">
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
                        <input type="text" id="Username" name="Username" class="form-control" placeholder="Username" required="required">
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="Password" class="cols-sm-2 control-label">Password: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                        </div>
                        <input type="password" id="Password" name="Password" class="form-control" placeholder="Password" required="required">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                        </div>
                        <input type="password" id="Password2" name="Password2" class="form-control" placeholder="Password (repeat)" required="required">
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="EMail" class="cols-sm-2 control-label"> E-Mail-Address: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-envelope"></i> </span>
                        </div>
                        <input class="form-control" type="email" id="EMail" name="EMail" placeholder="email@address.com" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="reset" class="btn btn-danger" name="reset" value="Reset Details">
                    </div>
                    <div class="form-group col-md-6">
                        <input type="submit" class="btn btn-success" name="submit" value="Create Account">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>