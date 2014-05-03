<?php App::uses('CakeTime', 'Utility') ?>
<tr>
	<td><?php echo $user['User']['username'] ?></td>
	<td><?php echo $user['UsersGroup']['name'] ?></td>
	<td><a href="mailto:<?php echo $user['User']['email'] ?>"><?php echo $user['User']['email'] ?></a></td>
	<td>
		<?php if ($user['User']['last_access'] == '0000-00-00 00:00:00') : ?>
			<?php echo __d('users', 'Never') ?>
		<?php else: ?>
			<?php echo CakeTime::timeAgoInWords($user['User']['last_access']) ?>
		<?php endif; ?>
	</td>

	<td style="text-align: right;">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
      			<i class="fa fa-ellipsis-v"></i>
    		</button>
    		<ul class="dropdown-menu pull-right" role="menu">
    			<li><?php echo $this->Html->link(__d('users', 'View'), array('action' => 'view', $user['User']['id'])) ?></li>
    			<li><?php echo $this->Html->link(__d('users', 'Edit'), array('action' => 'edit', $user['User']['id'])) ?></li>
				<li><?php echo $this->Html->link(__d('users', 'Delete'), array('action' => 'delete', $user['User']['id'])) ?></li>
    		</ul>
  		</div>
	</td>
</tr>
