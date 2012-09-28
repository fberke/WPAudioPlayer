<?php

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

// add new rows to the module's tables 
$database->query("INSERT INTO `" .TABLE_PREFIX ."mod_wpaudioplayer` (`page_id`, `section_id`) VALUES ('$page_id', '$section_id')");
// tracking starts with a zero value, which means it is turned off by default
$database->query("INSERT INTO `" .TABLE_PREFIX ."mod_wpaudioplayer_tracking` (`section_id`, `tracking_goal_id`) VALUES ('$section_id', '0')");

?>