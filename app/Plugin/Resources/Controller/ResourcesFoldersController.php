<?php
class ResourcesFoldersController extends ResourcesAppController {

	public $name = 'ResourcesFolders';

	public function index($parent_id = null) {
		$folders = $this->ResourcesFolder->find('all', array(
			'conditions' => array(
				'ResourcesFolder.parent_id' => $parent_id,
				'ResourcesFolder.is_deleted' => 0
			),
			'order' => array('ResourcesFolder.name' => 'ASC')
		));
		$this->set(compact('folders'));
	}

	public function trash() {
		$folders = $this->ResourcesFolder->find('all', array(
			'conditions' => array(
				'ResourcesFolder.is_deleted' => 1
			),
			'order' => array('ResourcesFolder.name' => 'ASC')
		));
		$this->set(compact('folders'));
	}

	public function view($id = null) {
		$folder = $this->ResourcesFolder->find('all', array('conditions' => array('ResourcesFolder.id' => $id)));
		$this->set(compact('folder'));
	}



	//var $helpers = array('Users.FormExtended');

	/* > AJAX actions
	 * ********************************************************************** */

	/*public function rowaccess($id = null) {
		$this->layout = 'rowaccess';

		if (!empty($this->data)) {
			$this->ResourcesFolder->id = $this->data['ResourcesFolder']['id'];
			$co_owners = $this->getFoldersCoOwners($this->data['ResourcesFolder']['id']);
			$this->ResourcesFolder->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $co_owners));
			if (array_key_exists('user_id', $this->data['ResourcesFolder'])) $this->ResourcesFolder->changeOwner($this->data['ResourcesFolder']['user_id']);
			$co_owners = $this->getFoldersCoOwners($this->data['ResourcesFolder']['id']);
			$this->ResourcesFolder->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $co_owners));
			if (array_key_exists('rowaccess', $this->data['ResourcesFolder'])) $this->ResourcesFolder->changeRowaccess($this->data['ResourcesFolder']['rowaccess']);
			die();
		}

		$co_owners = $this->getFoldersCoOwners($id);
		$this->ResourcesFolder->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $co_owners));

		$this->ResourcesFolder->id = $id;
		$this->data = $this->ResourcesFolder->read();
	}

	function move($id, $dest_id) {
		$this->layout = 'ajax';
		$this->ResourcesFolder->id = $id;
		$this->ResourcesFolder->saveField('parent_id', $dest_id);
		$this->Logs->logThis($this, $this->Authake->getLogin().' has move folder with id "'.$id.'" to folder id "'.$dest_id.'"');
		die('OK');
	}

	/**
	 * POST AJAX add function
	 * @param title
	 * @param parent_id
	 * @return JSON response with ResourcesFolder or error
	 */
	function add() {
		if (!empty($this->request->data)) {
			if ($this->ResourcesFolder->save($this->request->data)) {
				$this->Session->setFlash(__d('resources', 'Your folder has been saved.'), 'messages/success');
				$this->redirect(array('controller' => 'resources', 'action' => 'index'));
			}
		}
	}

	function edit($id = null) {
		if (!empty($this->request->data)) {
			if ($this->ResourcesFolder->save($this->request->data)) {
				$this->Session->setFlash(__d('resources','Your folder has been updated.', true), 'messages/success');
				$this->redirect(array('controller' => 'resources', 'action' => 'index'));
			}
		}
		$this->request->data = $this->ResourcesFolder->findById($id);
	}

	public function delete($id = null, $definitely = false) {
		if ($definitely) {
			$this->ResourcesFolder->delete($id);
		} else {
			$this->ResourcesFolder->id = $id;
			$this->ResourcesFolder->saveField('is_deleted', 1);
		}
	}

	public function restore($id = null) {
		$this->ResourcesFolder->id = $id;
		$this->ResourcesFolder->saveField('is_deleted', 0);
	}

	/*function ren() {
		$this->layout = 'ajax';
		$this->ResourcesFolder->id = $this->request->data['id'];
		$this->ResourcesFolder->save(array('ResourcesFolder' => array(
			'title' => $this->request->data['title'],
			'slug' => strtolower(Inflector::slug($this->request->data['title'], $replacement = '-'))
		)));
		die('{"status":"ok"}');
	}

	// TODO
	function remove($id, $level = 0) {
		$this->layout = 'ajax';

		/*$this->loadModel('ResourcesLink');
		$this->loadModel('ResourcesArticle');
		$this->loadModel('ResourcesFile');
		$this->loadModel('ResourcesCode');

		$this->ResourcesLink->deleteAll(array('ResourcesLink.resources_folder_id' => $id));
		$this->ResourcesArticle->deleteAll(array('ResourcesArticle.resources_folder_id' => $id));
		$this->ResourcesFile->deleteAll(array('ResourcesFile.resources_folder_id' => $id));
		$this->ResourcesCode->deleteAll(array('ResourcesCode.resources_folder_id' => $id));*/

		//$subfolders = $this->ResourcesFolder->find('all', array('conditions' => array('ResourcesFolder.parent_id' => $id)));
		//foreach($subfolders as $subfolder) $this->remove($subfolder['ResourcesFolder']['id']);
		//$this->ResourcesFolder->delete($id);

