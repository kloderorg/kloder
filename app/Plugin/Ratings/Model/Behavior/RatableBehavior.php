<?php
/**
 * Copyright 2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CakePHP Ratings Plugin
 *
 * Ratable behavior
 *
 * @package 	ratings
 * @subpackage 	ratings.models.behaviors
 */
class RatableBehavior extends ModelBehavior {

/**
 * Settings array
 *
 * @var array
 */
	public $settings = array();

/**
 * Default settings
 *
 * modelClass		- must be set in the case of a plugin model to make the behavior work with plugin models like 'Plugin.Model'
 * rateClass		- name of the rate class model
 * foreignKey		- foreign key field
 * saveToField		- boolean, true if the calculated result should be saved in the rated model
 * field 			- name of the field that is updated with the calculated rating
 * fieldSummary		- optional cache field that will store summary of all ratings that allow to implement quick rating calculation
 * fieldCounter		- optional cache field that will store count of all ratings that allow to implement quick rating calculation
 * calculation		- 'average' or 'sum', default is average
 * update			- boolean flag, that define permission to rerate(change previous rating)
 * modelValidate	- validate the model before save, default is false
 * modelCallbacks	- run model callbacks when the rating is saved to the model, default is false
 * allowedValues	- @todo
 *
 * @var array
 */
	protected $_defaults = array(
		'modelClass' => null,
		'rateClass' => 'Users.Rating',
		'foreignKey' => 'foreign_key',
		'field' => 'rating',
		'fieldSummary' => 'rating_sum',
		'fieldCounter' => 'rating_count',
		'calculation' => 'average',
		'saveToField' => true,
		'countRates' => false,
		'update' => true,
		'modelValidate' => false,
		'modelCallbacks' => false,
		'allowedValues' => array());

/**
 * Rating modes
 *
 * @var array
 */
	public $modes = array(
		'average' => 'avg',
		'sum' => 'sum');

/**
 * Setup
 *
 * @param AppModel $Model
 * @param array $settings
 * @return void
 */
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->_defaults;
		}
		$this->settings[$Model->alias] = am($this->settings[$Model->alias], (is_array($settings) ? $settings : array()));
		if (empty($this->settings[$Model->alias]['modelClass'])) {
			$this->settings[$Model->alias]['modelClass'] = $Model->name;
		}

		$Model->bindModel(
			array('hasMany' => array(
				'Rating' => array(
					'className' => $this->settings[$Model->alias]['rateClass'],
					'foreignKey' => $this->settings[$Model->alias]['foreignKey'],
					'unique' => true,
					'conditions' => '',
					'fields' => '',
					'dependent' => true))), false);

		$Model->Rating->bindModel(array(
			'belongsTo' => array(
				$Model->alias => array(
					'className' => $this->settings[$Model->alias]['modelClass'],
					'foreignKey' => 'foreign_key',
					'counterCache' => $this->settings[$Model->alias]['countRates']))), false);
	}

/**
 * Saves a new rating
 *
 * @param AppModel $Model
 * @param string $foreignKey
 * @param string $userId
 * @param numeric $value
 * @return mixed boolean or calculated sum
 */
	public function saveRating(Model $Model, $foreignKey = null, $userId = null, $value = 0) {
		$type = 'saveRating';
		$this->beforeRateCallback($Model, compact('foreignKey', 'userId', 'value', 'update', 'type'));
		if (!$this->isRatedBy($Model, $foreignKey, $userId) || $this->settings[$Model->alias]['update'] == true) {
			$data['Rating']['foreign_key'] = $foreignKey;
			$data['Rating']['model'] = $Model->alias;
			$data['Rating']['user_id'] = $userId;
			$data['Rating']['value'] = $value;
			$oldRating = null;
			$update = false;
			if ($this->settings[$Model->alias]['update'] == true) {
				$update = true;
				$oldRating = $this->oldRating = $Model->Rating->find('first', array(
					'recursive' => -1,
					'conditions' => array(
						'Rating.model' => $Model->alias,
						'Rating.foreign_key' => $foreignKey,
						'Rating.user_id' => $userId)));
				$Model->Rating->deleteAll(array(
					'Rating.model' => $Model->alias,
					'Rating.foreign_key' => $foreignKey,
					'Rating.user_id' => $userId));
			}

			$Model->Rating->create();
			if ($Model->Rating->save($data)) {
				if ($Model->getColumnType($this->settings[$Model->alias]['fieldCounter']) && $Model->getColumnType($this->settings[$Model->alias]['fieldSummary'])) {
					$result = $this->incrementRating($Model, $foreignKey, $value, $this->settings[$Model->alias]['saveToField'], $this->settings[$Model->alias]['calculation'], $update);
				} else {
					$result = $this->calculateRating($Model, $foreignKey, $this->settings[$Model->alias]['saveToField'], $this->settings[$Model->alias]['calculation']);
				}
				$this->afterRateCallback($Model, compact('foreignKey', 'userId', 'value', 'result', 'update', 'oldRating', 'type'));
				return $result;
			}
		}
		return false;
	}


