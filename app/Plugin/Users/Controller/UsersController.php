<?php
App::uses('UsersAppController', 'Users.Controller');
class UsersController extends UsersAppController {

    public $name = 'Users';

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'lostpassword');
    }

	public function login() {
        if (AuthComponent::user('id') != null) $this->redirect($this->Auth->redirectUrl());
		$this->layout = 'login';
		if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
	            return $this->redirect($this->Auth->redirectUrl());
	        }
	        $this->Session->setFlash(__d('users', 'Invalid username or password, try again'), 'messages/error');
	    }
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

	public function lostpassword() {
		$this->layout = 'login';
		/* TODO */
	}

	public function index() {
		$this->User->bindModel(array('belongsTo' => array('UsersGroup')));
        $this->set('users', $this->paginate());
	}

	public function view($id = null) {
        $this->User->bindModel(array('belongsTo' => array('UsersGroup')));
        $this->set('user', $this->User->findById($id));
	}

	public function add() {
		if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__d('users', 'The user has been saved'), 'messages/success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__d('users', 'The user could not be saved. Please, try again.'), 'messages/error');
        }
        $this->User->bindModel(array('belongsTo' => array('UsersGroup')));
        $this->set('usersGroups', $this->User->UsersGroup->find('list'));
	}

	public function edit($id = null) {
		$this->User->id = $id;
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__d('users', 'The user has been saved'), 'messages/success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__d('users', 'The user could not be saved. Please, try again.'), 'messages/error');
        } else {
            $this->User->bindModel(array('belongsTo' => array('UsersGroup')));
            $this->request->data = $this->User->findById($id);
            unset($this->request->data['User']['password']);
            $this->set('usersGroups', $this->User->UsersGroup->find('list'));
        }
	}

	public function delete($id = null) {
        $this->request->onlyAllow('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__d('users', 'Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__d('users', 'User deleted'), 'messages/success');
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__d('users', 'User was not deleted'), 'messages/error');
        return $this->redirect(array('action' => 'index'));
    }

}
