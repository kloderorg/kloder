<?php
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
class LanguageExtractorComponent extends Component {

	function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
    }

	function read_from_source() {

		$controller->loadModel('MenuItem');
		$controller->MenuItem->Behaviors->attach('Tree');
		$menus = $controller->MenuItem->find('threaded', array('order' => 'MenuItem.lft ASC'));

		$this->menus = $this->__menu_recursive($menus);
		// debug($this->menus); die();
	}

	function __menu_recursive($menus) {
		$menu = array();
		foreach ($menus as $item) {
			if ($this->_check_permissions($item)) {
				if (array_key_exists('children', $item)) $item['children'] = $this->__menu_recursive($item['children']);
				array_push($menu, $item);
			}
		}
		return $menu;
	}

	function _check_permissions($item) {
		return $this->Authake->isAllowed('/'.$item['MenuItem']['ubication']);
	}

	function beforeRender($controller) {
		$controller->set('menus', $this->menus);
	}
}
?>
