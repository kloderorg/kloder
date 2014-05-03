<?php
class ResourcesFolder extends ResourcesAppModel {

    var $name = 'ResourcesFolder';
	var $belongsTo = array('User');
	var $actsAs = array('Users.Owner');

}
?>
