<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('users', 'Edit') ?> <small><?php echo __d('users', 'Groups') ?></small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-user"></i> '.__d('users', 'Users'), array('controller' => 'users', 'action' => 'index'), array('escape' => false)) ?></li>
			<li><?php echo $this->Html->link('<i class="fa fa-users"></i> '.__d('users', 'Groups'), array('action' => 'index'), array('escape' => false)) ?></li>
			<li class="active"><?php echo __d('users', 'Edit') ?></li>
		</ol>
	</div>
</div>

<?php echo $this->Form->create('UsersGroup') ?>

	<?php echo $this->Form->input('id') ?>

	<?php echo $this->Form->input('name', array('placeholder' => __d('users', 'Name'), 'required', 'autofocus')) ?>

	<?php echo $this->Form->submit(__d('users', 'Save'), array('class' => 'btn btn-large btn-primary')) ?>

<?php echo $this->Form->end() ?>
