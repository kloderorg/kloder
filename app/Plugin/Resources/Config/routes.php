<?php
Router::connect('/resources/download/*', array('plugin'=>'resources', 'controller' => 'resources_files', 'action' => 'download'));
Router::connect('/resources/get/*', array('plugin'=>'resources', 'controller' => 'resources_files', 'action' => 'get'));
?>