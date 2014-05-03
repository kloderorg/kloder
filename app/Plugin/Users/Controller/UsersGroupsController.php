<?php
App::uses('UsersAppController', 'Users.Controller');
class UsersGroupsController extends UsersAppController {

	public $name = 'UsersGroups';

	public function index() {
		$groups = $this->paginate('UsersGroup');
		$this->set('groups', $groups);
	}

	public function view($id = null) {
		$group = $this->UsersGroup->findById($id);
		$this->set('group', $group);
	}

	public function add() {
		if ($this->request->is('post')) {
            $this->UsersGroup->create();
            if ($this->UsersGroup->save($this->request->data)) {
                $this->Session->setFlash(__d('users', 'The group has been saved'), 'messages/success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__d('users', 'The group could not be saved. Please, try again.'), 'messages/error');
        }
	}

	public function edit($id = null) {
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->UsersGroup->save($this->request->data)) {
                $this->Session->setFlash(__d('users', 'The group has been update'), 'messages/success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__d('users', 'The group could not be update. Please, try again.'), 'messages/error');
        } else {
            $this->request->data = $this->UsersGroup->findById($id);
        }
	}

}

