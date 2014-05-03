<?php
class MenuItemsController extends MenusAppController {

	function add() {
		if (!empty($this->data)) {
			$this->MenuItem->save($this->data);
			$this->Session->setFlash(__d('platform', 'Item created!', true), 'success');
			$this->redirect(array('plugin' => 'platform', 'controller' => 'menu', 'action'=>'index'));
		}
	}

	function add_separation() {
		$this->MenuItem->save(array(
			'MenuItem' => array(
				'title' => '---',
				'icon' => '',
				'ubication' => '',
				'disabled' => 0,
				'extra_ubications' => '',
				'parent_id' => 0
			)
		));
		$this->Session->setFlash(__d('platform', 'Separation created!', true), 'success');
		$this->redirect(array('plugin' => 'platform', 'controller' => 'menu', 'action'=>'index'));
	}

	function edit($id = null) {
		$this->MenuItem->id = $id;
		if (!empty($this->data)) {
			$this->MenuItem->save($this->data);
			$this->Session->setFlash(__d('platform', 'Item changed!', true), 'success');
			$this->redirect(array('plugin' => 'platform', 'controller' => 'menu', 'action'=>'index'));
		}
		$this->data = $this->MenuItem->read();
	}

	function delete($id = null) {
		$this->MenuItem->delete($id);
		Configure::write('debug', 0);
		$this->autoRender = false;
		$this->autoLayout = false;
		//$this->Session->setFlash(__d('platform','Your item has been deleted.', true), 'success');
		//$this->redirect(array('plugin' => 'platform', 'controller' => 'menu', 'action'=>'index'));
	}

	function enable($id = null) {
		$this->MenuItem->id = $id;
		$this->MenuItem->saveField('disabled', '0');
		$this->Session->setFlash(__d('platform', 'Item enabled!', true), 'success');
		$this->redirect(array('plugin' => 'platform', 'controller' => 'menu', 'action'=>'index'));
	}

	function disable($id = null) {
		$this->MenuItem->id = $id;
		$this->MenuItem->saveField('disabled', '1');
		$this->Session->setFlash(__d('platform', 'Item disabled!', true), 'success');
		$this->redirect(array('plugin' => 'platform', 'controller' => 'menu', 'action'=>'index'));
	}

	function move_ajax($id = null, $delta = null, $parent_id = null) {
		$this->layout = 'ajax';
		$this->MenuItem->id = $id;

		if ($parent_id != null) {
			$this->MenuItem->saveField('parent_id', $parent_id);
		}

		if ($delta > 0) {
	        $this->MenuItem->moveDown($this->MenuItem->id, abs($delta));
	    } elseif ($delta < 0) {
	        $this->MenuItem->moveUp($this->MenuItem->id, abs($delta));
	    }

		//$this->Session->setFlash(__d('platform', 'Item moved!', true), 'success');
		die();
	}

	function move($id = null, $delta = null, $parent_id = null) {
		$this->MenuItem->id = $id;

		if ($parent_id != null) {
			$this->MenuItem->saveField('parent_id', $parent_id);
		}

		if ($delta > 0) {
	        $this->MenuItem->moveDown($this->MenuItem->id, abs($delta));
	    } elseif ($delta < 0) {
	        $this->MenuItem->moveUp($this->MenuItem->id, abs($delta));
	    }

		$this->Session->setFlash(__d('platform', 'Item moved!', true), 'success');
		$this->redirect(array('plugin' => 'platform', 'controller' => 'menu', 'action'=>'index'));
	}
}
?>
