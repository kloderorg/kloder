<?php  
class ZipComponent extends Component {
	
	/**
	 * Open a Zip file into a temporally directory
	 * Use $data for give the ResourceFile data
	 */
	function open($data) {
		$data['ResourcesFile']['path'] = str_replace('\\', DS, $data['ResourcesFile']['path']);
		$data['ResourcesFile']['path'] = str_replace('/', DS, $data['ResourcesFile']['path']);
		$path = APP . 'files' . DS . $data['ResourcesFile']['path'] . $data['ResourcesFile']['mask'];
		$tempPath = WWW_ROOT . 'files' . DS . $data['ResourcesFile']['path'] . $data['ResourcesFile']['mask'].DS;
		
		if (!$this->isCached($data)) {
			if (!file_exists($tempPath)) {
				if (!@mkdir($tempPath, 0777, true)) {
					debug('Epub: '.$tempPath.', can\'t be created.');
					return false;
				}
			}
			$zip = new ZipArchive;
			$res = $zip->open($path);
			if ($res === TRUE) {
			    $zip->extractTo($tempPath);
			    $zip->close();
				return true;
			} else {
				debug('Epub: '.$path.', can\'t be open.');
				return false;
			}
		} else {
			return 'cached';
		}
	}
	
	function createFromDir($path = '', $destination = '', $overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		
		//debug($path.'|'.$destination);
		
		$files = $this->readRecursive($path);
		
		$zip = new ZipArchive();
		if($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		
		foreach($files[0] as $folder) {
			$p = substr($folder, strlen($path)+1);
			$zip->addEmptyDir($p);
		}
		
		foreach($files[1] as $file) {
			$f = substr($file, strlen($path)+1);
			$zip->addFile($file, $f);
		}
		
		$zip->close();
		return file_exists($destination);
	}
	
	function readRecursive($path) {
		$dir = new Folder($path);
		$files = $dir->read(true, array(), true);
		foreach($files[0] as $d) {
			$tmp_res = $this->readRecursive($d);
			$files[0] = array_merge($files[0], $tmp_res[0]);
			$files[1] = array_merge($files[1], $tmp_res[1]);
		}
		return $files;
	}
	
	/**
	 * Check if the Zip is already open
	 * Use $data for give the ResourceFile data
	 */
	function isCached($data) {
		$tempPath = WWW_ROOT . 'files' . DS . $data['ResourcesFile']['path'] . $data['ResourcesFile']['mask'].DS;
		return is_dir($tempPath);
	}
	
} 
?>