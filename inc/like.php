<?php

$db = new DB();

$user = $db->getUser($_SESSION["SessionUserName"]);
$result = $db->getUserList();

?>
<div class="container">

    <?php
    foreach ($result as $u) {
        ?>
        <div class="col-md-12">
            <div class="extra-form textsize">
                <?php
                $likes = $db->getLikeNumber(true, $u->getUserID());
                $dislikes = $db->getLikeNumber(false, $u->getUserID());


                $userliked = $db->user_liked($user->getUserID(), true, $u->getUserID());
                $userdisliked = $db->user_liked($user->getUserID(), false, $u->getUserID());

                echo $u->getUserName();


                ?>
                <hr/>
                <form method="post">
                    <div class="row">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fas fa-comments"></i> </span>
                            </div>
                            <input class="form-control" type="text" name="comment" id="comment"
                                   placeholder="Add a comment...">
                            <input type="hidden" name="fileid" id="fileid" value="<?= $u->getUserID() ?>">
                            <button type="submit" class="btn btn-success" name="submitcomment">
                                <i class="fas fa-share"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <hr/>
                <div>
                    <?php
                    $commentnumber = $db->getCommentNumber($u->getUserID()); ?>
                    <form method="post">
                        <?php
                        if ($_SESSION["showComments"] == $u->getUserID()) {
                            ?>
                            <button type="submit" class="btn btn-primary"
                                    style="background-color: transparent; border: none !important; color: #2b2b2b; outline: none !important; box-shadow: none !important;"
                                    name="closecomments" value="<?= $u->getUserID() ?>">
                                <?php echo "Close ";
                                if ($commentnumber != 1) {
                                    echo "all ";
                                }
                                echo $commentnumber; ?> Comment<?php if ($commentnumber != 1) {
                                    echo "s";
                                } ?>
                            </button>
                            <?php
                        } else {
                            ?>
                            <button type="submit" class="btn btn-primary"
                                    style="background-color: transparent; border: none !important; color: #2b2b2b; outline: none !important; box-shadow: none !important;"
                                    name="viewcomments" value="<?= $u->getUserID() ?>">
                                <?php echo "View ";
                                if ($commentnumber != 1) {
                                    echo "all ";
                                }
                                echo $commentnumber; ?> Comment<?php if ($commentnumber != 1) {
                                    echo "s";
                                } ?>
                            </button>
                        <?php } ?>
                    </form>
                </div>
                <?php
                if ($_SESSION["showComments"] == $u->getUserID()) {

                    ?>
                    <div style="font-weight: normal">
                        <hr style='border-top: dashed 2px; color: #d9d9da'/>
                        <?php
                        $comments = $db->getCommentList();
                        foreach ($comments as $comment) {
                            $tempuser = $db->getUserWithID($comment->getUserID());
                            if ($comment->getFileID() == $u->getUserID()) {

                                if($_SESSION["editcomment"] == $comment->getCommentID()){
                                    ?>
                                    <form method="post">
                                        <div class="form-group form-inline">
                                            <label><?= $tempuser->getUserName() ?>:</label>
                                            <input style="background-color: transparent; border: none !important"
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
                                    if ($comment->getUserID() == $user->getUserID()) {
                                        ?>
                                        <div class='float-right'>
                                            <a style='color: #515151' href='#' role='button' id='dropdownMenuLink'
                                               data-toggle='dropdown'>
                                                <i class='fas fa-ellipsis-h'></i></a>
                                            <div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>
                                                <form method="post">
                                                    <button type="submit" class="dropdown-item"
                                                            style="border: none !important; outline: none !important; box-shadow: none !important;"
                                                            name="editcomment" value="<?= $comment->getCommentID() ?>">Edit</button>
                                                    <button type="submit" class="dropdown-item"
                                                            style=" border: none !important; outline: none !important; box-shadow: none !important;"
                                                            name="deletecomment" value="<?= $comment->getCommentID() ?>">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php }
                                }
                                ?>
                                <hr style='border-top: dashed 2px; color: #d9d9da'/>
                                <?php
                            }
                        }
                        ?>
                    </div>
                <?php } ?>
                <hr/>
                <form method="post">
                <div class="row">
                        <?php
                        if ($userliked == 0) {
                            ?>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary"
                                        style="background-color: transparent; border: none !important; color: #1f1fff; outline: none !important; box-shadow: none !important;"
                                        name="liked" value="<?= $u->getUserID() ?>">
                                    <i class="far fa-thumbs-up fa-lg"></i></button>
                                <?= $likes; ?>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary"
                                         style="background-color: transparent; border: none !important; color: #1f1fff; outline: none !important; box-shadow: none !important;"
                                         name="remlike" value="<?= $u->getUserID() ?>">
                                    <i class="fas fa-thumbs-up fa-lg"></i></button>
                                <?= $likes; ?>
                            </div>
                        <?php }
                        if ($userdisliked == 0) {
                            ?>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary"
                                        style="background-color: transparent; border: none !important; color: red; outline: none !important; box-shadow: none !important;"
                                        name="dislike" value="<?= $u->getUserID() ?>">
                                    <i class="far fa-thumbs-down fa-lg"></i></button>
                                <?= $dislikes; ?>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary"
                                        style="background-color: transparent; border: none !important; color: red; outline: none !important; box-shadow: none !important;"
                                        name="remdislike" value="<?= $u->getUserID() ?>">
                                    <i class="fas fa-thumbs-down fa-lg"></i></button>
                                <?= $dislikes; ?>
                            </div>
                        <?php } ?>
                </div>
                </form>
            </div>
        </div>
        <?php
        echo "<br>";
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
        else{
            unset($_SESSION["editcomment"]);
        }
    }


    ?>

