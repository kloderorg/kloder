<?php
App::uses('Controller', 'Controller');
class AppController extends Controller {

	public $layout = 'main';
	public $helpers = array(
		'Form' => array('className' => 'Kloder.KForm'),
		'Html' => array('className' => 'Kloder.KHtml'),
        'Paginator' => array('className' => 'Kloder.KPaginator'),
        'Session'
	);
	public $components = array(
        'Session',
        'RequestHandler',
        'Auth' => array(
        	'loginAction' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'),
            'loginRedirect' => array('plugin' => 'kloder', 'controller' => 'dashboard', 'action' => 'index'),
            'logoutRedirect' => array('plugin' => '', 'controller' => 'pages', 'action' => 'home')
        ),
        'Languages.LanguageInitiator',
        'Notices.Notices',
        'DebugKit.Toolbar',
        'Users.LastAccess'
    );

    public function beforeFilter() {
        $this->Auth->flash['element'] = 'messages/error';
    }

}
