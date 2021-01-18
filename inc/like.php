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
                                       placeholder="Schreiben Sie ein Kommentar">
                                <input type="hidden" name="userid" id="userid" value="<?= $u->getUserID() ?>">
                                <button type="submit" class="btn btn-success" name="submit">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr/>
                    <div style="font-weight: normal">
                        <?php
                        $comments = $db->getCommentList();
                        foreach ($comments as $comment) {
                            $tempuser = $db->getUserWithID($comment->getUserID());
                            if ($comment->getFileID() == $u->getUserID()) {
                                if ((isset($_GET["editcomment"])) && ($_GET["editcomment"]==$comment->getCommentID())) {
                                    ?>
                                    <form method="post"><div class="form-group form-inline">
                                            <label><?=$tempuser->getUserName()?>:</label>
                                            <input style="background-color: transparent; border: none !important" type="text" name="newcomment" id="newcomment" class="form-control col" value="<?=$comment->getCommentText()?>">
                                            <input type="hidden" name="commentid" id="commentid" value="<?=$comment->getCommentID()?>">
                                            <button type="submit" class="btn btn-success" name="editcommentsubmit">
                                                <i class="fas fa-share"></i>
                                            </button>
                                            <button type="submit" class="btn btn-danger" name="canceledit">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <?php

                                }
                                else{
                                    echo "<b>" . $tempuser->getUserName() . ": </b> " . $comment->getCommentText();
                                    if ($comment->getUserID() == $user->getUserID()) {
                                        ?>
                                        <div class='float-right'>
                                            <a style='color: #515151' href='#' role='button' id='dropdownMenuLink'
                                               data-toggle='dropdown'>
                                                <i class='fas fa-ellipsis-h'></i></a>
                                            <div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>
                                                <a class='dropdown-item' href='?page=like&editcomment=<?= $comment->getCommentID() ?>'>Edit</a>
                                                <a class='dropdown-item'
                                                   href='?page=like&deletecomment=<?= $comment->getCommentID() ?>'>Delete</a>
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

                    <hr/>
                    <div class="row">
                        <?php
                        if ($userliked == 0) {
                            ?>
                            <div class="col-md-2">
                                <a href='?page=like&liked=<?php echo $u->getUserID(); ?>'>
                                    <i class="far fa-thumbs-up"></i></a>
                                <?= $likes; ?>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-2">
                                <a href='?page=like&remlike=<?php echo $u->getUserID() ?>'>
                                    <i class="fas fa-thumbs-up"></i></a>
                                <?= $likes; ?>
                            </div>
                        <?php }
                        if ($userdisliked == 0) {
                            ?>
                            <div class="col-md-2">
                                <a style="color: red"
                                   href='?page=like&dislike=<?php echo $u->getUserID() ?>'>
                                    <i class="far fa-thumbs-down"></i></a>
                                <?= $dislikes; ?>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-2">
                                <a style="color: red"
                                   href='?page=like&remdislike=<?php echo $u->getUserID() ?>'>
                                    <i class="fas fa-thumbs-down"></i></a>
                                <?= $dislikes; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
            echo "<br>";
        }


        ?>

    </div>
<?php
if (isset($_GET["liked"])) {
    $userdisliked = $db->user_liked($user->getUserID(), false, $_GET["liked"]);

    if ($userdisliked == true) {
        $db->removeLike(false, $user->getUserID(), $_GET["liked"]);
    }
    $db->addLike(true, $user->getUserID(), $_GET["liked"]);

    echo "<script>window.location.href='index.php?page=like';</script>";
}

if (isset($_GET["dislike"])) {
    $userliked = $db->user_liked($user->getUserID(), true, $_GET["dislike"]);
    if ($userliked == true) {
        $db->removeLike(true, $user->getUserID(), $_GET["dislike"]);
    }
    $db->addLike(false, $user->getUserID(), $_GET["dislike"]);
    echo "<script>window.location.href='index.php?page=like';</script>";
}

if (isset($_GET["remlike"])) {
    $db->removeLike(true, $user->getUserID(), $_GET["remlike"]);
    echo "<script>window.location.href='index.php?page=like';</script>";
}

if (isset($_GET["remdislike"])) {
    $db->removeLike(false, $user->getUserID(), $_GET["remdislike"]);
    echo "<script>window.location.href='index.php?page=like';</script>";
}
if (isset($_GET["deletecomment"])) {
    $db->deleteComment($_GET["deletecomment"]);
    echo "<script>window.location.href='index.php?page=like';</script>";
}

if (isset($_POST["editcommentsubmit"])) {
    $newcom = $_POST["newcomment"];
    $comid = $_POST["commentid"];
    $db->editComment($comid,$newcom);
    echo "<script>window.location.href='index.php?page=like';</script>";
}
if (isset($_POST["canceledit"])) {
    echo "<script>window.location.href='index.php?page=like';</script>";
}
if (isset($_POST["submit"])) {
    $fileID = $_POST["userid"];
    $comment = $_POST["comment"];
    echo $fileID;
    echo $comment;
    $db->addComment($comment, $user->getUserID(), $fileID);
    echo "<script>window.location.href='index.php?page=like';</script>";
}

