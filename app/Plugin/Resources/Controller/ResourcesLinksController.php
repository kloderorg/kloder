<?php
class ResourcesLinksController extends ResourcesAppController {

	public $name = 'ResourcesLinks';
	public $uses = array('Resources.ResourcesFile');

	//var $components = array('Users.Ratings', 'Users.TagsGroups');
    //var $helpers = array('Webshot', 'Time', 'Text', 'Crumb', 'Phpthumb',
    //	'Users.Rating', 'Users.CollapsibleBlocks', 'Users.FormExtended', 'Ratings.Rowaccess', 'Users.TagFields');

	/* > Common actions
	 * ********************************************************************** */

	/*public function rowaccess($id = null) {
		$this->layout = 'rowaccess';

		if (!empty($this->request->data)) {
			$this->ResourcesLink->id = $this->request->data['ResourcesLink']['id'];
			$co_owners = $this->getFoldersCoOwners($this->request->data['ResourcesLink']['id']);
			$this->ResourcesLink->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $co_owners));
			if (array_key_exists('user_id', $this->request->data['ResourcesLink'])) $this->ResourcesLink->changeOwner($this->request->data['ResourcesLink']['user_id']);
			$co_owners = $this->getFoldersCoOwners($this->request->data['ResourcesLink']['id']);
			$this->ResourcesLink->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $co_owners));
			if (array_key_exists('rowaccess', $this->request->data['ResourcesLink'])) $this->ResourcesLink->changeRowaccess($this->request->data['ResourcesLink']['rowaccess']);
			die();
		}

		$co_owners = $this->getFoldersCoOwners($id);
		$this->ResourcesLink->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $co_owners));

		$this->ResourcesLink->id = $id;
		$this->request->data = $this->ResourcesLink->read();
	}*/

	public function add() {
		if (!empty($this->request->data)) {
			$this->request->data['ResourcesFile']['class'] = 'link';
			if (array_key_exists('folder', $this->request->named))
				$this->request->data['ResourcesFile']['resources_folder_id'] = $this->request->named['folder'];
			if ($this->ResourcesFile->save($this->request->data)) {
				$this->Session->setFlash(__d('resources','Your file has been saved.'), 'messages/success');
				$this->redirect(array('controller' => 'resources', 'action' => 'index', $this->request->data['ResourcesFile']['resources_folder_id']));
			}
		}
	}

	/*function add_remote() {
		$this->layout = 'remote';
		if (!empty($this->request->data)) {
			if ($this->ResourcesLink->save($this->request->data)) $this->set('saved', true);
			else $this->set('saved', false);
		} else {
			$this->set('avaiableTags', $this->ResourcesLink->Tag->find('list'));
			$this->set('link', $this->request->query['url']);
			$this->set('title', $this->request->query['title']);
			$this->set('referer', $this->referer());
		}
	}*/

