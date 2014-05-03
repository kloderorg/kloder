<div class="row">
    <div class="col-lg-12">
        <h1><?php echo __d('notices', 'Administration') ?> <small><?php echo __d('notices', 'Notices') ?></small></h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-exclamation"></i> <?php echo __d('notices', 'Notices') ?></li>
        </ol>
    </div>
</div>

<div class="btn-group">
	<?php echo $this->Html->link(__d('notices', 'Add Notice'), array('action' => 'add'), array('class' => 'btn btn-primary')) ?>
</div>

<div style="clear:both; height: 16px;"></div>

<?php echo $this->element('pagination') ?>
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th class="header"><?php echo $this->Paginator->sort('title', __d('notices', 'Title')) ?></th>
			<th class="header"><?php echo $this->Paginator->sort('active', __d('notices', 'Active')) ?></th>
			<th class="header"><?php echo $this->Paginator->sort('class', __d('notices', 'Class')) ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($notices as $notice) : ?>
			<?php echo $this->element('notices/list_item', compact('notice')) ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $this->element('pagination') ?>

