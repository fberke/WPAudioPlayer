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

//---- get mp3list
$query = "SELECT * FROM ".TABLE_PREFIX."mod_wpaudioplayer WHERE section_id = '$section_id' AND mp3_visible= '1'";
$query_content = $database->query($query);

$query = "SELECT tracking_goal_id FROM ".TABLE_PREFIX."mod_wpaudioplayer_tracking WHERE section_id = '$section_id'";
$query_tracking = $database->query($query);
$wpaudioplayer_tracking =  $query_tracking -> fetchRow();
$tracking_goal_id = $wpaudioplayer_tracking['tracking_goal_id'];
$tracking = ($tracking_goal_id > 0);

$i = 0;
$rows = ($query_content->numRows());
$unique_id = "audioplayer_".$section_id;
$tracking_code_id = "tracking_".$section_id;

if($rows > 0) {
	// setting variables
	$mp3_files = "";
	$mp3_titles = "";
	$mp3_artists = "";
	$mp3_descriptions = "";
	
	// opening paragraph for descriptions
	$mp3_descriptions = '<p class="wpaudio_descr">';

	// opening downloads list
	$mp3_downloads = '<ul class="wpaudio_download">';
	
	$html5Skeleton = '<audio controls="controls"><source src="%s" /></audio>';
	$html5Fallback = '';
	/*
	$html5Fallback = '<h4>HTML5 Audio Fallback</h4>';
	$html5Fallback .= '<p>You see this because there is no Flash player available on your system.</p>';
	*/
	while ($audio = $query_content->fetchRow()) {
 		
		$i++;
		
		if ($audio['mp3_visible']=='1') {
			$audiofileFullpath = WB_URL.'/media/wpaudio/'.$audio['mp3_file'];
			
			// Remove path from filename
			$isSubfolder = strrchr ($audio['mp3_file'], '/');
			$audiofileName = ($isSubfolder !== false) ? substr ($isSubfolder, 1) : $audio['mp3_file'];
			
			$mp3_files .= $audiofileFullpath;
			$mp3_titles .= $audio['mp3_title'];
			$mp3_artists .= $audio['mp3_artist'];
			$mp3_descriptions .= $audio['mp3_description'];
			
			$html5Fallback .= sprintf ($html5Skeleton, $audiofileFullpath);
			
			// status of first song in list determines whether or not autoplay is active
			if ($i == 1) { $mp3_autostart = $audio['mp3_autoplay']; }
			if ($audio['mp3_download'] == '1') { 
				$mp3_downloads .= '<li><a href="'.$audiofileFullpath.'">'.$audiofileName.'</a></li>'; 
			}
			
			// if multiple files, create a list with no tailing commas
			if ($i < $rows) {
				$mp3_files .= ', ';
				$mp3_titles .= ', ';
				$mp3_artists .= ', ';
				$mp3_descriptions .= '</p><p class="wpaudio_descr">';
			}
		}
	}
	
	// remove commas if no titles and artists are set, otherwise wpaudioplayer won't show mp3 tags
	if ((str_replace (', ', "", $mp3_titles) == "") AND (str_replace (', ', "", $mp3_artists) == "")) {
		$mp3_titles = "";
		$mp3_artists = "";
	}
	
	// closing paragraph for descriptions
	$mp3_descriptions .= '</p>';
	
	// closing downloads list
	$mp3_downloads .= '</ul>';
	
	// remove empty paragraphs from descriptions
	$mp3_descriptions = str_replace ('<p class="wpaudio_descr"></p>', "", $mp3_descriptions);
	
	// clear download list if empty
	$mp3_downloads = str_replace ('<ul class="wpaudio_download"></ul>', "", $mp3_downloads);

?>

<script type="text/javascript">
<?php
echo 'AudioPlayer.setup("'.WB_URL.'/modules/wpaudioplayer/player.swf", {';
?>
		width: 290
});  
</script>

<div id="<?php echo $tracking_code_id; ?>">

<div id="<?php echo $unique_id; ?>"><?php echo $html5Fallback;?></div>

<script type="text/javascript">  
AudioPlayer.embed("<?php echo $unique_id; ?>", {
	soundFile: "<?php echo $mp3_files; ?>",
	titles: "<?php echo $mp3_titles; ?>",
	artists: "<?php echo $mp3_artists; ?>"
	<?php
	if ($mp3_autostart == '1') { echo ', autostart: "yes"'; }
	?>
		
});  
</script>
</div>

<?php
if ($tracking) {
	echo "<!-- Piwik Tracking Code -->\n";
	echo "<script type='text/javascript'>\n";
	echo "$('#".$tracking_code_id."').mousedown(function(e) {\n";
	echo "//alert(\"Thanks for clicking!\");\n";
	echo "piwikTracker.trackGoal(".$tracking_goal_id.");\n";
	echo "});\n";
	echo "</script>\n";
}
?>

<?php
	echo $mp3_descriptions;
	echo $mp3_downloads;
} 

?>