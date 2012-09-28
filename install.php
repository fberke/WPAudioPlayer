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

// delete existing module DB-table (start with a clean database)
$database->query("DROP TABLE IF EXISTS `" .TABLE_PREFIX ."mod_wpaudioplayer`");
$database->query("DROP TABLE IF EXISTS `" .TABLE_PREFIX ."mod_wpaudioplayer_tracking`");

// create a new, clean module DB-table (you need to change the fields added according your needs!!!)
$mod_create_table = 'CREATE TABLE `' .TABLE_PREFIX .'mod_wpaudioplayer` ( '
	. '`mp3_id` INT NOT NULL AUTO_INCREMENT,'
	. '`section_id` INT NOT NULL,'
	. '`page_id` INT NOT NULL,'
	. '`mp3_file` VARCHAR(125) NOT NULL,'
	. '`mp3_title` VARCHAR(255) NOT NULL,'
	. '`mp3_artist` VARCHAR(75) NOT NULL,'
	. '`mp3_description` TEXT NOT NULL,'
	. '`mp3_visible` BOOL NOT NULL DEFAULT 1,'
	. '`mp3_autoplay` BOOL NOT NULL DEFAULT 0,'
	. '`mp3_download` BOOL NOT NULL DEFAULT 1,'
	. 'PRIMARY KEY (mp3_id)'
	. ' )';
$database->query($mod_create_table);

$mod_create_table = 'CREATE TABLE `' .TABLE_PREFIX .'mod_wpaudioplayer_tracking` ( '
	. '`tracking_id` INT NOT NULL AUTO_INCREMENT,'
	. '`section_id` INT NOT NULL,'
	. '`tracking_goal_id` SMALLINT NOT NULL,'
	. 'PRIMARY KEY (tracking_id)'
	. ' )';
$database->query($mod_create_table);

/**
* ADD THE CODE BELOW TO YOUR install.php FILE IF YOU WANT THAT USERS CAN SEARCH INFORMATION STORED
* IN YOUR MODUL DB-TABLES. IF YOUR MODULE DB-TABLES DO NOT CONTAIN ANY INFORMATION YOU WANT BE FOUND
* BY THE WB SEARCH FUNCTION, SIMPLY DELETE THE LINES BELOW. 
* NOTE: DO NOT DELETE THE VERY LAST LINE CONTAINING THE CLOSING PHP TAG ?>
*/
# ADD 1st MODULE SEARCH ROW TO THE DATABASE
$search_info = array(
	'page_id'	=>	'page_id',
	'title'		=>	'page_title',
	'link'		=>	'link',
	'description'	=>	'description',
	'modified_when'	=>	'modified_when',
	'modfified_by'	=>	'modified_by'
	);

$search_info = serialize($search_info);
$database->query("INSERT INTO `" .TABLE_PREFIX ."search` (`name`,`value`,`extra`) 
	VALUES ('module', 'wpaudioplayer', '$search_info')");

# ADD 2nd MODULE SEARCH ROW TO THE DATABASE
$search_info = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, 
	[TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_wpaudioplayer, [TP]pages WHERE ";
$database->query("INSERT INTO `" .TABLE_PREFIX ."search` (`name`,`value`,`extra`) 
	VALUES ('query_start', '$search_info', 'wpaudioplayer')");

# ADD 3rd MODULE SEARCH ROW TO THE DATABASE
$search_info = " [TP]pages.page_id = [TP]mod_wpaudioplayer.page_id AND [TP]mod_wpaudioplayer.simple_output [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";	
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`,`value`,`extra`) 
	VALUES ('query_body', '$search_info', 'wpaudioplayer')");

# ADD 4th MODULE SEARCH ROW TO THE DATABASE
$search_info = "";
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`,`value`,`extra`) 
	VALUES ('query_end', '$search_info', 'wpaudioplayer')");
	
// insert blank row to the module table (there needs to be at least on row for the search to work)
$database->query("INSERT INTO `".TABLE_PREFIX."mod_wpaudioplayer` (`page_id`,`section_id`) VALUES ('0','0')");

?>