	function edit($id = null) {
		if (!empty($this->request->data)) {
			if ($this->ResourcesFile->save($this->request->data)) {
				$this->Session->setFlash(__d('resources','Your link has been updated.'), 'messages/success');
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

	/*function view($id = null) {
		$this->set('title_for_layout', __d('resources', 'View link', true));
		if (isset($this->request->query['lang'])) {
			Configure::write('Config.language', $this->request->query['lang']);
		}
		if (!$this->Authake->isLogged()) $this->layout = 'public';
		$this->ResourcesLink->id = $id;
		$link = $this->ResourcesLink->read();
		$this->set(compact('link'));
	}

	function getFoldersCoOwners($id) {
		$this->ResourcesLink->Behaviors->unload('Ratings.Rowaccess'); // Deactivate Rowaccess
		$this->ResourcesLink->id = $id;
		$object = $this->ResourcesLink->read();
		$folderOwners = $this->requestAction(
			array('plugin' => 'resources', 'controller' => 'resources_folders', 'action' => 'get_parent_folders_owners'),
			array('pass' => array($object['ResourcesLink']['resources_folder_id']))
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

	/* > AJAX actions
	 * ********************************************************************** */

	/*function copy_to_project($id){
		$this->layout = 'ajax';
		$this->loadModel("ResourcesFolder");
		$this->ResourcesFolder->Behaviors->load('Ratings.Rowaccess');
		$project = $this->ResourcesFolder->find('first', array('conditions' => array('project_id' => Configure::read('platform.current_project_id'))));
		if (!$project) die("KO");

		$this->ResourcesLink->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners($id)));
		$link = $this->ResourcesLink->find('first', array('conditions' => array('ResourcesLink.id' => $id)));

		unset($link['ResourcesLink']['rowaccess']);
		unset($link['ResourcesLink']['id']);

		$link['ResourcesLink']['user_id'] = $this->Authake->getUserId();
		$link['ResourcesLink']['resources_folder_id'] = $project['ResourcesFolder']['id'];
		$this->ResourcesLink->create();
		$this->ResourcesLink->save($link);
		$this->ResourcesLink->makeViewAll();
		die('OK');
	}

	function remove($id) {
		$this->layout = 'ajax';
		$this->ResourcesLink->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners($id)));
		$this->ResourcesLink->saveField('removed', 1);
		$this->Logs->logThis($this, $this->Authake->getLogin().' has remove link with id "'.$id.'"');
		die('OK');
	}

	function move($id, $folder_dest_id) {
		$this->layout = 'ajax';
		$this->ResourcesLink->id = $id;
		$this->ResourcesLink->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners($id)));
		$this->ResourcesLink->saveField('resources_folder_id', $folder_dest_id);
		$this->Logs->logThis($this, $this->Authake->getLogin().' has move folder with id "'.$id.'" to folder id "'.$folder_dest_id.'"');
		die('OK');
	}

	function ren() {
		$this->layout = 'ajax';
		$this->ResourcesLink->id = $this->request->data['id'];
		$this->ResourcesLink->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners($this->request->data['id'])));
		$this->ResourcesLink->save(array('ResourcesLink' => array('title' => $this->request->data['name'])));
		die('{"status":"ok"}');
	}

	function feed($limit = 20, $order = 'latest', $lang = 'es_ES') {
		Configure::write('Config.language', $lang);
		$this->layout = 'ajax';
		if ($order == 'latest') $order = 'ResourcesLink.created ASC';
		$items = $this->ResourcesLink->find('all', array('limit' => $limit, 'order' => $order));
		$this->set(compact('items'));
	}

	/* > AJAX Rate actions
	 * ********************************************************************** */
	/*function rate($id, $rate) {
		$this->layout = 'ajax';
		$result = $this->ResourcesLink->saveRating($id, $this->Authake->getUserId(), $rate);
		die('{"status":"ok", "data":'.json_encode($result['ResourcesLink']).'}');
	}
	function remove_rate($id) {
		$this->layout = 'ajax';
		$result = $this->ResourcesLink->removeRating($id, $this->Authake->getUserId());
		die('{"status":"ok", "data":'.json_encode($result['ResourcesLink']).'}');
	}

	/* > Remote actions
	 * ********************************************************************** */

	/*function items_folder($folder_id) {
		$this->ResourcesLink->Behaviors->load('Ratings.Rowaccess', array('co_owners' => $this->getFoldersCoOwners2($folder_id)));
		$items = $this->ResourcesLink->find('all', array('conditions' => array(
			'ResourcesLink.resources_folder_id' => $folder_id, 'ResourcesLink.removed' => 0
		)));
		return $items;
	}

	function items_search($search) {
		$tags = $this->ResourcesLink->Tag->find('first', array('conditions' => array('Tag.keyname' => strtolower($search))));
		$ids = array();
		if (!empty($tags['Tagged'])) foreach ($tags['Tagged'] as $tagged) if ($tagged['model'] == 'ResourcesLink') array_push($ids, $tagged['foreign_key']);
		$items = $this->ResourcesLink->find('all', array('conditions' => array(
			'OR' => array('ResourcesLink.title LIKE' => '%'.$search.'%', 'ResourcesLink.id' => $ids),
			'AND' => array('ResourcesLink.removed' => 0)
		)));
		return array('ResourcesLink' => $items);
	}*/
}
?>
