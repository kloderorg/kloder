<?php
App::uses('AppController', 'Controller');
class PluginsRepositoriesController extends PluginsAppController {

	public $name = 'PluginsRepositories';

	public function index() {
		$repositories = $this->paginate('PluginsRepository');
		$this->set(compact('repositories'));
	}

	public function add() {
		if (!empty($this->data)) {
			if ($this->PluginsRepository->save($this->data)) {
				$this->Session->setFlash(__d('plugins', 'Your repository has been saved.'), 'messages/success');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	public function edit($id = null) {

	}

	public function update($id) {
		$repository = $this->Repository->findById($id);
		$xml = Xml::build($repository['Repository']['url']);

		if ($xml->channel->item->count() > 0) {
			$this->loadModel('Plugin');
			foreach ($xml->channel->item as $plugin) {
				$check = $this->Plugin->find('all', array('conditions' => array('repository_id' => $id, 'link' => ((string) $plugin->link))));
				if (empty($check)) {
					$this->Plugin->create();
					$this->Plugin->save(array('Plugin' => array(
						'repository_id' => $id,
						'name' => ((string) $plugin->title),
						'link' => ((string) $plugin->link),
						'version' => ((string) $plugin->version),
						'uri' => ((string) $plugin->uri)
					)));
				} else {
					// TODO: Check versions
				}
				//debug($plugin);
			}
			//die();
		}

		$this->Repository->id = $id;
		$this->Repository->saveField('plugins', $xml->channel->item->count());

		$this->redirect(array('action' => 'index'));
	}

	public function delete($id = null) {

	}

}
