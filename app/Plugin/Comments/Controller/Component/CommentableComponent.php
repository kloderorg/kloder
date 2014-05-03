<?php
App::uses('Component', 'Controller');
class CommentableComponent extends Component {

	public $components = array('Session');

	public $defaults = array(
		'model' => null,
        'comment_model' => null,
        'update_model' => true,
        'redirect' => array(
            'action' => 'view',
            'id_field' => 'foreign_key'
        )
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
		$this->settings = array_merge($this->defaults, $this->settings);
	}

    public function initialize(Controller $controller) {
        if (AuthComponent::user('id') == null) return;
        if ($this->settings['model'] == null) $this->settings['model'] = Inflector::singularize($controller->name);

        if ($controller->request->params['action'] == 'comment') {
            $this->add($controller, $controller->request->data[$this->settings['model']]['foreign_key'], $controller->request->data[$this->settings['model']]['content']);
            if ($this->settings['update_model']) $this->updateModel($controller, $controller->request->data[$this->settings['model']]['foreign_key']);
            $controller->redirect(array('action' => $this->settings['redirect']['action'], $controller->request->data[$this->settings['model']][$this->settings['redirect']['id_field']]));
        }
	}

    public function add(Controller $controller, $model_id = null, $content = '') {
        $comment_model_alias = $this->getCommentModel($this->settings['model']);
        if (isset($this->settings['comment_model'])) $comment_model_alias = $this->settings['comment_model'];

        $controller->loadModel($comment_model_alias);
        $controller->{$comment_model_alias}->create();
        $controller->{$comment_model_alias}->save(array($comment_model_alias => array(
            Inflector::underscore($this->settings['model']).'_id' => $model_id,
            'user_id' => AuthComponent::user('id'),
            'content' => $content
        )));
    }

    public function updateModel(Controller $controller, $model_id) {
        $model_alias = $this->settings['model'];
        $controller->{$model_alias}->updateAll(
            array($model_alias.'.modified' => "'".date('Y-m-d H:i:s')."'"),
            array($model_alias.'.id' => $model_id)
        );
    }

    public function getCommentModel($model) {
        return Inflector::pluralize($model).'Comment';
    }

}
