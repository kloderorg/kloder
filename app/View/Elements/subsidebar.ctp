<?php $plugin = $this->request->params['plugin']; ?>
<li<?php if ($plugin == '') : ?> class="active"<?php endif; ?>>
	<?php echo $this->Html->link('<i class="fa fa-dashboard fa-fw"></i> '.__('Dashboard'), array('plugin' => '', 'controller' => 'dashboard', 'action' => 'index'), array('escape' => false)) ?>
</li>
