<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('users', 'View') ?> <small><?php echo __d('users', 'Groups') ?></small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-user"></i> '.__d('users', 'Users'), array('controller' => 'users', 'action' => 'index'), array('escape' => false)) ?></li>
			<li><?php echo $this->Html->link('<i class="fa fa-users"></i> '.__d('users', 'Groups'), array('action' => 'index'), array('escape' => false)) ?></li>
			<li class="active"><?php echo __d('users', 'View') ?></li>
		</ol>
	</div>
</div>

<h2><?php echo $group['UsersGroup']['name'] ?> <small><?php echo $group['UsersGroup']['id'] ?></small></h2>

<div class="btn-group">
	<?php echo $this->Html->link(__d('users', 'Back'), array('action' => 'index'), array('class' => 'btn btn-primary')) ?>
</div>
