<?php
//App::uses('Folder', 'Utility');
//App::uses('File', 'Utility');
//App::uses('GaelFile', 'Utility');
class ResourcesFilesController extends ResourcesAppController {

	public $name = 'ResourcesFiles';
	//var $components = array('Users.Ratings', 'Resources.MimeTypes', 'Resources.Zip', 'ResourcesElearnings.Scorm');
    //var $helpers = array('Webshot', 'Time', 'Text', 'Crumb', 'Phpthumb',
    //	'Users.Rating', 'Users.CollapsibleBlocks', 'Users.FormExtended');

	/*function copy_to_project($id) {
		$this->layout = 'ajax'; // Read proyect folder before
		$this->loadModel("ResourcesFolder");
		$this->ResourcesFolder->Behaviors->load('Ratings.Rowaccess');
		$projectFolder = $this->ResourcesFolder->find('first', array('conditions' => array('project_id' => Configure::read('platform.current_project_id'))));
		if (!$projectFolder) die("KO");

		$this->ResourcesFile->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners($id)));
		$file = $this->ResourcesFile->find('first', array('conditions' => array('ResourcesFile.id' => $id)));

		$file['ResourcesFile']['path'] = str_replace('\\', DS, $file['ResourcesFile']['path']);
		$file['ResourcesFile']['path'] = str_replace('/', DS, $file['ResourcesFile']['path']);
		$path = APP . 'files' . DS . $file['ResourcesFile']['path'] . $file['ResourcesFile']['mask'];
		$name = $file['ResourcesFile']['name'];
		$extension = $file['ResourcesFile']['extension'];
		$folder_id = $projectFolder['ResourcesFolder']['id'];

		$this->add_direct($path, $name, $extension, $folder_id, true);
		die('OK');
	}

	public function add_direct($path = '', $name = '', $extension = '', $folder_id = 0, $viewAll = false) {
		$randomDirectory = $this->randomPath(3);
		$targetDir = APP . 'files' . DS . $randomDirectory;
		$fileName = String::uuid();
		$path_parts = pathinfo($path);

		if (!file_exists($targetDir)) @mkdir($targetDir, 0777, true);
		$file = new File($path);
		$file->copy($targetDir.$fileName);

		$size = filesize($targetDir.$fileName);
		$data = array(
			'ResourcesFile' => array(
				'name' => $name, 'extension' => $extension,
				'size' => $size,
				'path' => $randomDirectory, 'mask' => $fileName,
				'views' => 0, 'downloads' => 0,
				'resources_folder_id' => $folder_id,
				'user_id' => $this->Authake->getUserId()
			)
		);
		$this->ResourcesFile->create();
		$this->ResourcesFile->save($data);
		if ($viewAll) $this->ResourcesFile->makeViewAll();
		return true;
	}

	public function rowaccess($id = null) {
		$this->layout = 'rowaccess';

		if (!empty($this->request->data)) {
			$this->ResourcesFile->id = $this->request->data['ResourcesFile']['id'];
			$co_owners = $this->getFoldersCoOwners($this->request->data['ResourcesFile']['id']);
			$this->ResourcesFile->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $co_owners));
			if (array_key_exists('user_id', $this->request->data['ResourcesFile'])) $this->ResourcesFile->changeOwner($this->request->data['ResourcesFile']['user_id']);
			$this->ResourcesFile->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $co_owners));
			if (array_key_exists('rowaccess', $this->request->data['ResourcesFile'])) $this->ResourcesFile->changeRowaccess($this->request->data['ResourcesFile']['rowaccess']);
			die();
		}

		$co_owners = $this->getFoldersCoOwners($id);
		$this->ResourcesFile->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $co_owners));

		$this->ResourcesFile->id = $id;
		$this->request->data = $this->ResourcesFile->read();
	}*/

	public function index($folder_id = null) {
		$files = $this->ResourcesFile->find('all', array(
			'conditions' => array(
				'ResourcesFile.resources_folder_id' => $folder_id,
				'ResourcesFile.is_deleted' => 0
			),
			'order' => array('ResourcesFile.name' => 'ASC')
		));
		$this->set(compact('files'));
	}

