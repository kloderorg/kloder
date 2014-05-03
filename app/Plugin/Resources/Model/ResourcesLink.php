<?php
class ResourcesLink extends AppModel {

	var $belongsTo = array('User', 'ResourcesFolder');
	var $actsAs = array('Users.Taggable', 'Ratings.Ratable', 'Ratings.Rowaccess', 'Users.Owner');

}
?>
