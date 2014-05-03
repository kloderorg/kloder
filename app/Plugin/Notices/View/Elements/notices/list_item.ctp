<tr>
	<td><?php echo $this->Html->link($notice['Notice']['title'], array('action' => 'view', $notice['Notice']['id'])) ?></td>
	<td><?php if ($notice['Notice']['active']) : ?><i class="fa fa-check"></i><?php endif; ?></td>
	<td><?php echo $notice['Notice']['class'] ?></td>

	<td style="text-align: right;">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
      			<i class="fa fa-ellipsis-v"></i>
    		</button>
    		<ul class="dropdown-menu pull-right" role="menu">
    			<li><?php echo $this->Html->link(__d('notices', 'View'), array('action' => 'view', $notice['Notice']['id'])) ?></li>
    			<li>
    				<?php if ($notice['Notice']['active']) : ?>
    					<?php echo $this->Html->link(__d('notices', 'Deactivate'), array('action' => 'deactivate', $notice['Notice']['id'])) ?>
    				<?php else: ?>
    					<?php echo $this->Html->link(__d('notices', 'Activate'), array('action' => 'activate', $notice['Notice']['id'])) ?>
    				<?php endif; ?>
    			</li>
    			<li><?php echo $this->Html->link(__d('notices', 'Edit'), array('action' => 'edit', $notice['Notice']['id'])) ?></li>
				<li><?php echo $this->Html->link(__d('notices', 'Delete'), array('action' => 'delete', $notice['Notice']['id'])) ?></li>
    		</ul>
  		</div>
	</td>

</tr>