/**
 * Remove exists rating
 *
 * @param AppModel $Model
 * @param string $foreignKey
 * @param string $userId
 * @param numeric $value
 * @return mixed boolean or calculated sum
 */
	public function removeRating(Model $Model, $foreignKey = null, $userId = null) {
		$type = 'removeRating';
		$this->beforeRateCallback($Model, compact('foreignKey', 'userId', 'update', 'type'));
		if ($this->isRatedBy($Model, $foreignKey, $userId)) {
			$data['Rating']['foreign_key'] = $foreignKey;
			$data['Rating']['model'] = $Model->alias;
			$data['Rating']['user_id'] = $userId;
			$oldRating = null;
			$update = true;
			$oldRating = $this->oldRating = $Model->Rating->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'Rating.model' => $Model->alias,
					'Rating.foreign_key' => $foreignKey,
					'Rating.user_id' => $userId)));
			$Model->Rating->deleteAll(array(
				'Rating.model' => $Model->alias,
				'Rating.foreign_key' => $foreignKey,
				'Rating.user_id' => $userId));

			if ($Model->getColumnType($this->settings[$Model->alias]['fieldCounter']) && $Model->getColumnType($this->settings[$Model->alias]['fieldSummary'])) {
				$result = $this->decrementRating($Model, $foreignKey, $oldRating['Rating']['value'], $this->settings[$Model->alias]['saveToField'], $this->settings[$Model->alias]['calculation'], $update);
			} else {
				$result = $this->calculateRating($Model, $foreignKey, $this->settings[$Model->alias]['saveToField'], $this->settings[$Model->alias]['calculation']);
			}
			$this->afterRateCallback($Model, compact('foreignKey', 'userId', 'result', 'update', 'oldRating', 'type'));
			return $result;
		}
		return false;
	}

/**
 * Increments/decrements the rating
 *
 * See also Ratable::calculateRating() and decide which one suits your needs better
 *
 * @see Ratable::calculateRating()
 * @param AppModel $Model
 * @param string $foreignKey
 * @param integer $value of new rating
 * @param mixed $saveToField boolean or fieldname
 * @param string $mode type of calculation
 * @return mixed boolean or calculated sum
 */
	public function decrementRating(Model $Model, $foreignKey = null, $oldRating, $saveToField = true, $mode = 'average', $update = false) {
		if (!in_array($mode, array_keys($this->modes))) {
			throw new InvalidArgumentException(sprintf(__d('platform', 'Invalid rating mode %s.', true),$mode));
		}

		$data = $Model->find('first', array(
			'conditions' => array(
				$Model->alias . '.' . $Model->primaryKey => $foreignKey),
			'recursive' => -1));

		$fieldSummary = $this->settings[$Model->alias]['fieldSummary'];
		$fieldCounter = $this->settings[$Model->alias]['fieldCounter'];

		$ratingSumNew = $data[$Model->alias][$fieldSummary] - $oldRating;
		$ratingCountNew = $data[$Model->alias][$fieldCounter] - 1;

		if ($mode == 'average') {
			if ($ratingCountNew == 0) {
				$rating = 0;
			} else {
				$rating = $ratingSumNew / $ratingCountNew;
			}
		} else {
			$rating = $ratingSumNew;
		}

		if ($saveToField || is_string($saveToField)) {
			$save = array();
			if (is_string($saveToField)) {
				$save[$Model->alias][$saveToField] = $rating;
			} else {
				$save[$Model->alias][$this->settings[$Model->alias]['field']] = $rating;
			}
			$save[$Model->alias][$fieldSummary] = $ratingSumNew;
			$save[$Model->alias][$fieldCounter] = $ratingCountNew;
			$save[$Model->alias][$Model->primaryKey] = $foreignKey;

			return $Model->save($save, array(
				'validate' => $this->settings[$Model->alias]['modelValidate'],
				'callbacks' => $this->settings[$Model->alias]['modelCallbacks']));
		}
		return $rating;
	}

