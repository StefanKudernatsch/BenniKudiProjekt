<?php
$DB = new DB();
if (isset($_SESSION["SessionUserName"])) {
    if ($_SESSION["SessionUserName"] == 'admin') {
        $FileList = $DB->getAllFiles();
        $admincheck = 1;
    } else {



        echo "<div style='position: fixed; z-index: 1; left: 100px; top: 100px; max-height: max-content; width: 150px;'>";
        echo "<a href='#UploadFileModal' data-toggle='modal'  class='btn btn-primary' style='margin-bottom: 10px; width: 150px;'>Create Post</a>";
        echo "<a href='?page=home&UserPosts=" . $_SESSION['SessionUserName'] . "' class='btn btn-info' style='width: 150px;'>My Posts</a>";
        echo "</div>";

        echo "<div class='container'>";

        $UserID = $DB->getUser($_SESSION["SessionUserName"])->getUserID();
        $FriendList = $DB->getFriendList($UserID);
        $FileList = $DB->getPublicFiles();
        $PrivateFileList = $DB->getPrivateUserFiles($UserID);
        $i = sizeof($FileList);
        if(!empty($PrivateFileList)){
            foreach ($PrivateFileList as $private) {
                $FileList[$i] = $private;
                $i++;
            }
        }

        if(!empty($FriendList)){
            foreach ($FriendList as $friend) {
                $FriendFileList = $DB->getFriendFiles($friend->getUserID());
                if(!empty($FriendFileList)){
                    foreach ($FriendFileList as $friendfiles) {
                        $FileList[$i] = $friendfiles;
                        $i++;
                    }
                }
            }
        }
        function sortbyfiledate($a, $b)
        {
            return strcmp($b->getFileDate(), $a->getFileDate());
        }

        if (!empty($FileList)) {
            usort($FileList, "sortbyfiledate");
        }
    }
} else {
    $FileList = $DB->getPublicFiles();
}

if (isset($_POST["CreateFileSubmit"])) {
    $FileDate = date("Y-m-d H:i:s");
    //$TagID = $DB->getTag($_POST["tag_name"]);
    $TagID = NULL;
    if ($_POST["file_showtype"] == 'private') {
        $ShowType = 0;
    } else {
        $ShowType = 1;
    }
    if ($_FILES["file_upload"]["error"] == 4) {
        $FileType = 0;
    } else {
        if ($_FILES["file_upload"]["error"] == 0) {
            $FileType = 1;
            $FilePath = "./users/" . $UserID . "/";
            $FilePath = $FilePath . $_FILES['file_upload']['name'];
            move_uploaded_file($_FILES["file_upload"]["tmp_name"], $FilePath);
        } else {
            echo "<script language='JavaScript'>alert('Error | Upload picture failed')</script>";
        }
    }
    $File = new File($_POST["file_title"], $UserID, $FileDate, $TagID, $ShowType, $FileType, $_POST["file_text"], $FilePath);
    if ($DB->uploadFile($File)) {
        echo "<script language='JavaScript'>alert('Uploaded post successfully')</script>";
    } else {
        echo "<script language='JavaScript'>alert('Error | Uploading post failed')</script>";
    }
    echo "<script>window.location.href='index.php?page=home';</script>";
}

if (isset($_GET["UserPosts"]) && @$_GET["UserPosts"] == $_SESSION["SessionUserName"]) {
    $FileList = $DB->getUserFiles($UserID);
    $usercheck = 1;
    if(isset($_GET["ChangeShowType"]) ) {
        for ($i = 0; $i < sizeof($FileList); $i++) {
            if($FileList[$i]->getFileID() == $_GET["ChangeShowType"]) {
                if($FileList[$i]->getShowType() == 0) {
                    $DB->changeShowType(1, $FileList[$i]->getFileID());
                } else if($FileList[$i]->getShowType() == 1){
                    $DB->changeShowType(0, $FileList[$i]->getFileID());
                }
                echo "<script>window.location.href='index.php?page=home&UserPosts=".$_SESSION["SessionUserName"]."';</script>";
            }
        }
    }
}

if (isset($_POST["DeletePostSubmit"])) {
    $DB->deleteFile($_POST["file_id"]);
    if (isset($usercheck)) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else if (isset($admincheck)) {
        echo "<script>window.location.href='index.php?page=home';</script>";
    }
}

if (isset($_POST["liked"])) {
    $_SESSION["liked"] = $_POST["liked"];
} else {
    unset($_SESSION["liked"]);
}

