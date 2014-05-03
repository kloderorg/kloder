<?php
App::uses('Folder', 'Utility');
App::uses('AppController', 'Controller');
class PluginsController extends PluginsAppController {

	public $name = 'Plugins';

	public function index() {
		$plugins = $this->Plugin->find('all');
		$this->set(compact('plugins'));
	}

	public function install_develop($id = null) {
		$plugin = $this->Plugin->findById($id);

		$remote = $plugin['Plugin']['uri'].'/get/master.zip';
		$local = WWW_ROOT . 'files' . DS . 'tmp' . DS . $plugin['Plugin']['id'] . '.zip';
		if (file_exists($local)) unlink($local);
		$plugins_dir = ROOT . DS . APP_DIR . DS . 'Plugin' . DS;

		file_put_contents($local, fopen($remote, 'r'));

		$zip = new ZipArchive;
		if ($zip->open($local) === TRUE) {
    		$extract_dir = $zip->getNameIndex(0);
    		$zip->extractTo($plugins_dir);
    		$zip->close();

    		rename($plugins_dir . $extract_dir, $plugins_dir . $plugin['Plugin']['name']);

    		$this->Plugin->id = $id;
			$this->Plugin->saveField('status', 1);
			clearCache(null, "persistent");

			$sql_file = $plugins_dir . $plugin['Plugin']['name'] . DS . 'Config' . DS . 'Schema' . DS . 'database.sql';
			$db = $this->Plugin->getDataSource();
			$db->fetchAll(file_get_contents($sql_file));

    		$this->Session->setFlash(__('Plugin %s Installed', $plugin['Plugin']['name']), 'messages/success');
		} else {
    		$this->Session->setFlash(__('Error opening ZIP file'), 'messages/error');
		}

		$this->redirect(array('action'=>'index'));
	}

	public function delete($id = null) {
		$plugin = $this->Plugin->findById($id);

		$dir = new Folder(ROOT . DS . APP_DIR . DS . 'Plugin' . DS . $plugin['Plugin']['name']);
		$dir->delete();

		$this->Plugin->id = $id;
		$this->Plugin->saveField('status', 0);

		clearCache(null, "persistent");
		$local = WWW_ROOT . 'files' . DS . 'tmp' . DS . $plugin['Plugin']['id'] . '.zip';
		if (file_exists($local)) unlink($local);

    	$this->Session->setFlash(__('Plugin %s Deleted', $plugin['Plugin']['name']), 'messages/success');
    	$this->redirect(array('action'=>'index'));
	}

}
