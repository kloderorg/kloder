<?php
class IcarusHelper extends AppHelper {

	var $helpers = array('Html', 'Text', 'Users.Authake');
	var $count = 0;

	function show($options) {
		if (array_key_exists('embed', $options)) $embed = $options['embed']; else $embed = false;
		if (array_key_exists('debug', $options)) $debug = $options['debug']; else $debug = false;
		if (array_key_exists('filters', $options)) $filters = $options['filters']; else $filters = array();
		if (array_key_exists('proyect_slug', $options)) $proyect_slug = $options['proyect_slug']; else $proyect_slug = '';

		// just once
		if ($this->count++ == 0) {

			// Other plugins
			$plugins = $this->__resources_plugins();
			// Adding styles
			$css_path = array(
				'/resources/css/icarus/icarus',
				'breadcrumb/BreadCrumb',
				'/resources/css/icarus/plupload/jquery.ui.plupload',
				'/resources/css/icarus/fg.menu',
				'/resources/css/icarus/jquery.dragToSelect',
				'/resources/css/icarus/jquery.contextMenu'
			);
			// Adding from other plugins
			foreach ($plugins as $plugin) {
				array_push($css_path, '/'.$plugin.'/css/icarus/icarus');
			}
			$this->Html->css($css_path, null, array('inline' => false));

			// Adding scripts
			$scripts_paths = array(
				'ratings/jquery.raty.min',
				'jquery.jBreadCrumb.1.1',
				'/resources/js/icarus/jquery.dragToSelect',
				'/resources/js/icarus/i18n/'.Configure::read('Config.language'),
				'/resources/js/icarus/icarus',
				'/resources/js/jquery.lazyload.min',
				'/resources/js/icarus/jquery.contextmenu.r2.packed',
				'/resources/js/icarus/jquery.contextMenu',
				'/resources/js/icarus/plupload/plupload.full',
				'/resources/js/icarus/plupload/jquery.ui.plupload/jquery.ui.plupload',
				'/resources/js/icarus/plupload/i18n/'.Configure::read('Config.language'),
				'/resources/js/icarus/fg.menu',
				'http://bp.yahooapis.com/2.4.21/browserplus-min.js'
			);
			// Adding from other plugins
			foreach ($plugins as $plugin) {
				array_push($scripts_paths, '/'.$plugin.'/js/icarus/i18n/'.Configure::read('Config.language'));
				array_push($scripts_paths, '/'.$plugin.'/js/icarus/icarus');
			}
			$this->Html->script($scripts_paths, array('inline' => false));

			// Adding parameters
			$scripts = '
				//user_id = '.$this->Authake->getUserId().';
				user_id = 1;
				base_url = "'.$this->Html->url('/').'";
				icarus_embed = '.(($embed) ? 'true' : 'false').';
				icarus_debug = '.(($debug) ? 'true' : 'false').';
				icarus_proyect_slug = "'.$proyect_slug.'";
				icarus_upload_max_size = '.$this->return_bytes(ini_get('post_max_size')).';
				icarusUploadExtensions = new Array();
				icarus_files_uploaded = false;
				icarusActiveFilters = new Array();
			';

			// Icarus Types
			$scripts .= 'resourcesTypes = [';
			foreach (Configure::read('Resources.types') as $value) $scripts .= '"'.$value.'",';
			$scripts = substr($scripts, 0, -1); $scripts .= '];';

			// Icarus Active Filters
			$scripts .= 'icarusActiveFilters = [';

			if (!empty($filters)){
				foreach ( $filters as $filter ) $scripts .= '"'.$filter.'",';
				$scripts = substr($scripts, 0, -1);
			}
			$scripts .= '];';

			// Icarus Filters
			$scripts .= 'icarusFilters = [';

			foreach (Configure::read('Resources.icarusFilter') as $key => $value){
				in_array($key, $filters) ? $active = "true" : $active = "false";
				$scripts .= '["'.$key.'",';
				foreach ($value as $key2 => $value2){
					if (!is_array($value2))	$scripts .= '["'.$key2.'","'.$value2.'"], ';
					else {
						$scripts .= '["'.$key2.'",[';
						foreach ($value2 as $value3){
							$scripts .= '"'.$value3.'",';
						}
						$scripts = substr($scripts, 0, -1);
						$scripts .= ']],';
					}
				}
				$scripts .= '["active","'.$active.'"]';
				$scripts .= '],';
			}
			$scripts = substr($scripts, 0, -1); $scripts .= '];';

			//die(debug($scripts));


			// Icarus Menu
			$scripts .= 'icarusMenu = [';
			foreach (Configure::read('Resources.menu') as $key => $value) $scripts .= '["'.$key.'","'.$this->Html->url('/').$value.'"],';
			$scripts = substr($scripts, 0, -1); $scripts .= '];';

			// Icarus Files Types
			$scripts .= 'resourcesExtensionsFileType = [';
			foreach (Configure::read('Resources.fileTypes') as $key => $value) $scripts .= '["'.$key.'","'.$value.'"],';
			$scripts = substr($scripts, 0, -1); $scripts .= '];';

			// Icarus Files Types
			$scripts .= 'resourcesExtensionsLinkType = [';
			foreach (Configure::read('Resources.linkTypes') as $key => $value) $scripts .= '["'.$key.'","'.$value.'"],';
			$scripts = substr($scripts, 0, -1); $scripts .= '];';

			// Icarus Upload Types
			$scripts .= 'icarusUploadExtensions = [';
			foreach (Configure::read('Resources.uploadFiles') as $key => $value) $scripts .= '["'.$key.'","'.$value.'"],';
			$scripts = substr($scripts, 0, -1); $scripts .= '];';

			//die(debug($scripts));

			$this->Html->scriptBlock($scripts, array('inline' => false));
		}
	}

	function return_bytes($val) {
	    $val = trim($val);
	    $last = strtolower($val[strlen($val)-1]);
	    switch($last) {
	    	case 'g': $val *= 1024;
	        case 'm': $val *= 1024;
	        case 'k': $val *= 1024;
	    }
	    return $val;
	}

	/* > Retrives all resources plugins
	 * *********************************************************************************** */
	private function __resources_plugins() {
		$plugins = App::objects('plugin');
		$resources_plugins = array();
		foreach ($plugins as $plugin) {
			if (preg_match('/^Resources/', $plugin) > 0 && $plugin != 'Resources')
				array_push($resources_plugins, $plugin);
		}
		return $resources_plugins;
	}

}
?>
