<?php
App::uses('Component', 'Controller');
class DomainComponent extends Component {

	//public $components = array('Session');

	public $defaults = array(
		'permanent' => array(
			'plugin' => 'Users',
			'model' => 'UsersSetting',
			'group' => ''
		),
		'domain' => array(
			'model' => '',
			'plugin' => '',
            'create' => array(),
            'empty' => array()
		)
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
		$this->settings = array_merge($this->defaults, $this->settings);
	}

    public function initialize(Controller $controller) {
        if (AuthComponent::user('id') == null) return;
    	$model_domain_alias = $this->settings['domain']['model'];
    	App::uses($model_domain_alias, $this->settings['domain']['plugin'].'.Model');
    	$model_domain = new $model_domain_alias();
        $results = $model_domain->find('all');

    	// Get id from user key/value table
        $model_permanent_alias = $this->settings['permanent']['model'];
        App::uses($model_permanent_alias, $this->settings['permanent']['plugin'].'.Model');
        $model_permanent = new $model_permanent_alias();

    	$model_id = $model_permanent->getValue(
    		Inflector::underscore($model_domain_alias).'_id',
    		$this->settings['permanent']['group']
    	);

    	// If not exists we use first row id
    	if ($model_id == false) {
            if (count($results) > 0) {
        		$model_id = $results[0][$model_domain_alias]['id'];
        		$model_permanent->setValue(
    	    		Inflector::underscore($model_domain_alias).'_id',
    	    		$model_id,
    	    		$this->settings['permanent']['group']
    	    	);
            } else {
                if (!$this->isIn($controller, $this->settings['domain']['empty']) && !$this->isIn($controller, $this->settings['domain']['create'])) {
                    $controller->redirect($this->settings['domain']['empty']);
                }
            }
    	}

		// Set results for all controllers and views
		$selected = null;
    	foreach ($results as $result) {
    		if ($result[$model_domain_alias]['id'] == $model_id) {
    			$selected = $result;
    			break;
    		}
    	}
    	Configure::write($model_domain_alias, array(
    		'selected' => $selected,
    		'list' => $results
    	));
	}

    public function isIn($controller, $request = array()) {
        if (array_key_exists('plugin', $request) && $controller->request->params['plugin'] != $request['plugin']) return false;
        if (array_key_exists('controller', $request) && $controller->request->params['controller'] != $request['controller']) return false;
        if (array_key_exists('action', $request) && $controller->request->params['action'] != $request['action']) return false;
        return true;
    }

	public function change(Controller $controller, $value) {
        $model_permanent_alias = $this->settings['permanent']['model'];
        App::uses($model_permanent_alias, $this->settings['permanent']['plugin'].'.Model');
        $model_permanent = new $model_permanent_alias();

        $model_domain_alias = $this->settings['domain']['model'];

    	$model_permanent->setValue(
    		Inflector::underscore($model_domain_alias).'_id',
    		$value,
    		$this->settings['permanent']['group']
    	);
	}

}
