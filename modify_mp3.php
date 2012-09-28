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

require_once(WB_PATH.'/framework/functions.php');

// Get id
if(!isset($_GET['mp3_id']) OR !is_numeric($_GET['mp3_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$mp3_id = $_GET['mp3_id'];
}

$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_wpaudioplayer WHERE mp3_id = '$mp3_id'");
$fetch_content = $query_content->fetchRow();
?>

<h2>Edit Audio
<?php
if($fetch_content['mp3_file'] != '') {
	echo ": ".stripslashes($fetch_content['mp3_file']);
}
?>
</h2>

<p>Fill in "Title" and "Artist" fields to override ID3-Tags stored in the MP3 file. If these fields are left blank,
the player shows the ID3-Tags, if available.</p>
<p>Note: You cannot mix the behavior of WPAudioPlayer within one playlist
- either leave the fields blank or fill them in for each song!</p>
<p>"Description" is an optional entry.</p>

<form name="modify" action="<?php echo WB_URL; ?>/modules/wpaudioplayer/save_mp3.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="mp3_id" value="<?php echo $mp3_id; ?>">

<fieldset>
<legend>Description</legend>
<label for="mp3_title">
	Title
</label>
<input type="text" name="mp3_title" id="mp3_title" value="<?php echo stripslashes($fetch_content['mp3_title']); ?>" maxlength="255" />
	

<label for="mp3_artist">
	Artist
</label>
<input type="text" name="mp3_artist" id="mp3_artist" value="<?php echo stripslashes($fetch_content['mp3_artist']); ?>" maxlength="255" />
	

<label for="mp3_description">
	Description
</label>
<textarea name="mp3_description" id="mp3_description" rows="3">
<?php echo stripslashes($fetch_content['mp3_description']); ?>
</textarea>
</fieldset>


<fieldset>
<legend>Choose File</legend>
<label for="mp3_upload">
	Upload MP3
</label>
<input type="file" name="mp3_upload" id="mp3_upload" />
	

<label for="mp3_file">
	Select existing MP3
</label>
<select name="mp3_file" id="mp3_file">
<option value="<?php echo $fetch_content['mp3_file']; ?>" selected><?php echo $fetch_content['mp3_file']; ?></option>
<?php

$basedir = WB_PATH.MEDIA_DIRECTORY;
$folder_list = directory_list ($basedir);

foreach ($folder_list as $foldername) {
	$file_list = file_list ($foldername);

	foreach ($file_list as $filename) {
		$filename = str_replace ($basedir."/wpaudio/", '', $filename);
		// determine file extension
		$fileparts = pathinfo ($filename);
		
		if ($fileparts['extension'] == "mp3" and trim($filename) != "") {
			echo "<option value=\"".$filename."\">".$filename."</option>\n";
		}
	}
}
?>
</select>
</fieldset>


<fieldset>
<legend>Options</legend>
<label for="mp3_visible">
	MP3 is visible
</label>
<input type="checkbox" name="mp3_visible" id="mp3_visible" value="visible" <?php if($fetch_content['mp3_visible'] == 1) { echo ' checked="checked"'; } ?> />


	

<label for="mp3_autoplay">
	Autoplay MP3
</label>
<input type="checkbox" name="mp3_autoplay" id="mp3_autoplay" value="autoplay" <?php if($fetch_content['mp3_autoplay'] == 1) { echo ' checked="checked"'; } ?> />



<label for="mp3_download">
	Download allowed
</label>
<input type="checkbox" name="mp3_download" id="mp3_download" value="download" <?php if($fetch_content['mp3_download'] == 1) { echo ' checked="checked"'; } ?> />

</fieldset>

<div class="buttonrow">
<button name="save" type="submit"><?php echo $TEXT['SAVE']; ?></button>
<button name="delete" type="button"
onclick="javascript: confirm_link('Are you sure that you want to delete this audio?',
	'<?php echo WB_URL; ?>/modules/wpaudioplayer/delete_mp3.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&mp3_id=<?php echo $fetch_content["mp3_id"]; ?>&delete=1');">
<?php echo $TEXT['DELETE']; ?>
</button>
<button type="button" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';">
<?php echo $TEXT['CANCEL']; ?>
</button>
</div>

</form>
<?php

// Print admin footer
$admin->print_footer();

?>