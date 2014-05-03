<?php
class Notice extends NoticesAppModel {

    var $name = 'Notice';
	var $actsAs = array('Users.Owner');

}
?>
