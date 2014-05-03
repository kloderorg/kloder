<?php
class KeyValueBehavior extends ModelBehavior {
	
	public function findAllKeyValues(Model $model, $group = '', $user_id = 0) {
		if ($user_id == 0) $user_id = AuthComponent::user('id');

		$conditions = array('user_id' => $user_id);
		if (!empty($group)) $conditions['group'] = $group;

		$results = $model->find('all', array('conditions' => $conditions));
		return $this->_parseResults($model, $results);
	}

	private function _parseResults(Model $model, $results) {
		$filter_results = array();
		foreach ($results as $result)
			$filter_results[$result[$model->alias]['key']] = $result[$model->alias]['value'];
		return $filter_results;
	}

	public function setValue(Model $model, $key = '', $value = '', $group = '', $user_id = 0) {
		if ($user_id == 0) $user_id = AuthComponent::user('id');
		$row = $model->find('first', array('conditions' => array(
    		'key' => $key,
    		'group' => $group,
    		'user_id' => $user_id
    	)));
    	if (empty($row)) {
    		$model->create();
    		$model->save(array($model->alias => array(
    			'key' => $key,
    			'value' => $value,
    			'group' => $group,
    			'user_id' => $user_id
    		)));
    	} else {
    		$model->save(array($model->alias => array(
    			'id' => $row[$model->alias]['id'],
    			'key' => $key,
    			'value' => $value,
    			'group' => $group,
    			'user_id' => $user_id
    		)));
    	}
	}

	public function getValue(Model $model, $key = '', $group = '', $user_id = 0) {
		if ($user_id == 0) $user_id = AuthComponent::user('id');
		$value = $model->field('value', array('key' => $key, 'group' => $group, 'user_id' => $user_id), 'key DESC');
    	return $value;
	}

}