		/*$this->ResourcesFolder->id = $id;
		$this->ResourcesFolder->saveField('removed', 1);
		$this->Logs->logThis($this, $this->Authake->getLogin().' has remove folder with id "'.$id.'"');

		die('OK');
	}

	// Recover structure lft & rght of tree
	function recover() {
		$this->ResourcesFolder->recover('parent', 0);
		$this->redirect(array('plugin' => 'resources', 'controller' => 'resources', 'action' => 'maintenance'));
	}

	function getFoldersCoOwners($id) {
		return $this->get_parent_folders_owners($id);
	}

	/* > Remote actions
	 * ********************************************************************** */

	/*function get_parent_folders_owners($folder_id) {
		$out = array();
		$this->ResourcesFolder->Behaviors->load('Ratings.Rowaccess');
		$this->ResourcesFolder->id = $folder_id;
		$folder = $this->ResourcesFolder->read();
		if (!empty($folder) && $folder['ResourcesFolder']['parent_id'] != 0) $out = array_merge($out, $this->get_parent_folders_owners($folder['ResourcesFolder']['parent_id']));
		$out = array_merge($out, $this->ResourcesFolder->getOwnersIds($folder_id));
		return $out;
	}

	/**
	 * get_folder_threaded
	 */
	/*function get_folder($slug_path = '') {
		$folder_id = 0;
		$folders = array();

		array_push($folders, $this->get_home_folder());
		if ($slug_path != '') {
			$slug_path = rtrim($slug_path, '|');
			$folders_slug = explode('|', $slug_path);
			foreach($folders_slug as $folder_slug) {
				$this->ResourcesFolder->unbindModel(array('belongsTo' => array('User')));
				$folder = $this->ResourcesFolder->find('first', array('conditions' => array(
					'ResourcesFolder.parent_id' => $folder_id,
					'ResourcesFolder.slug' => $folder_slug
				)));
				if ($folder == null) return null;
				array_push($folders, $folder);
				$folder_id = $folder['ResourcesFolder']['id'];
			}
		}
		return $folders;
	}

	function get_home_folder() {
		$current_group_ids = $this->Session->read('Authake.group_ids');
		$rowaccess_superusers_groups = explode(',', Configure::read('platform.rowaccess_superusers_groups'));
		$intersect = array_intersect($rowaccess_superusers_groups, $current_group_ids);
		$is_owner = (!empty($intersect));
		return array('ResourcesFolder' => array(
			'id' => 0,
			'title' => __d('resources','Home'),
			'slug' => 'home',
			'rowaccess' => 'a:3:{s:7:"general";a:2:{s:4:"view";s:4:"user";s:4:"edit";s:4:"user";}s:6:"groups";a:0:{}s:5:"users";a:0:{}}',
			'user_id' => '1',
			'is_owner' => $is_owner,
			'is_public' => false,
			'can_edit' => $is_owner
		));
	}

	function get_folder_by_id($folder_id = 0) {
		$folders = array(); $folder_slug = '';
		$folder_slug_array = array();
		while ($folder_id != 0) {
			$this->ResourcesFolder->unbindModel(array('belongsTo' => array('User')));
			$folder = $this->ResourcesFolder->find('first', array('conditions' => array('ResourcesFolder.id' => $folder_id)));
			if (empty($folder)) return null;
			array_push($folder_slug_array, $folder['ResourcesFolder']['slug']);
			array_push($folders, $folder);
			$folder_id = $folder['ResourcesFolder']['parent_id'];
		}
		array_push($folders, array('ResourcesFolder' => array('id' => 0, 'title' => __d('resources', 'Home'))));
		$folder_slug_array = array_reverse($folder_slug_array);
		$folder_slug = implode('/', $folder_slug_array);
		$folder_slug .= '/';
		$folders = array_reverse($folders);
		return array($folders, $folder_slug);
	}

	function ajax_get_all() {
		$this->layout = 'ajax';
		$this->ResourcesFolder->Behaviors->load('Ratings.Rowaccess', array('force_show' => true));
		echo json_encode($this->ResourcesFolder->find('threaded', array('conditions' => array('ResourcesFolder.removed' => 0))));
		die();
	}

	function items_folder($folder_id) {
		$this->ResourcesFolder->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners($folder_id)));
		$items = $this->ResourcesFolder->find('all', array('conditions' => array(
			'ResourcesFolder.parent_id' => $folder_id, 'ResourcesFolder.removed' => 0
		)));
		return $items;
	}

	function items_search($search) {
		$items = $this->ResourcesFolder->find('all', array('conditions' => array(
			'ResourcesFolder.title LIKE' => '%'.$search.'%', 'ResourcesFolder.removed' => 0
		)));
		return array('ResourcesFolder' => $items);
	}

	function get_folders_from_id($folder_id = 0) {
		$this->ResourcesFolder->Behaviors->load('Ratings.Rowaccess', array('force_show' => true));
		$folders = $this->ResourcesFolder->find('threaded', array('conditions' => array('ResourcesFolder.removed' => 0)));
		return $this->get_folders_from_id_search($folders, $folder_id);
	}

	function get_folders_from_id_search($folders = array(), $folder_id = 0) {
		foreach($folders as $folder) {
			if ($folder['ResourcesFolder']['id'] == $folder_id) return $folder;
			if (count($folder['children']) > 0) {
				$result = $this->get_folders_from_id_search($folder['children'], $folder_id);
				if ($result != null) return $result;
			}
		}
		return null;
	}*/
}
?>
