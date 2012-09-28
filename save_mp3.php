<?php

require('../../config.php');

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php


// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Include WB functions file
require(WB_PATH.'/framework/functions.php');


// Get id
if(!isset($_POST['mp3_id']) OR !is_numeric($_POST['mp3_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$id = $_POST['mp3_id'];
	$mp3_id = $id;
}

$mp3_file = addslashes($admin->get_post('mp3_file'));
$mp3_title = addslashes($admin->get_post('mp3_title'));
$mp3_artist = addslashes($admin->get_post('mp3_artist'));
$mp3_description = addslashes($admin->get_post('mp3_description'));
$mp3_visible = (addslashes($admin->get_post('mp3_visible')) == 'visible') ? 1 :0;
$mp3_autoplay = (addslashes($admin->get_post('mp3_autoplay')) == 'autoplay') ? 1 :0;
$mp3_download = (addslashes($admin->get_post('mp3_download')) == 'download') ? 1 :0;

//create the audio directory
make_dir(WB_PATH.MEDIA_DIRECTORY.'/wpaudio');

// upload mp3 file
if ($_FILES["mp3_upload"]["name"] != "") {
	if ($_FILES["mp3_upload"]["error"] > 0) {
		echo "Return Code: " . $_FILES["mp3_upload"]["error"] . "<br />";
	} else {
		echo "MP3 File Name: " .$_FILES["mp3_upload"]["name"] . "<br />";
		echo "File Type: " . $_FILES["mp3_upload"]["type"] . "<br />";
		echo "File Size: " . ($_FILES["mp3_upload"]["size"] / 1024) . " Kb<br />";
		$_FILES["mp3_upload"]["tmp_name"] . "<br />";
		if (file_exists("upload/" . $_FILES["mp3_upload"]["name"])) {
			echo $_FILES["mp3_upload"]["name"] . " already exists. ";
		} else {
			move_uploaded_file($_FILES["mp3_upload"]["tmp_name"],
				WB_PATH.MEDIA_DIRECTORY."/wpaudio/". $_FILES["mp3_upload"]["name"]);
			$mp3_file = $_FILES["mp3_upload"]["name"];
		}
	}
}

if (trim($mp3_description) == "") {
	$mp3_description = "";
}

// Get page link URL
$query_page = $database->query("SELECT level,link FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$page = $query_page->fetchRow();
$page_level = $page['level'];
$page_link = $page['link'];


// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_wpaudioplayer SET mp3_file = '$mp3_file', mp3_title = '$mp3_title', mp3_artist = '$mp3_artist', mp3_description = '$mp3_description', mp3_visible = '$mp3_visible', mp3_autoplay = '$mp3_autoplay', mp3_download = '$mp3_download' WHERE mp3_id = '$mp3_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/wpaudioplayer/modify_mp3.php?page_id='.$page_id.'&section_id='.$section_id.'&mp3_id='.$mp3_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>