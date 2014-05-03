<?php
/* Resources Test cases generated on: 2011-04-01 15:04:55 : 1301671855*/
App::import('Controller', 'Resources.Resources');

class TestResourcesController extends ResourcesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ResourcesControllerTestCase extends CakeTestCase {
	function startTest() {
		$this->Resources =& new TestResourcesController();
		$this->Resources->constructClasses();
	}

	function endTest() {
		unset($this->Resources);
		ClassRegistry::flush();
	}

}
?>