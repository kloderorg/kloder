<?php
class CommentableBehavior extends ModelBehavior {

	public function setup(Model $Model, $settings = array()) {
	    if (!isset($this->settings[$Model->alias])) {
	        $this->settings[$Model->alias] = array(
	            'comment_model' => null,
	            'user_model' => 'User'
	        );
	    }
	    $this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
	}

	public function beforeFind(Model $model, $query) {
		$comment_model = $this->getCommentModel($model);
		if (isset($this->settings[$model->alias]['comment_model'])) $comment_model = $this->settings[$model->alias]['comment_model'];

		$model->bindModel(array('hasMany' => array($comment_model)));
		return $query;
	}

	public function afterFind(Model $model, $results, $primary = false) {
		$comment_model = $this->getCommentModel($model);
		if (isset($this->settings[$model->alias]['comment_model'])) $comment_model = $this->settings[$model->alias]['comment_model'];

		// In case one only result: get more info about comments
		if (count($results) == 1) {
			foreach ($results as $i => $result) {
				if (array_key_exists($comment_model, $result)) {
					foreach ($result[$comment_model] as $j => $result2) {
						$model->{$comment_model}->unbindModel(array('belongsTo' => array($model->alias)));
						$model->{$comment_model}->bindModel(array('belongsTo' => array($this->settings[$model->alias]['user_model'])));
						$comment = $model->{$comment_model}->findById($result2['id']);
						$results[$i][$comment_model][$j] = $comment;
					}
				}
			}
		}

		return $results;
	}

	public function getCommentModel($model) {
		return Inflector::pluralize($model->alias).'Comment';
	}

}
