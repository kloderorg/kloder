<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('resources', 'Add') ?> <small><?php echo __d('resources', 'Folders') ?></small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-folder"></i> '.__d('resources', 'Resources'), array('controller' => 'resources', 'action' => 'index'), array('escape' => false)) ?></li>
			<li><?php echo $this->Html->link(__d('resources', 'Folders'), array('controller' => 'resources_folders', 'action' => 'index')) ?></li>
			<li class="active"><?php echo __d('resources', 'Add') ?></li>
		</ol>
	</div>
</div>

<?php echo $this->Form->create('ResourcesFolder'); ?>

<?php echo $this->Form->input('name', array('placeholder' => __d('resources',  'Name'), 'autofocus' => 'autofocus')) ?>

<div class="form-actions">
    <?php echo $this->Form->submit(__d('resources',  'Save'), array('class' => 'btn btn-primary', 'div' => false)) ?>
    <?php echo $this->Html->link(__d('resources', 'Cancel', true), 'javascript:window.history.back();', array('class' => 'btn')); ?>
</div>

<?php echo $this->Form->end() ?>
