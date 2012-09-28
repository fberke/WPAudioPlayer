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

$friendly = array('&lt;', '&gt;', '?php');
$raw = array('<', '>', '');
?>


<?php
$query_page_content = $database->query("SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$fetch_page_content = $query_page_content->fetchRow();

?>

	<h2>View and Edit mp3 Audio Files</h2>

<?php
// include the button to edit the optional module CSS files (function added with WB 2.7)
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Remember to replace the string helloworld below with the module directory of your module
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css')) {
	edit_module_css('wpaudioplayer');
}
?>

<form name="edit" action="<?php echo WB_URL; ?>/modules/wpaudioplayer/add_mp3.php" method="post">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />

<?php
$query_wpaudioplayer = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_wpaudioplayer WHERE section_id = '".$section_id."'" );
if($query_wpaudioplayer -> numRows() > 0) {
	$mp3_num = $query_wpaudioplayer -> numRows();
	
	?>

	<table>
	<thead>
		<tr>
			<th>MP3 Audio Title</th>
			<th>Visible</th>
			<th>Autoplay</th>
			<th>Download</th>
			<th>Modify</th>
			<th>Remove</th>
		</tr>
	</thead>
	<tbody>
		<?php
	while($mp3 = $query_wpaudioplayer -> fetchRow()) {
		?>
		<tr>

			<td>
				<a href="<?php echo WB_URL; ?>/modules/wpaudioplayer/modify_mp3.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&mp3_id=<?php echo $mp3['mp3_id']; ?>" title="Edit">
				<?php echo (($mp3['mp3_title'] != "") ? stripslashes($mp3['mp3_title']) : stripslashes($mp3['mp3_file'])); ?>
				</a>
			</td>            
			<td>
				<?php 
					$icon = ($mp3['mp3_visible'] == '1') ? 'ok' : 'minus';
					echo '<img src="'.THEME_URL.'/images/'.$icon.'_16.png" alt="'.$icon.'" />';
				?>
			</td>
			<td>
				<?php 
					$icon = ($mp3['mp3_autoplay'] == '1') ? 'ok' : 'minus';
					echo '<img src="'.THEME_URL.'/images/'.$icon.'_16.png" alt="'.$icon.'" />';
				?>
			</td>
			<td>
				<?php 
					$icon = ($mp3['mp3_download'] == '1') ? 'ok' : 'minus';
					echo '<img src="'.THEME_URL.'/images/'.$icon.'_16.png" alt="'.$icon.'" />';
				?>
			</td>
			<td>
				<a href="<?php echo WB_URL; ?>/modules/wpaudioplayer/modify_mp3.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&mp3_id=<?php echo $mp3['mp3_id']; ?>" title="Edit">
					<img src="<?php echo THEME_URL; ?>/images/modify_16.png" alt="Modify" />
				</a>
			</td>
			<td>
				<a href="<?php echo WB_URL; ?>/modules/wpaudioplayer/delete_mp3.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&mp3_id=<?php echo $mp3['mp3_id']; ?>&delete=0" title="Remove">
					<img src="<?php echo THEME_URL; ?>/images/delete_16.png" alt="Delete" />
				</a>
			</td>
            
		</tr>
		<?php
	}
	?>
	</tbody>
	</table>
	<?php
} else {
	echo "<p>No Audio Found</p>";
}
?>
	<div class="buttonrow">
	<button name="save" type="submit">Add MP3 Audio File</button>
	<button name="cancel" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/index.php'; return false;">
	<?php echo $TEXT['CANCEL']; ?>
	</button>
	</div>
</form>

<?php
	if($query_wpaudioplayer -> numRows() > 0) {

	$query_wpaudioplayer_tracking = $database->query("SELECT tracking_goal_id FROM ".TABLE_PREFIX."mod_wpaudioplayer_tracking WHERE section_id = '".$section_id."'" );
	$tracking =  $query_wpaudioplayer_tracking -> fetchRow();
?>

<form name="tracking" action="<?php echo WB_URL; ?>/modules/wpaudioplayer/save_tracking.php" method="post">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />


	<label for="tracking_goal_id">Enter optional Piwik goal ID for tracking player events:</label>
	<input type="text" name="tracking_goal_id" value="<?php echo $tracking['tracking_goal_id']; ?>" maxlength="10" />
	
	<div class="buttonrow">
	<button type="submit">Change Tracking Goal ID</button>
	<button type="button" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/index.php'; return false;">
	<?php echo $TEXT['CANCEL']; ?>
	</button>
	</div>
</form>

<?php
	} //if($query_wpaudioplayer -> numRows() > 0) {
?>