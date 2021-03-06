<?php
require_once('./page_stuff/page_session.php');
require_once("./page_fnc/fnc_gallery.php");
require_once('./page_fnc/fnc_general.php');
require_once("../../config.php");

$page = 1;
$page_limit = 5;
$photo_count = count_public_photos(2);
//hoolitseme, et saaks liikuda vaid legaalsetele lehekülgedele, mis on olemas
if(!isset($_GET["page"]) or $_GET["page"] < 1){
    $page = 1;
} elseif(round($_GET["page"] - 1) * $page_limit >= $photo_count) {
    $page = ceil($photo_count / $page_limit);
} else {
    $page = $_GET["page"];
}

$to_head = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' ."\n";

require("./page_stuff/page_header.php");
?>
<div><p><h2>Minu letud fotod</h2>Kliki pildile, et muuta pilid andmeid</p></div><br />
    <p>
        <?php
            if($page > 1){
                echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span> |' ."\n";
            } else {
                echo "<span>Eelmine leht</span> | \n";
            }
            if($page * $page_limit < $photo_count){
                echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>' ."\n";
            } else {
                echo "<span>Järgmine leht</span> \n";
            }
        ?>
    </p>
<?php echo read_own_photo_thumbs($page_limit, $page);?>
<?php require_once('./page_stuff/page_footer.php'); ?>