	public function trash() {
		$files = $this->ResourcesFile->find('all', array(
			'conditions' => array(
				'ResourcesFile.is_deleted' => 1
			),
			'order' => array('ResourcesFile.name' => 'ASC')
		));
		$this->set(compact('files'));
	}

	public function add() {
		if (!empty($this->request->data)) {
			$this->request->data['ResourcesFile']['class'] = 'file';
			if (array_key_exists('folder', $this->request->named))
				$this->request->data['ResourcesFile']['resources_folder_id'] = $this->request->named['folder'];
			if ($this->ResourcesFile->save($this->request->data)) {
				$this->Session->setFlash(__d('resources','Your file has been saved.'), 'messages/success');
				$this->redirect(array('controller' => 'resources', 'action' => 'index', $this->request->data['ResourcesFile']['resources_folder_id']));
			}
		}
	}

	public function edit($id = null) {
		if (!empty($this->request->data)) {
			if ($this->ResourcesFile->save($this->request->data)) {
				$this->Session->setFlash(__d('resources','Your file has been update.'), 'messages/success');
				$this->redirect(array('controller' => 'resources', 'action' => 'index'));
			}
		}
		$this->request->data = $this->ResourcesFile->findById($id);
	}

	public function delete($id = null, $definitely = false) {
		if ($definitely) {
			$this->ResourcesFile->delete($id);
		} else {
			$this->ResourcesFile->id = $id;
			$this->ResourcesFile->saveField('is_deleted', 1);
		}
	}

	public function restore($id = null) {
		$this->ResourcesFile->id = $id;
		$this->ResourcesFile->saveField('is_deleted', 0);
	}

