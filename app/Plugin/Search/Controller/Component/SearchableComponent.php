<?php
App::uses('Component', 'Controller');
class SearchableComponent extends Component {

    public $model = null;
    public $key = 'search';
    public $redirect_url = array('action' => 'index');

    public function initialize(Controller $controller) {
        $this->controller = $controller;

        if (array_key_exists($this->model, $this->controller->request->data) && array_key_exists($this->key, $this->controller->request->data[$this->model])) {
            $url = $this->redirect_url;
            if ($this->controller->request->data[$this->model][$this->key] != '') {
                $url = array_merge($url, array($this->key => $this->controller->request->data[$this->model][$this->key]));
            }
            $this->controller->redirect($url);
        }
    }

    public function getConditions($fields = array()) {
        $conditions = array();

        if (array_key_exists($this->key, $this->controller->request->params['named'])) {
            $list = array();
            foreach ($fields as $field) {
                array_push($list, array($this->model.'.'.$field.' LIKE' => "%".urldecode($this->controller->request->params['named'][$this->key])."%"));
            }
            $conditions += array("OR" => $list);
            $this->controller->set($this->key, $this->controller->request->params['named'][$this->key]);
        }

        return $conditions;
    }

}
