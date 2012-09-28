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
if(!isset($_POST['tracking_goal_id']) OR !is_numeric($_POST['tracking_goal_id']) OR ($_POST['tracking_goal_id'] < 0)) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	//echo "tracking_goal_id nicht richt uebergeben";
} else {
	$tid = $_POST['tracking_goal_id'];
	$tracking_goal_id = $tid;
}
if(!isset($_POST['section_id']) OR !is_numeric($_POST['section_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	//echo "section_id nicht richt uebergeben";
} else {
	$sid = $_POST['section_id'];
	$section_id = $sid;
}

// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_wpaudioplayer_tracking SET tracking_goal_id = '$tracking_goal_id' WHERE section_id = '$section_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/wpaudioplayer/modify_mp3.php?page_id='.$page_id.'&section_id='.$section_id.'&mp3_id='.$mp3_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>