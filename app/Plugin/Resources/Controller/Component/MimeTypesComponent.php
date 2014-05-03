<?php
class MimeTypesComponent extends Component {
	
	function get_complete($ext) {
		foreach($this->map as $mime) { if ($mime['extension'] == $ext) { return $mime['type'].'/'.$mime['subtype']; break; }}
		return '';
	}
	
	function get_type($ext) {
		foreach($this->map as $mime) { if ($mime['extension'] == $ext) { return $mime['type']; break; }}
		return '';
	}
	
	function get_subtype($ext) {
		foreach($this->map as $mime) { if ($mime['extension'] == $ext) { return $mime['subtype']; break; }}
		return '';
	}
	
	function get_mimeTypesArray() {
		$out = array();
		
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
		array('extension' => 'svg', 'type' => 'image', 'subtype' => 'svg'),
		array('extension' => 'tif', 'type' => 'image', 'subtype' => 'tiff'),
		array('extension' => 'tiff', 'type' => 'image', 'subtype' => 'tiff'),
		array('extension' => 'odg', 'type' => 'application', 'subtype' => 'vnd.oasis.opendocument.graphics'),
		// Video
		array('extension' => 'm1v', 'type' => 'video', 'subtype' => 'mpeg'),
		array('extension' => 'mpg', 'type' => 'video', 'subtype' => 'mpeg'),
		array('extension' => 'm2v', 'type' => 'video', 'subtype' => 'mpeg'),
		array('extension' => 'mp4', 'type' => 'video', 'subtype' => 'mp4'),
		array('extension' => 'flv', 'type' => 'video', 'subtype' => 'x-flv'),
		array('extension' => 'ogv', 'type' => 'video', 'subtype' => 'ogg'),
		array('extension' => 'mpeg', 'type' => 'video', 'subtype' => 'mpeg'),
		array('extension' => 'mov', 'type' => 'video', 'subtype' => 'quicktime'),
		array('extension' => 'mkv', 'type' => 'video', 'subtype' => 'x-matroska'),
		// Sound
		array('extension' => 'mp3', 'type' => 'audio', 'subtype' => 'mpeg'),
		// Docs
		array('extension' => 'epub', 'type' => 'application', 'subtype' => 'epub+zip'),
		array('extension' => 'pdf', 'type' => 'application', 'subtype' => 'pdf'),
		array('extension' => 'doc', 'type' => 'application', 'subtype' => 'msword'),
		array('extension' => 'dot', 'type' => 'application', 'subtype' => 'msword'),
		array('extension' => 'docx', 'type' => 'application', 'subtype' => 'vnd.openxmlformats-officedocument.wordprocessingml.document'),
		array('extension' => 'dotx', 'type' => 'application', 'subtype' => 'vnd.openxmlformats-officedocument.wordprocessingml.template'),
		array('extension' => 'docm', 'type' => 'application', 'subtype' => 'vnd.ms-word.document.macroEnabled.12'),
		array('extension' => 'dotm', 'type' => 'application', 'subtype' => 'vnd.ms-word.template.macroEnabled.12'),
		array('extension' => 'xls', 'type' => 'application', 'subtype' => 'vnd.ms-excel'),
		array('extension' => 'xlt', 'type' => 'application', 'subtype' => 'vnd.ms-excel'),
		array('extension' => 'xla', 'type' => 'application', 'subtype' => 'vnd.ms-excel'),
		array('extension' => 'xlsx', 'type' => 'application', 'subtype' => 'vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
		array('extension' => 'xltx', 'type' => 'application', 'subtype' => 'vnd.openxmlformats-officedocument.spreadsheetml.template'),
		array('extension' => 'xlsm', 'type' => 'application', 'subtype' => 'vnd.ms-excel.sheet.macroEnabled.12'),
		array('extension' => 'xltm', 'type' => 'application', 'subtype' => 'vnd.ms-excel.template.macroEnabled.12'),
		array('extension' => 'xlam', 'type' => 'application', 'subtype' => 'vnd.ms-excel.addin.macroEnabled.12'),
		array('extension' => 'xlsb', 'type' => 'application', 'subtype' => 'vnd.ms-excel.sheet.binary.macroEnabled.12'),
		array('extension' => 'ppt', 'type' => 'application', 'subtype' => 'vnd.ms-powerpoint'),
		array('extension' => 'pot', 'type' => 'application', 'subtype' => 'vnd.ms-powerpoint'),
		array('extension' => 'pps', 'type' => 'application', 'subtype' => 'vnd.ms-powerpoint'),
		array('extension' => 'ppa', 'type' => 'application', 'subtype' => 'vnd.ms-powerpoint'),
		array('extension' => 'pptx', 'type' => 'application', 'subtype' => 'vnd.openxmlformats-officedocument.presentationml.presentation'),
		array('extension' => 'potx', 'type' => 'application', 'subtype' => 'vnd.openxmlformats-officedocument.presentationml.template'),
		array('extension' => 'ppsx', 'type' => 'application', 'subtype' => 'vnd.openxmlformats-officedocument.presentationml.slideshow'),
		array('extension' => 'ppam', 'type' => 'application', 'subtype' => 'vnd.ms-powerpoint.addin.macroEnabled.12'),
		array('extension' => 'pptm', 'type' => 'application', 'subtype' => 'vnd.ms-powerpoint.presentation.macroEnabled.12'),
		array('extension' => 'potm', 'type' => 'application', 'subtype' => 'vnd.ms-powerpoint.template.macroEnabled.12'),
		array('extension' => 'ppsm', 'type' => 'application', 'subtype' => 'vnd.ms-powerpoint.slideshow.macroEnabled.12'),
		array('extension' => 'odt', 'type' => 'application', 'subtype' => 'vnd.oasis.opendocument.text'),
		array('extension' => 'ods', 'type' => 'application', 'subtype' => 'vnd.oasis.opendocument.spreadsheet'),
		array('extension' => 'odp', 'type' => 'application', 'subtype' => 'vnd.oasis.opendocument.presentation'),
		array('extension' => 'odi', 'type' => 'application', 'subtype' => 'vnd.oasis.opendocument.image'),
		array('extension' => 'rtf', 'type' => 'application', 'subtype' => 'rtf'),
		// Scorms
		array('extension' => 'scorm', 'type' => 'application', 'subtype' => 'scorm'),
		array('extension' => 'zip', 'type' => 'application', 'subtype' => 'zip')
	);
	
}
?>