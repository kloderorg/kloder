<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('users', 'Administration') ?> <small><?php echo __d('users', 'Groups') ?></small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-user"></i> '.__d('users', 'Users'), array('controller' => 'users', 'action' => 'index'), array('escape' => false)) ?></li>
			<li class="active"><i class="fa fa-users"></i> <?php echo __d('users', 'Groups') ?></li>
		</ol>
	</div>
</div>

<div class="btn-group">
	<?php echo $this->Html->link(__d('users', 'Add Group'), array('action' => 'add'), array('class' => 'btn btn-primary')) ?>
</div>

<div style="clear:both; height: 16px;"></div>

<?php echo $this->element('pagination') ?>
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th class="header"><?php echo __d('users', 'ID') ?></th>
			<th class="header"><?php echo __d('users', 'Name') ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($groups as $group) : ?>
			<?php echo $this->element('groups/list_item', compact('group')) ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $this->element('pagination') ?>
