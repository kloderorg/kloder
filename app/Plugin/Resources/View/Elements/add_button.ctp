<li><?php echo $this->Html->link('<i class="fa fa-folder fa-fw"></i> '.__d('resources', 'Folder'), array('controller' => 'resources_folders', 'action' => 'add'), array('escape' => false)) ?></li>
<li class="divider"></li>
<li><?php echo $this->Html->link('<i class="fa fa-file fa-fw"></i> '.__d('resources', 'File'), array('controller' => 'resources_files', 'action' => 'add', 'folder' => $folder_id), array('escape' => false)) ?></li>
<li><?php echo $this->Html->link('<i class="fa fa-link fa-fw"></i> '.__d('resources', 'Link'), array('controller' => 'resources_links', 'action' => 'add', 'folder' => $folder_id), array('escape' => false)) ?></li>

<li class="divider"></li>
