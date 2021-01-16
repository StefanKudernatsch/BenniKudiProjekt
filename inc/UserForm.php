<?php
$DB = new DB();

if(!empty(@$_GET["username"])) {

    $edituser = $DB->getUser(@$_GET["username"]);
}

if(isset($_POST['registersubmit'])) {

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
                    <label for="anrede" class="cols-sm-2 control-label">Anrede: </label>
                    <div class="form-row">
                        <div class="input-group col-md-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fas fa-venus-mars"></i> </span>
                            </div>
                            <select name="anrede" id="anrede" class="form-control">
                                <option value="NULL">Keine Auswahl</option>
                                <option value="Herr">Herr</option>
                                <option value="Frau">Frau</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="vorname" class="cols-sm-2 control-label">Name: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                        </div>
                        <input class="form-control" type="text" name="vorname" id="vorname" placeholder="Vorname"
                               required>
                    </div>
                </div>

                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-user"></i> </span>
                    </div>
                    <input class="form-control" type="text" name="nachname" id="nachname" placeholder="Nachname"
                           required>
                </div>
                <hr/>
                <div>
                    <label for="adresse">Adresse: </label>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                        </div>
                        <input class="form-control" type="text" id="adresse" name="adresse" placeholder="Straße 123/4">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-6 input-group">
                            <label for="plz">PLZ: </label>
                        </div>
                        <div class="col-md-6 input-group">
                            <label for="ort">Ort: </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <input class="form-control" type="number" id="plz" name="plz" placeholder="PLZ" min="1000"
                                   max="9999">
                        </div>

                        <div class="col-md-6 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            </div>
                            <input class="form-control" type="text" id="ort" name="ort" placeholder="Ort">
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="username" class="cols-sm-2 control-label">Username: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-user-circle"></i> </span>
                        </div>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username"
                               required="required">
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="password" class="cols-sm-2 control-label">Password: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                        </div>
                        <input type="password" id="password2" name="password" class="form-control"
                               placeholder="Passwort" required="required">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                        </div>
                        <input type="password" id="confirm_password2" name="confirm_password" class="form-control"
                               placeholder="Passwort bestätigen" required="required">
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="password" class="cols-sm-2 control-label"> E-Mail-Adresse: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-envelope"></i> </span>
                        </div>
                        <input class="form-control" type="email" id="email" name="email" placeholder="email@adresse.com"
                               required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="reset" class="btn btn-danger" name="reset">
                    </div>
                    <div class="form-group col-md-6">
                        <input type="submit" class="btn btn-success" name="submit" value="Hinzufügen">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>