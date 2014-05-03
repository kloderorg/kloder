<?php
Router::connect('/login', array('plugin'=>'users', 'controller' => 'users', 'action' => 'login'));
Router::connect('/logout', array('plugin'=>'users', 'controller' => 'users', 'action' => 'logout'));
Router::connect('/lost_password', array('plugin'=>'users', 'controller' => 'users', 'action' => 'lost_password'));
