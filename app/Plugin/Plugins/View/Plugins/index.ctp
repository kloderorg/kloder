<div class="row">
    <div class="col-lg-12">
        <h1><?php echo __d('plugins', 'Administration') ?> <small><?php echo __d('plugins', 'Plugins') ?></small></h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-puzzle-piece"></i> <?php echo __d('plugins', 'Plugins') ?></li>
        </ol>
    </div>
</div>

<div class="btn-group">
	<?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__d('plugins', 'Add Plugin'), array('action' => 'add'), array('class' => 'btn btn-success', 'escape' => false)) ?>
</div>

<div class="btn-group pull-right">
	<?php echo $this->Html->link(__d('plugins', 'Repositories'), array('controller' => 'plugins_repositories', 'action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)) ?>
</div>

<div style="clear:both; height: 16px;"></div>

<table class="table table-condensed table-striped">
	<tr>
		<th><?php echo __('Name') ?></th>
		<th><?php echo __('Version') ?></th>
		<th><?php echo __('Link') ?></th>
		<th><?php echo __('URI') ?></th>
		<th style="text-align: right;"><?php echo __('Actions') ?></th>
	</tr>
	<?php foreach ($plugins as $key => $plugin) : ?>
		<tr>
			<td><?php echo $plugin['Plugin']['name'] ?></td>
			<td><?php echo $plugin['Plugin']['version'] ?></td>
			<td><?php echo $this->Html->link($plugin['Plugin']['link'], $plugin['Plugin']['link'], array('target' => '_blank')) ?></td>
			<td><?php echo $this->Html->link($plugin['Plugin']['uri'], $plugin['Plugin']['uri'], array('target' => '_blank')) ?></td>
			<td style="text-align: right;">
				<?php switch ($plugin['Plugin']['status']) {
					case '0':
						echo $this->Html->dialog('install-dialog-'.$plugin['Plugin']['id'], array('name' => __('Install'), 'title' => __('Install plugin'), 'content' => '<h3>'.__('Which version to install?').'</h3>'.$this->Html->link(__('Develop'), array('action' => 'install_develop', $plugin['Plugin']['id']), array('class' => 'btn btn-primary')).' '.$this->Html->link(__('Package'), array('action' => 'install_package', $plugin['Plugin']['id']), array('class' => 'btn btn-primary'))));
						break;
					case '1':
						echo $this->Html->link(__('Delete'), array('action' => 'delete', $plugin['Plugin']['id']), array('class' => 'btn btn-danger'));
						break;
				} ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>