if (isset($_POST["dislike"])) {
    $_SESSION["dislike"] = $_POST["dislike"];
} else {
    unset($_SESSION["dislike"]);
}

if (isset($_POST["remlike"])) {
    $_SESSION["remlike"] = $_POST["remlike"];
} else {
    unset($_SESSION["remlike"]);
}

if (isset($_POST["remdislike"])) {
    $_SESSION["remdislike"] = $_POST["remdislike"];
} else {
    unset($_SESSION["remdislike"]);
}

if (isset($_SESSION["liked"])) {
    $userdisliked = $DB->user_liked($UserID, false, $_SESSION["liked"]);

    if ($userdisliked == true) {
        $DB->removeLike(false, $UserID, $_SESSION["liked"]);
    }

    $DB->addLike(true, $UserID, $_SESSION["liked"]);
    echo("<meta http-equiv='refresh' content='0'>");
}


if (isset($_SESSION["dislike"])) {
    $userliked = $DB->user_liked($UserID, true, $_SESSION["dislike"]);
    if ($userliked == true) {
        $DB->removeLike(true, $UserID, $_SESSION["dislike"]);
    }
    $DB->addLike(false, $UserID, $_SESSION["dislike"]);
    echo("<meta http-equiv='refresh' content='0'>");
}

if (isset($_SESSION["remlike"])) {
    $DB->removeLike(true, $UserID, $_SESSION["remlike"]);
    echo("<meta http-equiv='refresh' content='0'>");
}

if (isset($_SESSION["remdislike"])) {
    $DB->removeLike(false, $UserID, $_SESSION["remdislike"]);
    echo("<meta http-equiv='refresh' content='0'>");
}

if (isset($_POST["closecomments"])) {
    unset($_SESSION["showComments"]);
    echo("<meta http-equiv='refresh' content='0'>"); //Refresh by HTTP 'meta'
}

if (isset($_POST["viewcomments"])) {
    $_SESSION["showComments"] = $_POST["viewcomments"];
    echo("<meta http-equiv='refresh' content='0'>"); //Refresh by HTTP 'meta'
}

if ((isset($_POST["editcomment"]))) {
    $_SESSION["editcomment"] = $_POST["editcomment"];
    echo("<meta http-equiv='refresh' content='0'>"); //Refresh by HTTP 'meta'
}


if (isset($_POST["deletecomment"])) {
    $DB->deleteComment($_POST["deletecomment"]);
    echo("<meta http-equiv='refresh' content='0'>"); //Refresh by HTTP 'meta'
}

