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

$module_directory = 'wpaudioplayer';
$module_name = 'WP Audio Player';
$module_function = 'page';
$module_version = '0.4.0';
$module_platform = '1.1.0';
$module_author = 'Jason Carncross, Frank Berke';
$module_license = 'GNU General Public License, Open Source MIT license';
$module_description = 'This module provides upload and play functionality for MP3 files. It is based on the audioplayer module by Jason Carncross, but has been adapted to the WPAudioPlayer flash MP3 player.';
$module_home = 'http://';
$module_guid = '32a333cb-2853-43d7-bc61-f15b077b854b';

?>