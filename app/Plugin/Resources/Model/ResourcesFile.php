<?php
class ResourcesFile extends ResourcesAppModel {

    var $name = 'ResourcesFile';
	var $belongsTo = array('User', 'ResourcesFolder');
	//var $actsAs = array('Users.Taggable', 'Ratings.Ratable', 'Ratings.Rowaccess', 'Users.Owner');
	public $actsAs = array('Users.Owner', 'Resources.Upload' => array(
            'file' => array(
                'fields' => array(
                    'dir' => 'path'
                )
            )
        )
    );

}
?>
