<?php
class ResourcesController extends ResourcesAppController {

	var $name = 'Resources';

	//var $components = array('Resources.RemoteThumb');
    //var $helpers = array('Webshot', 'Time', 'Text', 'Phpthumb', 'Resources.Icarus');
	public $uses = array();

	public function index($folder_id = null) {
		$this->set(compact('folder_id'));
	}

	public function trash() {

	}

}
