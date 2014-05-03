<tr>
	<td><?php echo $group['UsersGroup']['id'] ?></td>
	<td><?php echo $this->Html->link($group['UsersGroup']['name'], array('action' => 'view', $group['UsersGroup']['id'])) ?></td>

	<td style="text-align: right;">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
      			<i class="fa fa-ellipsis-v"></i>
    		</button>
    		<ul class="dropdown-menu pull-right" role="menu">
    			<li><?php echo $this->Html->link(__d('users', 'View'), array('action' => 'view', $group['UsersGroup']['id'])) ?></li>
    			<li><?php echo $this->Html->link(__d('users', 'Edit'), array('action' => 'edit', $group['UsersGroup']['id'])) ?></li>
    			<?php if ($group['UsersGroup']['id'] != 'admin' && $group['UsersGroup']['id'] != 'users') : ?>
					<li><?php echo $this->Html->link(__d('users', 'Delete'), array('action' => 'delete', $group['UsersGroup']['id'])) ?></li>
				<?php endif; ?>
    		</ul>
  		</div>
	</td>
</tr>
