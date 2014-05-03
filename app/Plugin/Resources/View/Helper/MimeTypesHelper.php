<?php
class MimeTypesHelper extends AppHelper {
	
	function get_complete($ext) {
		$out = '';
		foreach($this->map as $mime) { if ($mime['extension'] == $ext) { $out = $mime['type'].'/'.$mime['subtype']; break; }}
		return $this->output($out);
	}
	
	function get_type($ext) {
		$out = '';
		foreach($this->map as $mime) { if ($mime['extension'] == $ext) { $out = $mime['type']; break; }}
		return $this->output($out);
	}
	
	function get_subtype($ext) {
		$out = '';
		foreach($this->map as $mime) { if ($mime['extension'] == $ext) { $out = $mime['subtype']; break; }}
		return $this->output($out);
	}
	
	// Using http://www.feedforall.com/mime-types.htm
	var $map = array(
		// Images
		array('extension' => 'jpe', 'type' => 'image', 'subtype' => 'jpeg'),
		array('extension' => 'jpeg', 'type' => 'image', 'subtype' => 'jpeg'),
		array('extension' => 'jpg', 'type' => 'image', 'subtype' => 'jpeg'),
		array('extension' => 'png', 'type' => 'image', 'subtype' => 'png'),
		array('extension' => 'gif', 'type' => 'image', 'subtype' => 'gif'),
		array('extension' => 'bmp', 'type' => 'image', 'subtype' => 'bmp'),
		// Video
		array('extension' => 'm1v', 'type' => 'video', 'subtype' => 'mpeg'),
		array('extension' => 'm2v', 'type' => 'video', 'subtype' => 'mpeg'),
		array('extension' => 'mp4', 'type' => 'video', 'subtype' => 'mp4'),
		array('extension' => 'flv', 'type' => 'video', 'subtype' => 'x-flv'),
		array('extension' => 'ogv', 'type' => 'video', 'subtype' => 'ogg'),
		// Sound
		array('extension' => 'mp3', 'type' => 'audio', 'subtype' => 'mpeg'),
		// Docs
		array('extension' => 'epub', 'type' => 'application', 'subtype' => 'epub+zip'),
		array('extension' => 'doc', 'type' => 'application', 'subtype' => 'msword'),
		array('extension' => 'pdf', 'type' => 'application', 'subtype' => 'pdf'),
	);
	
}
?>	