	/*public function upload() {
		$this->layout = 'ajax';

		$this->response->header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
		$this->response->header('Last-Modified', gmdate("D, d M Y H:i:s") . " GMT");
		$this->response->header('Cache-Control', 'no-store, no-cache, must-revalidate');
		$this->response->header('Cache-Control', 'post-check=0, pre-check=0');
		$this->response->header('Pragma', 'no-cache');

		$randomDirectory = $this->randomPath(3);
		$targetDir = APP . 'files' . DS . $randomDirectory;

		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
		$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
		$folder_id = isset($_REQUEST['folder_id']) ? $_REQUEST['folder_id'] : 0;

		// Clean the fileName for security reasons
		$fileName = String::uuid();

		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);
			$count = 1;
			while (file_exists($targetDir . $fileName_a . '_' . $count . $fileName_b)) $count++;
			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}

		// Create target dir
		if (!file_exists($targetDir)) @mkdir($targetDir, 0777, true);
		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"])) $contentType = $_SERVER["HTTP_CONTENT_TYPE"];
		if (isset($_SERVER["CONTENT_TYPE"])) $contentType = $_SERVER["CONTENT_TYPE"];
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				$out = @fopen($targetDir . $fileName, $chunk == 0 ? "wb" : "ab"); // Open temp file
				if ($out) {
					$in = fopen($_FILES['file']['tmp_name'], "rb"); // Read binary input stream and append it to temp file
					if ($in) { while ($buff = fread($in, 4096)) fwrite($out, $buff);
					} else die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					fclose($in); fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			} else die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		} else {
			$out = fopen($targetDir . $fileName, $chunk == 0 ? "wb" : "ab"); // Open temp file
			if ($out) {
				$in = fopen("php://input", "rb"); // Read binary input stream and append it to temp file
				if ($in) { while ($buff = fread($in, 4096)) fwrite($out, $buff);
				} else die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				fclose($in); fclose($out);
			} else die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		$pathinfo = pathinfo($_FILES['file']['name']);

		$ext = $pathinfo['extension'];
		$data = array(
			'ResourcesFile' => array(
				'name' => $pathinfo['filename'], 'extension' => $ext,
				'size' => $_FILES['file']['size'],
				'path' => $randomDirectory, 'mask' => $fileName,
				'views' => 0, 'downloads' => 0,
				'resources_folder_id' => $folder_id
			)
		);

		if ($ext == 'zip') {
			$r = $this->Zip->open($data);
			if ($r === true) {
				if (file_exists(WWW_ROOT.'files'.DS.$data['ResourcesFile']['path'].$data['ResourcesFile']['mask'].DS.'imsmanifest.xml')) {
					$ext = 'scorm';
					$this->Scorm->commentAlert(WWW_ROOT.'files'.DS.$data['ResourcesFile']['path'].$data['ResourcesFile']['mask'].DS);
				}
			}
			$data['ResourcesFile']['extension'] = $ext;
		}

		if (!$this->ResourcesFile->save($data)) die('{"jsonrpc" : "2.0", "error" : {"code": 150, "message": "Failed to save on database."}, "id" : "id"}');
		die('{ jsonrpc : "2.0", result : { }, id : "id"}');
	}

	public function view($id = null) {
		$this->ResourcesFile->id = $id;
		$file = $this->ResourcesFile->read();
		$this->set(compact('file'));
	}

	public function removeAllThumbs() {
		$files = $this->ResourcesFile->find('all', array('conditions' => array('mime_type' => 'image')));
		foreach ($files as $file) {
			$path = APP . 'files' . DS . $file['ResourcesFile']['path'];
			$subfiles = scandir($path);
			foreach ($subfiles as $subfile) {
				if ($subfile == '.' || $subfile == '..' || $subfile == $file['ResourcesFile']['mask']) continue;
				unlink($path . $subfile);
			}
		}
	}

	public function download($id = null) {
		$this->viewClass = 'Media';
		$this->autoRender = false;
		$this->autoLayout = false;

		$this->ResourcesFile->recursive = -1;
		$this->ResourcesFile->id = $id;
		$file = $this->ResourcesFile->read();

		$ext = $file['ResourcesFile']['extension'];
		$mask = $file['ResourcesFile']['mask'];
		$file['ResourcesFile']['path'] = str_replace('\\', DS, $file['ResourcesFile']['path']);
		$file['ResourcesFile']['path'] = str_replace('/', DS, $file['ResourcesFile']['path']);

		if ($ext == 'scorm') $ext = 'zip';

		$params = array(
			'id' => $mask,
			'name' => $file['ResourcesFile']['name'],
			'extension' => $ext,
			'mimeType' => array($ext => $this->MimeTypes->get_complete($ext)),
			'path' => APP . 'files' . DS . $file['ResourcesFile']['path'],
			'download' => true
		);

		$this->set($params);

		if ($this->render() !== false) {
			$this->ResourcesFile->updateAll(
				array('ResourcesFile.downloads' => 'ResourcesFile.downloads + 1'),
				array('ResourcesFile.id' => $id));
		}
	}

	function get($id = null) {
		if (strpos($id, '.') !== false) $id = substr($id, 0, strpos($id, '.'));

		$this->viewClass = 'Media';
		$this->autoRender = false;

		$this->ResourcesFile->recursive = -1;
		$this->ResourcesFile->id = $id;
		$file = $this->ResourcesFile->read();
		$file['ResourcesFile']['path'] = str_replace('\\', DS, $file['ResourcesFile']['path']);
		$file['ResourcesFile']['path'] = str_replace('/', DS, $file['ResourcesFile']['path']);
		$params = array(
			'id' => $file['ResourcesFile']['mask'],
			'name' => $file['ResourcesFile']['name'],
			'extension' => $file['ResourcesFile']['extension'],
			'mimeType'  => array(
	            $file['ResourcesFile']['extension'] => $this->MimeTypes->get_complete($file['ResourcesFile']['extension'])
	        ),
			'path' => APP . 'files' . DS . $file['ResourcesFile']['path'],
			'download' => false
		);

		$this->set($params);
		if ($this->render() !== false) $this->ResourcesFile->updateAll(array('ResourcesFile.views' => 'ResourcesFile.views + 1'), array('ResourcesFile.id' => $id));
	}

	function getFoldersCoOwners($id) {
		$this->ResourcesFile->Behaviors->unload('Ratings.Rowaccess'); // Deactivate Rowaccess
		$this->ResourcesFile->id = $id;
		$object = $this->ResourcesFile->read();
		$folderOwners = $this->requestAction(
			array('plugin' => 'resources', 'controller' => 'resources_folders', 'action' => 'get_parent_folders_owners'),
			array('pass' => array($object['ResourcesFile']['resources_folder_id']))
		);
		return $folderOwners;
	}

	function getFoldersCoOwners2($folder_id) {
		$folderOwners = $this->requestAction(
			array('plugin' => 'resources', 'controller' => 'resources_folders', 'action' => 'get_parent_folders_owners'),
			array('pass' => array($folder_id))
		);
		return $folderOwners;
	}

	/* > Utils
	 * ********************************************************************** */

