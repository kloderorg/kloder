<?php
class RemoteThumbComponent extends Component {
	
	var $options = array(
		'width' => 100,			// Width of desired image
		'height' => 100,		// Height of desired image
		'zoom_crop' => 0,		// Will auto-crop off the larger dimension
       							// so that the image will fill the smaller dimension
								// (requires both "w" and "h", overrides "iar", "far")
								// Set to "1" or "C" to zoom-crop towards the center,
								// or set to "T", "B", "L", "R", "TL", "TR", "BL", "BR"
								// to gravitate towards top/left/bottom/right directions
								// (requies ImageMagick for values other than "C" or "1")
		'source' => '',			// Sorce URL of image
		'error' => '',			// URL of error image
		'cache_dir' => '',		// Dir for cached imaged
		'cache_url' => ''		// Dir for cached imaged
	);
	var $extension = '';
	
	function generate($options = array()) {
		$this->options = array_merge($this->options, $options);
		$this->get_extension();
		
		if (!$this->check_cache()) {
			if ($this->options['source'] != '') {
				App::import('Vendor', 'phpThumb', array('file' => 'phpThumb'.DS.'phpthumb.class.php'));
				
				$phpThumb = new phpThumb();
				
		        $phpThumb->setSourceFilename($this->options['source']);
		        $phpThumb->setParameter('w', $this->options['width']);
		        $phpThumb->setParameter('h', $this->options['height']);
		        $phpThumb->setParameter('zc', $this->options['zoom_crop']);
				
				if($phpThumb->generateThumbnail()) {
					if($phpThumb->RenderToFile($this->get_img_path())) {
						return $this->get_img_url();
					} else {
						return $this->get_error_url();
					}
				} else {
					return $this->get_error_url();
				}
			}
		} else {
			return $this->get_img_url();
		}
	}
	
	function get_extension() {
		$urlparts = parse_url($this->options['source']);
		$fileparts = pathinfo($urlparts['path']);
		$this->extension = $fileparts['extension'];
	}
	
	function check_cache() {
		if (!file_exists($this->options['cache_dir'].DS.$this->get_cached_name())) return true;
		return false;
	}

	function get_error_url() {
		App::import('Helper', 'Html');
		$html = new HtmlHelper();
		return $this->Html->url($this->options['error'], true);
	}
	
	function get_img_url() {
		App::import('Helper', 'Html');
		$html = new HtmlHelper();
		return $this->Html->url($this->options['cache_url'].'/'.$this->get_cached_name(), true);
	}
	
	function get_img_path() {
		return $this->options['cache_dir'].DS.$this->get_cached_name();
	}
	
	function get_cached_name() {
		return md5($this->options['source'].$this->options['width'].'x'.$this->options['height']).'.'.$this->extension;
	}
}
?>