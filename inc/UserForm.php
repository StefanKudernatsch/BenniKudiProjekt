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
    <div class="jumbotron">
        <h1 class="display-2" style="padding-bottom: 20px">create new account</h1>
    </div>
</div>
<div class="container">
    <form method="post">
        <table class="table">
            <thead>

            </thead>
            <tbody>
            <tr>
                <td>Gender</td>
                <td>
                    <select name="register[0]">
                        <option value="NULL" <?php if(isset($edituser)){if($edituser->getAnrede()==NULL){echo "selected";}}?>>Auswählen</option>
                        <option value="Herr" <?php if(isset($edituser)){if($edituser->getAnrede()=='Herr'){echo "selected";}}?>>Herr</option>
                        <option value="Frau" <?php if(isset($edituser)){if($edituser->getAnrede()=='Frau'){echo "selected";}}?>>Frau</option>
                    </select>
                </td>
            </tr>
            <tr class="table-active">
                <td>First Name</td>
                <td><input type="text" name="userdata[1]" placeholder="First Name" <?php if(isset($edituser)){echo "value='".$edituser->getVorname()."'";}?> required></td>
            </tr>
            <tr class="table-active">
                <td>Last Name</td>
                <td><input type="text" name="userdata[2]" placeholder="Last Name" <?php if(isset($edituser)){echo "value='".$edituser->getNachname()."'";}?> required></td>
            </tr>
            <tr class="table-active">
                <td>Birthday</td>
                <td><input type="date" name="userdata[3]" placeholder="Birthday" <?php if(isset($edituser)){echo "value='".$edituser->getNachname()."'";}?> required></td>
            </tr>
            <tr class="table-active">
                <td>Image</td>
                <td><input type="file" name="userdata[4]" placeholder="Image" <?php if(isset($edituser)){echo "value='".$edituser->getNachname()."'";}?> required></td>
            </tr>
            <tr class="table-active">
                <td>Username</td>
                <td><input type="text" name="userdata[6]" placeholder="Username" <?php if(isset($edituser)){echo "value='".$edituser->getUsername()."'";}?> required></td>
            </tr>
            <tr class="table-active">
                <td>Password</td>
                <td><input type="password" name="userdata[7]" required></td>
            </tr>
            <tr class="table-active">
                <td>repeat Password</td>
                <td><input type="password" name="userdata[8]" required></td>
            </tr>
            <tr class="table-active">
                <td>E-Mail-Address</td>
                <td><input type="email" name="userdata[9]" placeholder="E-Mail-Address" style="width: 50%" <?php if(isset($edituser)){echo "value='".$edituser->getEmailadresse()."'";}?> required></td>
            </tr>
            <tr>
                <td>City</td>
                <td><input type="text" name="userdata[5]" placeholder="Wien" <?php if(isset($edituser)){echo "value='".$edituser->getOrt()."'";}?> ></td>
            </tr>
            <tr>
                <td>PLZ</td>
                <td><input type="number" name="userdata[4]" placeholder="1200" <?php if(isset($edituser)){echo "value='".$edituser->getPlz()."'";}?> ></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><input type="text" name="userdata[3]" placeholder="Address" <?php if(isset($edituser)){echo "value='".$edituser->getAdresse()."'";}?> ></td>
            </tr>
            </tbody>
        </table>
        <input type="submit" name="submit" class="btn btn-success" <?php if(isset($edituser)){echo "value='Bearbeiten'";} else{echo "value='Hinzufügen'";}?>>
        <input type="reset" value="Reset" class="btn btn-danger">
    </form>
</div>