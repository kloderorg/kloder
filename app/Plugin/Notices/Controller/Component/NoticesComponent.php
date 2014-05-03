<?php
App::uses('Component', 'Controller');
class NoticesComponent extends Component {

	public function initialize(Controller $controller) {
        if (AuthComponent::user('id') == null) return;

        $controller->loadModel('Notice');
        $notices = $controller->Notice->find('all', array('conditions' => array('Notice.active' => 1)));
        Configure::write('Notices', $notices);
    }

}
