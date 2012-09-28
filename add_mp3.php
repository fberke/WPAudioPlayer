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

// Insert new row into database
$database->query("INSERT INTO ".TABLE_PREFIX."mod_wpaudioplayer (section_id,page_id,mp3_visible,mp3_download) VALUES ('$section_id','$page_id','1','1')");

// Get the id
$mp3_id = $database->get_one("SELECT LAST_INSERT_ID()");

// Say that a new record has been added, then redirect to modify page
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/wpaudioplayer/modify_mp3.php?page_id='.$page_id.'&section_id='.$section_id.'&mp3_id='.$mp3_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/wpaudioplayer/modify_mp3.php?page_id='.$page_id.'&section_id='.$section_id.'&mp3_id='.$mp3_id);
}

// Print admin footer
$admin->print_footer();

?>