</div>
<?php
if (isset($_POST["liked"])) {
   $_SESSION["liked"] = $_POST["liked"];
}
else {
    unset($_SESSION["liked"]);
}

if (isset($_POST["dislike"])) {
    $_SESSION["dislike"] = $_POST["dislike"];
}
else {
    unset($_SESSION["dislike"]);
}

if (isset($_POST["remlike"])) {
    $_SESSION["remlike"] = $_POST["remlike"];
}
else {
    unset($_SESSION["remlike"]);
}

if (isset($_POST["remdislike"])) {
    $_SESSION["remdislike"] = $_POST["remdislike"];
}
else {
    unset($_SESSION["remdislike"]);
}

if (isset($_SESSION["liked"])) {
    $userdisliked = $db->user_liked($user->getUserID(), false, $_SESSION["liked"]);

    if ($userdisliked == true) {
        $db->removeLike(false, $user->getUserID(), $_SESSION["liked"]);
    }
    $db->addLike(true, $user->getUserID(), $_SESSION["liked"]);

    echo "<script>window.location.href='index.php?page=like';</script>";
}


if (isset($_SESSION["dislike"])) {
    $userliked = $db->user_liked($user->getUserID(), true, $_SESSION["dislike"]);
    if ($userliked == true) {
        $db->removeLike(true, $user->getUserID(), $_SESSION["dislike"]);
    }
    $db->addLike(false, $user->getUserID(), $_SESSION["dislike"]);
    echo "<script>window.location.href='index.php?page=like';</script>";
}

if (isset($_SESSION["remlike"])) {
    $db->removeLike(true, $user->getUserID(), $_SESSION["remlike"]);
    echo "<script>window.location.href='index.php?page=like';</script>";
}

if (isset($_SESSION["remdislike"])) {
    $db->removeLike(false, $user->getUserID(), $_SESSION["remdislike"]);
    echo "<script>window.location.href='index.php?page=like';</script>";
}


if (isset($_POST["deletecomment"])) {
    $db->deleteComment($_POST["deletecomment"]);
    echo "<script>window.location.href='index.php?page=like';</script>";
}

if (isset($_POST["editcommentsubmit"])) {
    $newcom = $_POST["newcomment"];
    $comid = $_POST["commentid"];
    $db->editComment($comid, $newcom);
    echo "<script>window.location.href='index.php?page=like';</script>";
}
if (isset($_POST["canceledit"])) {
    echo "<script>window.location.href='index.php?page=like';</script>";
}
if (isset($_POST["submitcomment"])) {
    $fileID = $_POST["fileid"];
    $comment = $_POST["comment"];
    echo $fileID;
    echo $comment;
    $db->addComment($comment, $user->getUserID(), $fileID);
    echo "<script>window.location.href='index.php?page=like';</script>";
}
?>


