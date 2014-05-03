<?php
App::uses('UsersAppModel', 'Users.Model');
class UsersSetting extends UsersAppModel {

    public $actsAs = array('Users.KeyValue');

}
