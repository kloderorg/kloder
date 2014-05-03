<?php $plugin = $this->request->params['plugin']; ?>
<li<?php if ($plugin == 'kloder') : ?> class="active"<?php endif; ?>>
	<?php echo $this->Html->link('<i class="fa fa-dashboard fa-fw"></i> '.__d('kloder', 'Dashboard'), array('plugin' => 'kloder', 'controller' => 'dashboard', 'action' => 'index'), array('escape' => false)) ?>
</li>
