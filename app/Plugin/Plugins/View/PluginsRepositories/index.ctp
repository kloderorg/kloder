<div class="row">
    <div class="col-lg-12">
        <h1><?php echo __d('plugins', 'Administration') ?> <small><?php echo __d('plugins', 'Repositories') ?></small></h1>
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link('<i class="fa fa-puzzle-piece"></i> '.__d('plugins', 'Plugins'), array('controller' => 'plugins', 'action' => 'index'), array('escape' => false)) ?></li>
            <li class="active"><?php echo __d('plugins', 'Repositories') ?></li>
        </ol>
    </div>
</div>

<div class="btn-group">
	<?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__d('plugins', 'Add Repository'), array('action' => 'add'), array('class' => 'btn btn-success', 'escape' => false)) ?>
</div>

<div style="clear:both; height: 16px;"></div>

<?php echo $this->element('pagination') ?>
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th class="header"><?php echo __('Name') ?></th>
			<th class="header"><?php echo __('Public') ?></th>
			<th class="header"><?php echo __('Last Check') ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($repositories as $repository) : ?>
			<?php echo $this->element('repositories/list_item', compact('repository')) ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $this->element('pagination') ?>