if (isset($_POST["editcommentsubmit"])) {
    $newcom = $_POST["newcomment"];
    $comid = $_POST["commentid"];
    unset($_SESSION["editcomment"]);
    $DB->editComment($comid, $newcom);
    echo("<meta http-equiv='refresh' content='0'>"); //Refresh by HTTP 'meta'0
}
if (isset($_POST["canceledit"])) {
    unset($_SESSION["editcomment"]);
    echo("<meta http-equiv='refresh' content='0'>"); //Refresh by HTTP 'meta'
}
if (isset($_POST["submitcomment"])) {
    $fileID = $_POST["commentfileid"];
    $comment = $_POST["comment"];
    echo $fileID;
    echo $comment;
    $DB->addComment($comment, $UserID, $fileID);
    echo("<meta http-equiv='refresh' content='0'>"); //Refresh by HTTP 'meta'
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
                                            <textarea rows="3" cols="42" placeholder="Post Text" name="file_text"
                                                      required></textarea>
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
                                            <input type="checkbox" id="file_showtype" name="file_showtype"
                                                   value="private">
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

    $likes = $DB->getLikeNumber(true, $file->getFileID());
    $dislikes = $DB->getLikeNumber(false, $file->getFileID());


    $userliked = $DB->user_liked($UserID, true, $file->getFileID());
    $userdisliked = $DB->user_liked($UserID, false, $file->getFileID());
    ?>
    <div id='DeletePostModal<?= $file->getFileID() ?>' class='modal fade'>
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
                    <input type="hidden" name="file_id" value="<?= $file->getFileID() ?>">
                    <div class='modal-footer'>
                        <input type='submit' class='btn btn-danger btn-block' name='DeletePostSubmit'
                               value='Delete Post'>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    echo "<div class='main-login' style='margin-left: auto; margin-right: auto; margin-top: 10px; margin-bottom: 5px; max-width: 600px; padding: 5px 30px 5px 30px; border: 1px lightgray solid;'>";
    echo "<div class='container formtop col-md-12 col-sm-12'>";
    if ((isset($admincheck) && $admincheck == 1) || (isset($usercheck) && $usercheck == 1)) {
        echo "<a href='#DeletePostModal" . $file->getFileID() . "' data-toggle='modal' style='float: right;'><span><i class='fas fa-times' style='color: red'></i></a>";
    }
    echo "<div class='form-group'>";

    ?>


    <div style="display: flex; flex-flow: column wrap; align-content: space-between; height: 50px; margin-bottom: 15px;">
        <div style="max-width: fit-content; text-align: center;">
            <?="<h4>" . $file->getFileName() . "</h4>";?>

        </div>
        <div class="image_outer_container" style="display: inline-flex; max-width: max-content;">
            <?="<a style='padding-top: 15px; max-width: max-content; margin-right: 5px;'>@" . $DB->getUsernameWithID($file->getUserID()) . "</a>"; ?>
            <div class="image_inner_container" style="">
                <?php
                //$tempuser = $DB->getUser($_SESSION["SessionUserName"]);
                //$image=$DB->getUserImage($tempuser->getUserID());


                $image=$DB->getUserImage($file->getUserID());
                echo '
                <img style="height: 50px; width: 50px" src="data:image/png;base64,'.base64_encode($image).'"/>
                ';
                ?>
            </div>
        </div>
    </div>

    <?php
    if ($file->getFileType() == 0) {
        echo "<p style='margin-bottom: 5px;'>" . $file->getFileText() . "</p>";
    } else {
        echo "<a data-fancybox='gallery' href='" . $file->getFilePath() . "'><img src='" . $file->getFilePath() . "' style='max-width: 100%; height: auto; border-radius: 10px; margin-bottom: 10px;'></a>";
        echo "<p style='margin-bottom: 5px;'>" . $file->getFileText() . "</p>";
    }
    echo "<a style='font-size: small;'>" . $file->getFileDate() . "</a>";
    echo "<a style='font-size: small; float: right;'";
    if(isset($usercheck) && $usercheck == 1) {
        echo "href='index.php?page=home&UserPosts=Kudi&ChangeShowType=".$file->getFileID()."'";
    }
    if ($file->getShowType() == 0) {
        echo ">private</a>";
    } else {
        echo ">public</a>";
    }
    echo "<br><div class='form-group' style='border-top: 1px lightgray solid; padding-top: 5px;'>";
    ?>
    <form style="width: 100%" method="post" class="mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"> <i class="fas fa-comments"></i> </span>
            </div>
            <input <?php if (!isset($_SESSION["SessionUserName"])) {
                echo "disabled";
            } ?> class="form-control" type="text" name="comment" id="comment"
                   placeholder="Add a comment...">
            <input type="hidden" name="commentfileid" id="commentfileid" value="<?= $file->getFileID() ?>">
            <button <?php if (!isset($_SESSION["SessionUserName"])) {
                echo "disabled";
            } ?> type="submit" class="btn btn-success" name="submitcomment">
                <i class="fas fa-share"></i>
            </button>
        </div>
    </form>

    <div style="font-weight: normal">
        <?php
        $comments = $DB->getCommentList();
        ?>
    <div>
        <?php
        $commentnumber = $DB->getCommentNumber($file->getFileID()); ?>
        <form method="post">
            <?php
            if ($_SESSION["showComments"] == $file->getFileID()) {
                ?>
                    <div class="mt-3 text-center">
                        <button type="submit" class="btn btn-primary"
                                style="background-color: transparent; border: none !important; color: #2b2b2b; outline: none !important; box-shadow: none !important;"
                                name="closecomments" value="<?= $file->getFileID() ?>">
                            <?php echo "Close ";
                            if ($commentnumber != 1) {
                                echo "all ";
                            }
                            echo $commentnumber; ?> Comment<?php if ($commentnumber != 1) {
                                echo "s";
                            } ?>
                        </button>
                    </div>
                <?php
            } else {
                if($commentnumber != 0){
                    ?>
            <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary"
                            style="background-color: transparent; border: none !important; color: #2b2b2b; outline: none !important; box-shadow: none !important;"
                            name="viewcomments" value="<?= $file->getFileID() ?>">
                        <?php echo "View ";
                        if ($commentnumber != 1) {
                            echo "all ";
                        }
                        echo $commentnumber; ?> Comment<?php if ($commentnumber != 1) {
                            echo "s";
                        } ?>
                    </button>
            </div>
                <?php }
                else{
                    ?>
                    <p class="mt-3 text-center">There are no comments for this post.
                    </p>
                    <?php
                }
            } ?>
        </form>
    </div>
    <?php
        if ($_SESSION["showComments"] == $file->getFileID()) {

            ?>
            <div style="font-weight: normal">

                <?php
                $comments = $DB->getCommentList();
                foreach ($comments as $comment) {
                    $tempuser = $DB->getUserWithID($comment->getUserID());

                    if ($comment->getFileID() == $file->getFileID()) {
                        echo "<hr style='border-top: dashed 2px; color: #d9d9da'/>";
                        if ($_SESSION["editcomment"] == $comment->getCommentID()) {
                            ?>
                            <form method="post">
                                <div class="form-group form-inline">
                                    <label class="col-2"><?= $tempuser->getUserName() ?>:</label>
                                    <input style=" background-color: transparent; border: none !important"
                                           type="text" name="newcomment" id="newcomment"
                                           class="form-control col"
                                           value="<?= $comment->getCommentText() ?>">
                                    <input type="hidden" name="commentid" id="commentid"
                                           value="<?= $comment->getCommentID() ?>">
                                    <button type="submit" class="btn btn-success" name="editcommentsubmit">
                                        <i class="fas fa-share"></i>
                                    </button>
                                    <button type="submit" class="btn btn-danger" name="canceledit">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </form>
                            <?php

                        } else {
                            echo "<b>" . $tempuser->getUserName() . ": </b> " . $comment->getCommentText();
                            if ($comment->getUserID() == $UserID) {
                                ?>
                                <div class='float-right'>
                                    <a style='color: #515151' href='#' role='button' id='dropdownMenuLink'
                                       data-toggle='dropdown'>
                                        <i class='fas fa-ellipsis-h'></i></a>
                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>
                                        <form method="post">
                                            <button type="submit" class="dropdown-item"
                                                    style="border: none !important; outline: none !important; box-shadow: none !important;"
                                                    name="editcomment" value="<?= $comment->getCommentID() ?>">
                                                Edit
                                            </button>
                                            <button type="submit" class="dropdown-item"
                                                    style=" border: none !important; outline: none !important; box-shadow: none !important;"
                                                    name="deletecomment"
                                                    value="<?= $comment->getCommentID() ?>">Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php }
                        }
                    }
                }
                ?>
            </div>
        <?php }
        ?>

    </div>
    <form method="post">
        <hr/>
        <div class="row">
            <?php
            if ($userliked == 0) {
                ?>
                <div class="col-6">
                    <button <?php if (!isset($_SESSION["SessionUserName"])) {
                        echo "disabled";
                    } ?> type="submit" class="btn btn-primary"
                         style="background-color: transparent; border: none !important; color: #1f1fff; outline: none !important; box-shadow: none !important;"
                         name="liked" value="<?= $file->getFileID() ?>">
                        <i class="far fa-thumbs-up fa-lg"></i></button>
                    <?= $likes; ?>
                </div>
            <?php } else { ?>
                <div class="col-6">
                    <button type="submit" class="btn btn-primary"
                            style="background-color: transparent; border: none !important; color: #1f1fff; outline: none !important; box-shadow: none !important;"
                            name="remlike" value="<?= $file->getFileID() ?>">
                        <i class="fas fa-thumbs-up fa-lg"></i></button>
                    <?= $likes; ?>
                </div>
            <?php }
            if ($userdisliked == 0) {
                ?>
                <div class="col-6">
                    <button <?php if (!isset($_SESSION["SessionUserName"])) {
                        echo "disabled";
                    } ?> type="submit" class="btn btn-primary"
                         style="background-color: transparent; border: none !important; color: red; outline: none !important; box-shadow: none !important;"
                         name="dislike" value="<?= $file->getFileID() ?>">
                        <i class="far fa-thumbs-down fa-lg"></i></button>
                    <?= $dislikes; ?>
                </div>
            <?php } else { ?>
                <div class="col-6">
                    <button type="submit" class="btn btn-primary"
                            style="background-color: transparent; border: none !important; color: red; outline: none !important; box-shadow: none !important;"
                            name="remdislike" value="<?= $file->getFileID() ?>">
                        <i class="fas fa-thumbs-down fa-lg"></i></button>
                    <?= $dislikes; ?>
                </div>
            <?php } ?>
        </div>
    </form>
    <?php
    echo "</div></div></div></div>";
}
echo "</div>";
?>

