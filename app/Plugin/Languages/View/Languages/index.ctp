<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('languages', 'Administration') ?> <small><?php echo __d('languages', 'Languages') ?></small></h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-flag"></i> <?php echo __d('languages', 'Languages') ?></li>
		</ol>
	</div>
</div>

<div class="btn-group">
	<?php echo $this->Html->link(__d('languages', 'Add Language'), array('action' => 'add'), array('class' => 'btn btn-primary')) ?>
</div>

<div style="clear:both; height: 16px;"></div>

<!-- Content -->
<?php echo $this->element('pagination') ?>
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th class="header"><?php echo __d('languages', 'Id') ?> <i class="fa fa-sort"></i></th>
			<th class="header"><?php echo __d('languages', 'Flag') ?> <i class="fa fa-sort"></i></th>
			<th class="header"><?php echo __d('languages', 'Language') ?> <i class="fa fa-sort"></i></th>
			<th></th>
		</tr>
	</thead>
	<?php foreach ($languages as $language): ?>
		<?php echo $this->element('languages/list_item', array('language' => $language)) ?>
	<?php endforeach; ?>
</table>
<?php echo $this->element('pagination') ?>