/**
 * Increments/decrements the rating
 *
 * See also Ratable::calculateRating() and decide which one suits your needs better
 *
 * @see Ratable::calculateRating()
 * @param AppModel $Model
 * @param string $foreignKey
 * @param integer $value of new rating
 * @param mixed $saveToField boolean or fieldname
 * @param string $mode type of calculation
 * @return mixed boolean or calculated sum
 */
	public function incrementRating(Model $Model, $foreignKey = null, $value, $saveToField = true, $mode = 'average', $update = false) {
		if (!in_array($mode, array_keys($this->modes))) {
			throw new InvalidArgumentException(sprintf(__d('platform', 'Invalid rating mode %s.', true),$mode));
		}

		$data = $Model->find('first', array(
			'conditions' => array(
				$Model->alias . '.' . $Model->primaryKey => $foreignKey),
			'recursive' => -1));

		$fieldSummary = $this->settings[$Model->alias]['fieldSummary'];
		$fieldCounter = $this->settings[$Model->alias]['fieldCounter'];

 		if ($update == true && !empty($this->oldRating)) {
			$ratingSumNew = $data[$Model->alias][$fieldSummary] - $this->oldRating['Rating']['value'] + $value;
			$ratingCountNew = $data[$Model->alias][$fieldCounter];
		} else {
			$ratingSumNew = $data[$Model->alias][$fieldSummary] + $value;
			$ratingCountNew = $data[$Model->alias][$fieldCounter] + 1;
		}

		if ($mode == 'average') {
			$rating = $ratingSumNew / $ratingCountNew;
		} else {
			$rating = $ratingSumNew;
		}
		$Model->newRating = $rating;

		if ($saveToField || is_string($saveToField)) {
			$save = array();
			if (is_string($saveToField)) {
				$save[$Model->alias][$saveToField] = $rating;
			} else {
				$save[$Model->alias][$this->settings[$Model->alias]['field']] = $rating;
			}
			$save[$Model->alias][$fieldSummary] = $ratingSumNew;
			$save[$Model->alias][$fieldCounter] = $ratingCountNew;
			$save[$Model->alias][$Model->primaryKey] = $foreignKey;

			return $Model->save($save, array(
				'validate' => $this->settings[$Model->alias]['modelValidate'],
				'callbacks' => $this->settings[$Model->alias]['modelCallbacks']));
		}
		return $rating;
	}

/**
 * Calculates the rating
 *
 * This method does always a calculation of the the values based on SQL AVG()
 * and SUM(). Please note that this is relativly slow compared to incrementing
 * the values, see Ratable::incrementRating()
 *
 *
 *
 * @param AppModel $Model
 * @param string $foreignKey
 * @param mixed $saveToField boolean or fieldname
 * @param string $mode type of calculation
 * @return mixed boolean or calculated sum
 */
	public function calculateRating(Model $Model, $foreignKey = null, $saveToField = true, $mode = 'average') {
		if (!in_array($mode, array_keys($this->modes))) {
			throw new InvalidArgumentException(sprintf(__d('platform', 'Invalid rating mode %s.', true),$mode));
		}

		$result = $Model->Rating->find('first', array(
			'conditions' => array('Rating.foreign_key' => $foreignKey, 'Rating.model' => $Model->alias),
			'fields' => array($this->modes[$mode] . '(Rating.value) AS rating')
		));
		if (empty($result[0]['rating'])) $result[0]['rating'] = 0;

		$Model->newRating = $result[0]['rating'];
		if ($saveToField || is_string($saveToField)) {
			if (is_string($saveToField)) {
				$data[$Model->alias][$saveToField] = $result[0]['rating'];
			} else {
				$data[$Model->alias][$this->settings[$Model->alias]['field']] = $result[0]['rating'];
			}
			$data[$Model->alias][$Model->primaryKey] = $foreignKey;
			return $Model->save($data, array(
				'validate' => $this->settings[$Model->alias]['modelValidate'],
				'callbacks' => $this->settings[$Model->alias]['modelCallbacks']));
		}
		return $result[0]['rating'];
	}

