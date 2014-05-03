<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('users', 'Administration') ?> <small><?php echo __d('users', 'Users') ?></small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-user"></i> '.__d('users', 'Users'), array('controller' => 'users', 'action' => 'index'), array('escape' => false)) ?></li>
			<li class="active"><?php echo __d('users', 'Administration') ?></li>
		</ol>
	</div>
</div>

<div class="btn-group">
	<?php echo $this->Html->link(__d('users', 'Add User'), array('action' => 'add'), array('class' => 'btn btn-primary')) ?>
</div>

<div class="btn-group">
	<?php echo $this->Html->link(__d('users', 'Groups'), array('controller' => 'users_groups', 'action' => 'index'), array('class' => 'btn btn-primary')) ?>
</div>

<div style="clear:both; height: 16px;"></div>

<?php echo $this->element('pagination') ?>
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th class="header"><?php echo __d('users', 'Username') ?> <i class="fa fa-sort"></i></th>
			<th class="header"><?php echo __d('users', 'Role') ?> <i class="fa fa-sort"></i></th>
			<th class="header"><?php echo __d('users', 'Email') ?> <i class="fa fa-sort"></i></th>
			<th class="header"><?php echo __d('users', 'Last Access') ?> <i class="fa fa-sort"></i></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user) : ?>
			<?php echo $this->element('users/list_item', compact('user')) ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $this->element('pagination') ?>
