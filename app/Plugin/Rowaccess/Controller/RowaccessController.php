<?php
class RowaccessController extends RowaccessAppController {

    public $uses = array();

	public function index($model = null, $plugin = null, $id = null) {
        if ($plugin == null) $this->loadModel($model);
        else $this->loadModel(ucfirst($plugin).'.'.$model);

        $this->{$model}->recursive = 2;
        $item = $this->{$model}->findById($id);
        $this->set(compact('item'));
	}

	public function add($rowaccess_model = null, $model = null, $plugin = null) {
		if ($plugin == null) $this->loadModel($rowaccess_model);
        else $this->loadModel(ucfirst($plugin).'.'.$rowaccess_model);

        $this->{$rowaccess_model}->create();
        $this->{$rowaccess_model}->save(array($rowaccess_model => array(
        	Inflector::underscore($model).'_id' => $this->request->data['model_id'],
        	'user_id' => $this->request->data['user_id'],
        	'can' => $this->request->data['can']
        )));

        $item = $this->{$rowaccess_model}->findById($this->{$rowaccess_model}->id);
        $this->set(compact('item'));
	}

	public function change($model = null, $plugin = null) {
		if ($plugin == null) $this->loadModel($model);
        else $this->loadModel(ucfirst($plugin).'.'.$model);

		$this->{$model}->updateAll(
			array($model.'.can' => $this->request->data['can']),
			array($model.'.id' => $this->request->data['id'])
        );

		$this->set('result', array(
			'status' => 'success',
    		'data' => array(
        		$model => array(
        			'id' => $this->request->data['id']
        		)
			)
		));
	}

	public function delete($model = null, $plugin = null) {
		if ($plugin == null) $this->loadModel($model);
        else $this->loadModel(ucfirst($plugin).'.'.$model);

		$this->{$model}->delete($this->request->data['id']);

		$this->set('result', array(
			'status' => 'success',
    		'data' => array()
		));
	}
}
