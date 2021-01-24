<?php
$DB = new DB();
echo "<div class='container' style='padding-top: 98px;'>";
if (isset($_SESSION["SessionUserName"])) {
    if ($_SESSION["SessionUserName"] == 'admin') {
        $FileList = $DB->getAllFiles();
        $admincheck = 1;
    } else {
        echo "<a href='#UploadFileModal' data-toggle='modal' class='btn btn-primary' style='top: 90px; left: 7px; text-align: center; position: fixed; z-index: 1; margin-bottom: 10px; width: 150px;'>Create Post</a>";
        echo "<a href='?page=home&UserPosts=".$_SESSION['SessionUserName']."' class='btn btn-info' style='top: 135px; left: 7px; text-align: center; position: fixed; z-index: 1; width: 150px;'>My Posts</a>";
        $UserID = $DB->getUser($_SESSION["SessionUserName"])->getUserID();
        $FriendList = $DB->getFriendList($UserID);
        $FileList = $DB->getPublicFiles();
        $PrivateFileList = $DB->getPrivateUserFiles($UserID);
        $i = sizeof($FileList);
        foreach ($PrivateFileList as $private) {
            $FileList[$i] = $private;
            $i++;
        }
        foreach ($FriendList as $friend) {
            $FriendFileList = $DB->getFriendFiles($friend->getUserID());
            foreach ($FriendFileList as $friendfiles) {
                $FileList[$i] = $friendfiles;
                $i++;
            }
        }
        //var_dump($FileList);
        function sortbyfiledate($a, $b)
        {
            return strcmp($b->getFileDate(), $a->getFileDate());
        }

        if (!empty($FileList)) {
            usort($FileList, "sortbyfiledate");
        }
        echo "<br><br><br>";
        //var_dump($FileList);
    }
} else {
    $FileList = $DB->getPublicFiles();
}

if(isset($_POST["CreateFileSubmit"])) {
    $FileDate = date("Y-m-d H:i:s");
    //$TagID = $DB->getTag($_POST["tag_name"]);
    $TagID = NULL;
    if($_POST["file_showtype"] == 'private') {
        $ShowType = 0;
    } else {
        $ShowType = 1;
    }
    if($_FILES["file_upload"]["error"] == 4) {
        $FileType = 0;
    } else {
        if($_FILES["file_upload"]["error"] == 0) {
            $FileType = 1;
            $FilePath = "./users/".$UserID."/";
            $FilePath = $FilePath.$_FILES['file_upload']['name'];
            move_uploaded_file($_FILES["file_upload"]["tmp_name"], $FilePath);
        } else {
            echo "<script language='JavaScript'>alert('Error | Upload picture failed')</script>";
        }
    }
    $File = new File($_POST["file_title"], $UserID, $FileDate, $TagID, $ShowType, $FileType, $_POST["file_text"], $FilePath);
    if($DB->uploadFile($File)) {
        echo "<script language='JavaScript'>alert('Uploaded post successfully')</script>";
    } else {
        echo "<script language='JavaScript'>alert('Error | Uploading post failed')</script>";
    }
    header("Location: ?page=home");
}

if(isset($_GET["UserPosts"]) && @$_GET["UserPosts"] == $_SESSION["SessionUserName"]) {
    $FileList = $DB->getUserFiles($UserID);
    $usercheck = 1;
}

if(isset($_POST["DeletePostSubmit"])) {
    $DB->deleteFile($_POST["file_id"]);
    if(isset($usercheck)) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else if(isset($admincheck)) {
        header("Location: ?page=home");
    }
}
?>
    <div id="UploadFileModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title">Create Post</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="container">
                            <div class=" main-center">
                                <div class="container formtop col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="file_title" class="cols-sm-2 control-label">Title & Text: </label>
                                        <div class="input-group">
                                            <input type="text" id="file_title" name="file_title"
                                                   class="form-control" placeholder="Post Title" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <textarea rows="3" cols="42" placeholder="Post Text" name="file_text" required></textarea>
                                        </div>
                                        <hr/>
                                        <label for="file_upload" class="cols-sm-2 control-label">Picture: </label>
                                        <div class="input-group">
                                            <input type="file" id="file_upload" name="file_upload"
                                                   class="form-control" accept=".jpg,.png,.jpeg">
                                        </div>
                                        <hr/>
                                        <label for="tag_name" class="cols-sm-2 control-label">Tags & View: </label>
                                        <div class="input-group">
                                            <input type="text" id="tag_name" name="tag_name"
                                                   class="form-control" placeholder="Tag">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="checkbox" id="file_showtype" name="file_showtype" value="private">
                                            <label for="file_showtype" style="margin-left: 5px;">private post</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="container">
                            <input type="submit" class="btn btn-primary btn-block" name="CreateFileSubmit"
                                   value="Upload Post">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
foreach ($FileList as $file) {
    ?>
    <div id='DeletePostModal<?=$file->getFileID()?>' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form method='post'>
                    <div class='modal-header'>
                        <h4 class='modal-title'>Delete Post</h4>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    </div>
                    <div class='modal-body'>
                        <p>Are you sure you want to delete the post?</p>
                    </div>
                    <input type="hidden" name="file_id" value="<?=$file->getFileID()?>">
                    <div class='modal-footer'>
                        <input type='submit' class='btn btn-danger btn-block' name='DeletePostSubmit' value='Delete Post'>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    echo "<div class='main-login main-center' style='margin-bottom: 10px; padding-top: 5px; border: 1px lightgray solid; padding-bottom: 30px;'>";
    echo "<div class='container formtop col-md-12 col-sm-12'>";
    if((isset($admincheck) && $admincheck == 1) || (isset($usercheck) && $usercheck == 1)) {
        echo "<a href='#DeletePostModal".$file->getFileID()."' data-toggle='modal' style='float: right;'><span><i class='fas fa-times' style='color: red'></i></a>";
    }
    echo "<div class='form-group'>";
    echo "<a style='float: right;'>@".$DB->getUsernameWithID($file->getUserID())."</a>";
    echo "<h4>" . $file->getFileName() . "</h4>";
    if ($file->getFileType() == 0) {
        echo "<p style='margin-bottom: 5px;'>" . $file->getFileText() . "</p>";
    } else {
        echo "<a data-fancybox='gallery' href='".$file->getFilePath()."'><img src='".$file->getFilePath()."' style='max-width: 100%; height: auto; border-radius: 10px; margin-bottom: 10px;'></a>";
        echo "<p style='margin-bottom: 5px;'>".$file->getFileText()."</p>";
    }
    echo "<a style='font-size: small;'>".$file->getFileDate()."</a>";
    echo "<br><div class='form-group' style='border-top: 1px lightgray solid; padding-top: 5px;'>";
    //echo likes
    echo "<a href='#CommentModal".$file->getFileID()."' data-toggle='modal' style='float: right; color: gray; text-decoration: none;'>Comments</a>";
    echo "</div></div></div></div>";
}
echo "</div>";
?>