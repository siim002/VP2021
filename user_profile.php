<?php
    //alustame sessiooni
	require_once('./page_stuff/page_session.php');
    require_once("../../config.php");
	require_once("./page_fnc/fnc_user.php");
    require_once("./page_fnc/fnc_general.php");
    
    $notice = null;
    $description = read_user_description();
	
	if(isset($_POST["profile_submit"])){
		$description = test_input($_POST["description_input"]);
		$notice = store_user_profile($description, $_POST["bg_color_input"],$_POST["text_color_input"]);
		$_SESSION["bg_color"] = $_POST["bg_color_input"];
		$_SESSION["text_color"] = $_POST["text_color_input"];
	}
	
    
    require("./page_stuff/page_header.php");
?>
    <h2>Kasutajaprofiil</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="description_input">Minu lühikirjeldus: </label>
        <div style="padding-top:6px"></div>
        <textarea name="description_input" id="description_input" rows="10" cols="80" placeholder="Minu lühikirjeldus ..."><?php echo $description; ?></textarea>

        <div style="padding-top:10px"></div>
        <label for="bg_color_input">tausta värv: </label>
        <input type="color" name="bg_color_input" id="bg_color_input" value="<?php echo $_SESSION["bg_color"]; ?>">

        <div style="padding-top:6px"></div>
        <label for="text_color_input">teksti värv: </label>
        <input type="color" name="text_color_input" id="text_color_input" value="<?php echo $_SESSION["text_color"]; ?>">

        <div style="padding-top:6px; padding-left:78px;">
            <input type="submit" name="profile_submit" value="Salvesta">
            <span style=padding-left:4px;"><?php echo $notice; ?></span>
        </div>
    </form>
    
<?php require_once('./page_stuff/page_footer.php'); ?>