<?php
/**
 * Owner behavior, uses the user_id field of a table and allways set to
 * current user on create, and for find filter by current user. If we don't
 * want to filter we can set the option 'filter' to false.
 */
class OwnerBehavior extends ModelBehavior {

	/**
	 * Contructor of the owner behavior
	 * For options we can set:
	 * - 'filter': Set to false to avoid filter by current user on search
	 * @param  Model  $Model
	 * @param  array  $settings We can specify options here
	 */
	public function setup(Model $Model, $settings = array()) {
	    if (!isset($this->settings[$Model->alias])) {
	        $this->settings[$Model->alias] = array(
	            'filter' => true,
	            'user_model' => 'User'
	        );
	    }
	    $this->settings[$Model->alias] = array_merge(
	        $this->settings[$Model->alias], (array)$settings);
	}

	/**
	 * When save a record, if is created, set the field user_id to current
	 * user id.
	 * @param  Model  $model   Model to work
	 * @param  bool $created If the Model was created is set to true
	 * @param  array  $options We can specify options here
	 */
	public function afterSave(Model $model, $created, $options = array()) {
		if ($created) {
			$model->updateAll(
    			array($model->alias.'.user_id' => "'".AuthComponent::user('id')."'"),
    			array($model->alias.'.id' => $model->id)
			);
		}
	}

	/**
	 * Change the find query to filter by the owner of the record, if we don't
	 * want this filter we can change the options by setting the 'filter'
	 * property to false.
	 * @param  Model  $Model Model to work
	 * @param  array  $query Query to execute
	 * @return array        Query modified or not
	 */
	public function beforeFind(Model $Model, $query = array()) {
		// Link to user model
		$user_model = $this->settings[$Model->alias]['user_model'];
		$Model->bindModel(array('belongsTo' => array($user_model)));

		// Filter by owner
		if ($this->settings[$Model->alias]['filter']) {
			if ($query['conditions'] == null) $query['conditions'] = array();
			$query['conditions'][$Model->alias.'.user_id'] = AuthComponent::user('id');
			return $query;
		} else {
			return $query;
		}
	}

}
