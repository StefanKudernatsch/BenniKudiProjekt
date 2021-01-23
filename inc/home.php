<?php
$DB = new DB();

if(isset($_SESSION["SessionUserName"])) {
    if($_SESSION["SessionUserName"] == 'admin') {

    } else {

    }
} else {
    $FileList = $DB->getPublicFiles();
}
?>
<?php
if(isset($_SESSION["SessionUserName"])) {
    if($_SESSION["SessionUserName"] == 'admin') {

    } else {
        echo "<a href='#UploadModal' class='btn btn-primary'>Create Post</a>";
    }
} else {
    foreach ($FileList as $file) {
        echo "<div class='container'>";
        echo "<div class='main-login main-center'>";
        echo "<div class='container formtop col-md-12 col-sm-12'>";
        echo "<form method='post'>";
        echo "<div class='form-group'>";
        echo "<div class='input-group justify-content-center'>";
        echo "<div class='d-flex justify-content-center h-100'>";
        echo "<div class='image_outer_container'>";
        echo "<h1>".$file->getFileName()."</h1>";
        if($file->getFileType() == 0) {
            echo "<p>".$file->getFileText()."</p>";
        } else {
            echo "<img src='".$file->getFilePath()."'>";
        }
        echo "</div></div></div></div></form></div></div></div>";
    }
}
?>