<?php
class LogsController extends KloderAppController {

	public $paginate = array(
        'limit' => 50,
        'order' => array(
            'Log.created' => 'desc'
        )
    );

	function index() {
		$this->Log->bindModel(array('belongsTo' => array('User' => array('className' => 'User'))));
		$logs = $this->paginate('Log');
		$this->set(compact('logs'));
	}

	function user($user_id) {
		$this->Log->bindModel(array('belongsTo' => array('User' => array('className' => 'User'))));
		$logs = $this->paginate('Log', array('Log.user_id' => $user_id));
		$this->set(compact('logs'));

		$this->loadModel('User');
		$this->User->id = $user_id;
		$this->set('user', $this->User->read());
	}

	/**
	 * Widget groups
	 * Show last logs from a group or groups
	 * @param groups_ids Array of groups
	 * @param limit Number of logs for show
	 */
	function widget_groups($groups_ids = "", $limit = 25) {

	}

	function widget_all($limit = 25) {
		$this->Log->bindModel(array('belongsTo' => array('User' => array('className' => 'User'))));
		$logs = $this->Log->find('all', array('limit', $limit));
		$this->set(compact('logs'));
	}

}
?>
