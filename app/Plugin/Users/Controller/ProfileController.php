<?php
App::uses('UsersAppController', 'Users.Controller');
class ProfileController extends UsersAppController {

    public $uses = array('Users.User');

	public function beforeFilter() {
        parent::beforeFilter();
    }

	public function index() {
        $this->User->bindModel(array('belongsTo' => array('Language')));
        $user = $this->User->findById(AuthComponent::user('id'));
        $this->set(compact('user'));
	}

	public function view($user_id = null) {
        $this->User->bindModel(array('belongsTo' => array('Language')));
		$user = $this->User->findById($user_id);
        $this->set(compact('user'));
	}

	public function edit() {
		$user = $this->User->findById(AuthComponent::user('id'));
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->User->Behaviors->load('Resources.Upload', array('thumb' => array(
                'fields' => array(
                    'dir' => 'thumb_dir'
                )
            )));
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__d('users', 'The profile has been saved'), 'messages/success');
                if (!empty($this->request->data['User']['language_id'])) {
                    $this->Session->write('Config.language', $this->request->data['User']['language_id']);
                }
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__d('users', 'The profile could not be saved. Please, try again.'), 'messages/error');
        } else {
            $this->request->data = $user;
            unset($this->request->data['User']['password']);
            $this->User->bindModel(array('belongsTo' => array('Language')));
            $this->set('languages', $this->User->Language->find('list'));
        }
	}

}
