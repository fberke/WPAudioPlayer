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


// Get ID
if (isset($_GET['mp3_id']) AND is_numeric($_GET['mp3_id'])) {
	$mp3_id = $_GET['mp3_id'];
} else {
	header("Location: ".ADMIN_URL."/pages/index.php");
}


// Unlink post access file
// Uncommented bedause I can't figure out what it does
/*
if (is_writable(WB_PATH.$get_details['mp3_file'].'.php')) {
	unlink(WB_PATH.$get_details['mp3_file'].'.php');
}
*/

// Delete actual file
// If delete == 0 only db entry will be removed
if ($_GET['delete'] == "1") {
	// Get MP3 details
	$query_details = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_wpaudioplayer WHERE mp3_id = '$mp3_id'");
	if ($query_details->numRows() > 0) {
		$get_details = $query_details->fetchRow();
	} else {
		$admin->print_error($TEXT['NOT_FOUND'].' MP3-ID: '.$mp3_id, ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}

	
	$file = WB_PATH.MEDIA_DIRECTORY.'/wpaudio/'.$get_details['mp3_file'];
	if (file_exists ($file) AND is_writable ($file)) {
		unlink($file);
	}
}

// Remove entry from DB
$database->query("DELETE FROM ".TABLE_PREFIX."mod_wpaudioplayer WHERE mp3_id = '$mp3_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
}

// Print admin footer
$admin->print_footer();

?>