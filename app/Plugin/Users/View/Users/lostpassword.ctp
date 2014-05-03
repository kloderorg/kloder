<?php echo $this->Form->create('User', array('class' => 'form-signin', 'role' => 'form')) ?>
	<h2 class="form-signin-heading"><?php echo __d('users', 'Forget your password?') ?></h2>
	<p><?php echo __d('users', 'Don\'t worry, we can reset your password, you only need to set your username or email here and we send you a reset password link.') ?></p>
	<?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => __d('users', 'Username or Email'), 'label' => false, 'div' => false, 'required', 'autofocus')) ?>
	<div style="height:16px;"></div>
	<?php echo $this->Form->submit(__d('users', 'Reset password'), array('class' => 'btn btn-large btn-primary')) ?>
	<hr />
	<?php echo $this->Html->link(__d('users', '&laquo; Back'), '/users/users/login', array('escape' => false)) ?>
<?php echo $this->Form->end() ?>