/**
 * Method to check if an entry is rated by a certain user
 *
 * @param AppModel $Model
 * @param mixed Single foreign key as uuid or int or array of foreign keys
 * @param mixed Boolean true or false if a single foreign key was supplied else an array of already voted keys
 * @return boolean true if already rated
 */
	public function isRatedBy(Model $Model, $foreignKey = null, $userId = null) {
		$findMethod = 'first';
		if (is_array($foreignKey)) {
			$findMethod = 'all';
		}

		$entry = $Model->Rating->find($findMethod, array(
			'recursive'-1,
			'conditions' => array(
				'Rating.foreign_key' => $foreignKey,
				'Rating.user_id' => $userId,
				'Rating.model' => $Model->alias)));

		if ($findMethod == 'all') {
			return Set::extract($entry, '{n}.Rating.foreign_key');
		}

		if (empty($entry)) {
			return false;
		}
		return true;
	}

/**
 * afterRate callback to the model
 *
 * @param AppModel $Model
 * @param array
 * @return void
 */
	public function afterRateCallback(Model $Model, $data = array()) {
		if (method_exists($Model, 'afterRate')) {
			$Model->afterRate($data);
		}
	}

/**
 * beforeRate callback to the model
 *
 * @param AppModel $Model
 * @param array
 * @return void
 */
	public function beforeRateCallback(Model $Model, $data = array()) {
		if (method_exists($Model, 'beforeRate')) {
			$Model->beforeRate($data);
		}
	}

/**
 * More intelligent version of saveRating - checks record existance and ratings
 *
 * @param AppModel $Model
 * @param string model primary key / id
 * @param mixed user id integer or string uuid
 * @param mixed integer or string rating
 * @param array options
 * @param return boolean True on success
 */
	public function rate(Model $Model, $foreignKey = null, $userId = null, $rating = null, $options = array()) {
		$defaults = array(
			'userField' => 'user_id',
			'find' => array(
				'contain' => array(),
				'conditions' => array(
					$Model->alias . '.' . $Model->primaryKey => $foreignKey)),
			'values' => array(
				'up' => 1, 'down' => -1));
		$options = array_merge($defaults, $options);

		if (!in_array($rating, array_keys($options['values']))) {
			throw new OutOfBoundsException(__d('platform', 'Invalid Rating', true));
		}

		$record = $Model->find('first', $options['find']);

		if (empty($record)) {
			throw new OutOfBoundsException(__d('platform', 'Invalid Record', true));
		}

		if ($options['userField'] !== false && $Model->getColumnType($options['userField'])) {
			if ($record[$Model->alias][$options['userField']] == $userId) {
				$Model->data = $record;
				throw new LogicException(__d('platform', 'You can not vote on your own records', true));
			}
		}

		if ($Model->saveRating($foreignKey, $userId, $options['values'][$rating])) {
			$Model->data = $record;
			return true;
		} else {
			throw new Exception(__d('platform', 'You have already rated this record', true));
		}
	}

/**
 * Caches the sum of the different ratings for each of them
 *
 * For example a rating of 1 will increase the value in the field "rating_1" by 1,
 * a rating of 2 will increase "rating_2" by one...
 *
 * @param object Model
 * @param array Data passed to afterRate() or similar structure
 * @return boolean True on success
 */
	public function cacheRatingStatistics(Model $Model, $data = array()) {
		extract($data);
		if ($result) {
			if ($type == 'removeRating') {
				$value = $oldRating['Rating']['value'];
			}
			if ($Model->getColumnType($this->_fieldName(round($value, 0)))) {
				$data = $Model->find('first', array(
					'conditions' => array(
						$Model->alias . '.' . $Model->primaryKey => $foreignKey),
					'recursive' => -1));

				if (($update == true || $type == 'removeRating') && !empty($oldRating['Rating'])) {
					$oldId = round($oldRating['Rating']['value']);
					$data[$Model->alias][$this->_fieldName($oldId)] -= 1;
				}
				if ($type == 'saveRating') {
					$newId = round($value);
					$data[$Model->alias][$this->_fieldName($newId)] += 1;
				}
				return $Model->save($data, array(
					'validate' => $this->settings[$Model->alias]['modelValidate'],
					'callbacks' => $this->settings[$Model->alias]['modelCallbacks']));
			}
		}
	}

/**
 * Return field name for cache value
 *
 * @param string $value
 * @param string $prefix
 * @return string
 */
	protected function _fieldName($value, $prefix = 'rating_') {
		$postfix = $value;
		if ($value < 0) {
			$postfix = 'neg' . abs($value);
		}
		return $prefix . $postfix;
	}
}
