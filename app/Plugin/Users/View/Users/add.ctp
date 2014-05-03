<div class="row">
	<div class="col-lg-12">
		<h1>Users <small>Add</small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-users"></i> '.__d('users', 'Users'), array('plugin' => 'users', 'controller' => 'users', 'action' => 'index'), array('escape' => false)) ?></li>
			<li><?php echo $this->Html->link(__d('users', 'Add'), array('plugin' => 'users', 'controller' => 'users', 'action' => 'add')) ?></li>
		</ol>
	</div>
</div>

<?php echo $this->Form->create('User') ?>

	<?php echo $this->Form->input('email', array('placeholder' => __d('users', 'Email address'), 'required', 'autofocus')) ?>
	<div class="row">
		<div class="col-lg-4"><?php echo $this->Form->input('username', array('placeholder' => __d('users', 'Username'), 'required', 'after' => '<p class="help-block">Must have between 3 and 15 digits</p>', 'autocomplete' => 'off')) ?></div>
		<div class="col-lg-4"><?php echo $this->Form->input('password', array('type' => 'password', 'placeholder' => __d('users', 'Password'), 'required', 'after' => '<p class="help-block">Must have between 3 and 15 digits</p>')) ?></div>
		<div class="col-lg-4"><?php echo $this->Form->input('users_group_id', array('label' => __d('users', 'Group'))) ?></div>
	</div>
	<div class="row">
		<div class="col-lg-6"><?php echo $this->Form->input('name', array('placeholder' => __d('users', 'Name'))) ?></div>
		<div class="col-lg-6"><?php echo $this->Form->input('last_name', array('placeholder' => __d('users', 'Last Name'))) ?></div>
	</div>
	<?php echo $this->Form->submit(__d('users', 'Save'), array('class' => 'btn btn-large btn-primary')) ?>

<?php echo $this->Form->end() ?>
