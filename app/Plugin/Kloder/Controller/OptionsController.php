<?php
class OptionsController extends KloderAppController {

	var $uses = 'Setting';

	function index() {
		$this->Setting->Behaviors->attach('Pair');
		if (empty($this->data)) {
			$this->data = $this->Setting->findPairs(0);
			$this->loadModel('Language');
			$this->set('languages', $this->Language->find('list', array('conditions' => array('status' => 1))));
			$this->loadModel('Theme');
			$this->set('themes', $this->Theme->find('list', array('conditions' => array('status' => 1))));
		} else {
			if ($this->Setting->savePairs(0, $this->data)) {
				//debug($this->data['Setting']['language_id']); die();
				Configure::write('Config.language', $this->data['Setting']['language_id']);
				$this->Session->setFlash(__('Settings saved', true), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Settings error on save', true), 'error');
			}
		}
		$this->set('referer', $this->referer());
	}
}
?>