	/*function randomPath($maxlevel = 3) {
		$level = rand(1, $maxlevel); $path = '';

		$validCharacters = "abcdefghijklmnopqrstuvwxyz0123456789";
		for ($i = 0; $i < $level; $i++) {
			$tempFolder = ''; $tempFolderSize = rand(2, 5);
			for ($j = 0; $j < $tempFolderSize; $j++) {
			    $index = mt_rand (0, strlen($validCharacters) - 1);
			    $tempFolder .= $validCharacters[$index];
			}
			$path .= $tempFolder . DS;
		}

		return $path;
	}

	/* > AJAX Icarus actions
	 * ********************************************************************** */
	/*function remove($id) {
		$this->layout = 'ajax';
		$this->ResourcesFile->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners($id)));
		//Remove file
		$this->ResourcesFile->id = $id;
		$this->ResourcesFile->saveField('removed', 1);
		$this->Logs->logThis($this, $this->Authake->getLogin().' has remove file with id "'.$id.'"');
		//$this->ResourcesFile->read();
		//$file = $this->ResourcesFile->read();
		//$path = APP . 'files' . DS . $file['ResourcesFile']['path'] . $file['ResourcesFile']['mask'];
		//$file2 = new File($path);
		//$file2->delete();
		//$this->ResourcesFile->delete($id);
		die('OK');
	}
	function move($id, $folder_dest_id) {
		$this->layout = 'ajax';
		$this->ResourcesFile->id = $id;
		$this->ResourcesFile->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners($id)));
		$this->ResourcesFile->saveField('resources_folder_id', $folder_dest_id);
		$this->Logs->logThis($this, $this->Authake->getLogin().' has move file with id "'.$id.'" to folder id "'.$folder_dest_id.'"');
		die('OK');
	}
	function ren() {
		$this->layout = 'ajax';
		$this->ResourcesFile->id = $this->request->data['id'];
		$this->ResourcesFile->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners($this->request->data['id'])));
		$this->ResourcesFile->save(array('ResourcesFile' => array('name' => $this->request->data['name'])));
		die('{"status":"ok"}');
	}

	/* > AJAX Rate actions
	 * ********************************************************************** */
	/*function rate($id, $rate) {
		$this->layout = 'ajax';
		$result = $this->ResourcesFile->saveRating($id, $this->Authake->getUserId(), $rate);
		die('{"status":"ok", "data":'.json_encode($result['ResourcesFile']).'}');
	}
	function remove_rate($id) {
		$this->layout = 'ajax';
		$result = $this->ResourcesFile->removeRating($id, $this->Authake->getUserId());
		die('{"status":"ok", "data":'.json_encode($result['ResourcesFile']).'}');
	}*/

	/* > Remote actions
	 * ********************************************************************** */

	/*function items_folder($folder_id) {
		$this->ResourcesFile->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners2($folder_id)));
		$items = $this->ResourcesFile->find('all', array('conditions' => array(
			'ResourcesFile.resources_folder_id' => $folder_id, 'ResourcesFile.removed' => 0
		)));
		return $items;
	}

	function items_search($search) {
		/*$this->ResourcesFile->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners2($folder_id)));
		$items = $this->ResourcesFile->find('all', array('conditions' => array('ResourcesFile.name LIKE' => '%'.$search.'%')));
		return array('ResourcesFile' => $items);*/
		/*$tags = $this->ResourcesFile->Tag->find('first', array('conditions' => array('Tag.keyname' => strtolower($search))));
		$ids = array();
		if (!empty($tags['Tagged'])) foreach ($tags['Tagged'] as $tagged) if ($tagged['model'] == 'ResourcesFile') array_push($ids, $tagged['foreign_key']);
		$items = $this->ResourcesFile->find('all', array('conditions' => array(
			'OR' => array('ResourcesFile.name LIKE' => '%'.$search.'%', 'ResourcesFile.id' => $ids),
			'AND' => array('ResourcesFile.removed' => 0)
		)));
		return array('ResourcesFile' => $items);
	}*/
}
?>
