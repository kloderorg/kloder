<tr>
	<td><?php echo $this->Html->link($repository['PluginsRepository']['name'], array('action' => 'view', $repository['PluginsRepository']['id'])) ?></td>
    <td><?php echo $repository['PluginsRepository']['public'] ?></td>
    <td><?php echo $repository['PluginsRepository']['last_check'] ?></td>

	<td style="text-align: right;">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
      			<i class="fa fa-ellipsis-v"></i>
    		</button>
    		<ul class="dropdown-menu pull-right" role="menu">
    			<li><?php echo $this->Html->link(__d('plugins', 'View'), array('action' => 'view', $repository['PluginsRepository']['id'])) ?></li>
                <li><?php echo $this->Html->link(__d('plugins', 'Update'), array('action' => 'update', $repository['PluginsRepository']['id'])) ?></li>
    			<li><?php echo $this->Html->link(__d('plugins', 'Edit'), array('action' => 'edit', $repository['PluginsRepository']['id'])) ?></li>
				<li><?php echo $this->Html->link(__d('plugins', 'Delete'), array('action' => 'delete', $repository['PluginsRepository']['id'])) ?></li>
    		</ul>
  		</div>
	</td>

</tr>
