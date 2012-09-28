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

//require('../../config.php');
//include('info.php');

// delete all database search table entries made by this module
$database->query("DELETE FROM `" .TABLE_PREFIX ."search` WHERE `name` = 'module' AND `value` = 'wpaudioplayer'");
$database->query("DELETE FROM `" .TABLE_PREFIX ."search` WHERE `extra` = 'wpaudioplayer'");

// delete the module database table
$database->query("DROP TABLE ".TABLE_PREFIX."mod_wpaudioplayer");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_wpaudioplayer_tracking");

?>