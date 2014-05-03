<?php
App::uses('NoticesAppController', 'Notices.Controller');
class NoticesController extends NoticesAppController {

	public function index() {
		$notices = $this->paginate('Notice');
		$this->set(compact('notices'));
	}

	public function view($id = null) {
		$notice = $this->Notice->findById($id);
		$this->set(compact('notice'));
	}

	public function activate($id = null) {
		$this->Notice->updateAll(
			array('Notice.active' => 1),
			array('Notice.id' => $id)
		);
		$this->redirect(array('action' => 'index'));
	}

	public function deactivate($id = null) {
		$this->Notice->updateAll(
			array('Notice.active' => 0),
			array('Notice.id' => $id)
		);
		$this->redirect(array('action' => 'index'));
	}

	public function add() {
		if (!empty($this->data)) {
			if ($this->Notice->save($this->data)) {
				$this->Session->setFlash(__d('notices', 'Your notice has been saved.'), 'messages/success');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	public function edit($id = null) {
		$this->Notice->id = $id;
		if (empty($this->request->data)) {
			$this->data = $this->Notice->read();
		} else {
			if ($this->Notice->save($this->request->data)) {
				$this->Session->setFlash(__d('notices', 'Your notice has been updated.'), 'messages/success');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	public function delete($id) {
		if ($this->Notice->delete($id)) {
			$this->Session->setFlash(__d('notices', 'The notice has been deleted.'), 'messages/success');
			$this->redirect(array('action' => 'index'));
		}
	}

}
