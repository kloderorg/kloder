<?php
class RowaccessBehavior extends ModelBehavior {

	public function setup(Model $Model, $settings = array()) {
	    if (!isset($this->settings[$Model->alias])) {
	        $this->settings[$Model->alias] = array(
	            'table' => 'projects_rowaccesses',
	            'user_model' => 'User',
	            'rowaccess' => array(
	            	'plugin' => 'Rowaccess',
	            	'model' => '',
	            	'foreign_key' => ''
	            ),
	            'users' => array(
	            	'plugin' => 'Users',
	            	'model' => 'User',
	            	'foreign_key' => 'user_id'
	            )
	        );
	    }
	    $this->settings[$Model->alias] = array_replace_recursive($this->settings[$Model->alias], (array)$settings);
	}

	public function afterSave(Model $model, $created, $options = array()) {
		$users_foreign_key = $this->settings[$Model->alias]['users']['foreign_key'];
		if ($created) {
			$model->updateAll(
    			array($model->alias.'.'.$users_foreign_key => AuthComponent::user('id')),
    			array($model->alias.'.id' => $model->id)
			);
		}
	}

	public function getUsers(Model $Model, $model_id) {
		$rowaccess_model_alias = $this->settings[$Model->alias]['rowaccess']['model'];
		$rowaccess_plugin = $this->settings[$Model->alias]['rowaccess']['plugin'];
		$rowaccess_foreign_key = $this->settings[$Model->alias]['rowaccess']['foreign_key'];

		$users_model_alias = $this->settings[$Model->alias]['users']['model'];
		$users_plugin = $this->settings[$Model->alias]['users']['plugin'];
		$users_foreign_key = $this->settings[$Model->alias]['users']['foreign_key'];

    	App::uses($rowaccess_model_alias, 'Model');
    	$model_rowaccess = new $rowaccess_model_alias();
        $results = $model_rowaccess->find('all', array('conditions' => array(
        	$rowaccess_model_alias.'.'.$rowaccess_foreign_key => $model_id
        )));
        $ids = Set::classicExtract($results, '{n}.'.$users_model_alias.'.id');

        $result = $Model->findById($model_id);
        array_push($ids, $result[$Model->alias][$users_foreign_key]);

        App::uses($users_model_alias, 'Users.Model');
        $user_model = new $users_model_alias();
        $results = $user_model->find('all', array('conditions' => array($users_model_alias.'.id' => $ids)));

        return $results;
	}

	public function getUsersList(Model $Model, $model_id) {
		$users = $this->getUsers($Model, $model_id);
		$results = array();
		foreach ($users as $user) $results[$user['User']['id']] = $user['User']['name'].' '.$user['User']['last_name'];
		return $results;
	}

	public function beforeFind(Model $Model, $query = array()) {
		if ($query['conditions'] == null) $query['conditions'] = array();
		$rowaccess_model_alias = $this->settings[$Model->alias]['rowaccess']['model'];
		$rowaccess_foreign_key = $this->settings[$Model->alias]['rowaccess']['foreign_key'];
		$users_model_alias = $this->settings[$Model->alias]['users']['model'];

		// Remove Owner behavior if exists
		if (array_key_exists('user_id', $query['conditions'])) unset($query['conditions']['user_id']);

		if (!array_key_exists('OR', $query['conditions'])) $query['conditions']['OR'] = array();
		$query['conditions']['OR'] = array_merge($query['conditions']['OR'], array(
			// Owner behavior
			$Model->alias.'.user_id = \''.AuthComponent::user('id').'\'',
			// TODO: Public access
			//$rowaccess_model_alias.'.all = 1 AND '.$rowaccess_model_alias.'.find = 1',
			// Personal access
			$rowaccess_model_alias.'.user_id = \''.AuthComponent::user('id').'\''
		));

		// Add joins
		$query['joins'] = array(
			array(
				'table' => Inflector::tableize($rowaccess_model_alias),
	    		'alias' => $rowaccess_model_alias,
	    		'type' => 'LEFT',
	    		'conditions' => $rowaccess_model_alias.'.'.$rowaccess_foreign_key.'='.$Model->alias.'.id'
			)
		);

		$query['group'] = array($Model->alias.'.id');

		// Link to user model
		$Model->bindModel(array('belongsTo' => array($users_model_alias)));

		//debug($query);
		return $query;
	}

}
