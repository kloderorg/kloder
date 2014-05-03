<?php
App::uses('MenusAppModel', 'Menus.Model');
class MenuItem extends MenusAppModel {

	var $name = 'MenuItem';
	var $actsAs = array('Tree');
	var $order = 'MenuItem.lft ASC';

}
?>
