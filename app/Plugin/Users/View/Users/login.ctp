<?php echo $this->Form->create('User', array('class' => 'form-signin', 'role' => 'form')) ?>
	<?php echo $this->Session->flash('auth'); ?>
	<?php echo $this->Session->flash(); ?>
	<h2 class="form-signin-heading"><?php echo __d('users', 'Please sign in') ?></h2>
	<?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => __d('users', 'Username or Email'), 'label' => false, 'div' => false, 'required', 'autofocus')) ?>
	<?php echo $this->Form->input('password', array('type' => 'password', 'class' => 'form-control', 'placeholder' => __d('users', 'Password'), 'label' => false, 'div' => false, 'required')) ?>
	<?php echo $this->Form->input('remember', array('type' => 'checkbox', 'label' => __d('users', 'Remember me'))) ?>
	<?php echo $this->Form->submit(__d('users', 'Sign in'), array('class' => 'btn btn-large btn-primary')) ?>
	<hr />
	<?php echo $this->Html->link(__d('users', 'Forget your password?'), '/users/users/lostpassword') ?>
<?php echo $this->Form->end() ?>
