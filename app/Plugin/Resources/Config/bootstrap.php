<?php
$resources_templates = array('Resources.templates');
if(!Configure::check('Resources.templates')) Configure::write('Resources.templates', $resources_templates);
else Configure::write('Resources.templates', array_merge(Configure::read('Resources.templates'), $resources_templates));

$resources_extensions = array(
	'application/pdf' => 'pdf'
);
if(!Configure::check('Resources.extensions')) Configure::write('Resources.extensions', $resources_extensions);
else Configure::write('Resources.extensions', array_merge(Configure::read('Resources.extensions'), $resources_extensions));


/*$sidebar_plugins = array('Resources');
if(!Configure::check('Sidebar.plugins')) Configure::write('Sidebar.plugins', $sidebar_plugins);
else Configure::write('Sidebar.plugins', array_merge(Configure::read('Sidebar.plugins'), $sidebar_plugins));
*/










// Public paths
/*$allowPublicPaths = array(
	'/resources/resources_links/view',
	'/resources/resources/widget_carousel',
	'/resources/resources_links/feed',
);
if(Configure::read('Authake.allowPublicPaths') == null) Configure::write('Authake.allowPublicPaths', $allowPublicPaths);
else Configure::write('Authake.allowPublicPaths', array_merge(Configure::read('Authake.allowPublicPaths'), $allowPublicPaths));

// All Paths (paths for all login users)
$allowAllPaths = array(
	'/resources/resources/items_folder',
	'/resources/resources/items_search'
);
if(Configure::read('Authake.allowAllPaths') == null) Configure::write('Authake.allowAllPaths', $allowAllPaths);
else Configure::write('Authake.allowAllPaths', array_merge(Configure::read('Authake.allowAllPaths'), $allowAllPaths));


// ResourcesTypes
$resourcesTypes = array('ResourcesLink', 'ResourcesFile');
if(Configure::read('Resources.types') == null) Configure::write('Resources.types', $resourcesTypes);
else Configure::write('Resources.types', array_merge(Configure::read('Resources.types'), $resourcesTypes));

// ResourcesMenu
$resourcesMenu = array('Link' => 'resources/resources_links/add');
if(Configure::read('Resources.menu') == null) Configure::write('Resources.menu', $resourcesMenu);
else Configure::write('Resources.menu', array_merge(Configure::read('Resources.menu'), $resourcesMenu));

// ResourcesUploadFiles
$resourcesUploadFiles = array(
	__d('resources', 'All files') => 'html,xml,csv,sxi,pot,po,cfg,pct,sgf,scm,zip,gz,rar,bz,arj'
);
if(Configure::read('Resources.uploadFiles') == null) Configure::write('Resources.uploadFiles', $resourcesUploadFiles);
else Configure::write('Resources.uploadFiles', array_merge(Configure::read('Resources.uploadFiles'), $resourcesUploadFiles));

// ResourcesPlugins
$resourcesPlugins = array(
	'link' => array('plugin' => 'Resources', 'controller' => 'ResourcesLinks'),
	'file' => array('plugin' => 'Resources', 'controller' => 'ResourcesFiles')
);
if(Configure::read('Resources.plugins') == null) Configure::write('Resources.plugins', $resourcesPlugins);
else Configure::write('Resources.plugins', array_merge(Configure::read('Resources.plugins'), $resourcesPlugins));
?>
*/
