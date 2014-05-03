<?php $plugin = $this->request->params['plugin']; ?>
<li<?php if ($plugin == 'resources') : ?> class="active"<?php endif; ?>>
	<?php echo $this->Html->link('<i class="fa fa-folder fa-fw"></i> '.__d('resources', 'Resources'), array('plugin' => 'resources', 'controller' => 'resources', 'action' => 'index'), array('escape' => false)) ?>
</li>
