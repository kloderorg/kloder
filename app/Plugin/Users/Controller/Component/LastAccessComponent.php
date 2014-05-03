<?php
App::uses('Component', 'Controller');
class LastAccessComponent extends Component {

	public function initialize(Controller $controller) {
        if (AuthComponent::user('id') == null) return;

        $model_user_alias = 'User';
        App::uses($model_user_alias, 'Users.Model');
        $model_user = new $model_user_alias();

        $model_user->save(array('User' => array(
            'id' => AuthComponent::user('id'),
            'last_access' => date('Y-m-d H:i:s'),
            'modified' => false
        )));
    }

}
