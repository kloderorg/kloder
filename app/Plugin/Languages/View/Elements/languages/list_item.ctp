<tr>
  <td><?php echo $language['Language']['id'] ?></td>
  <td><?php echo $this->Html->image('Languages.flags/'.$language['Language']['flag']) ?></td>
	<td><?php echo $language['Language']['name'] ?></td>

	<td style="text-align: right;">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
      			<i class="fa fa-ellipsis-v"></i>
    		</button>
    		<ul class="dropdown-menu pull-right" role="menu">
    			<li><?php echo $this->Html->link(__d('languages', 'Translate'), array('action' => 'translate', $language['Language']['id'])) ?></li>
    			<li><?php echo $this->Html->link(__d('languages', 'Edit'), array('action' => 'edit', $language['Language']['id'])) ?></li>
				<li><?php echo $this->Html->link(__d('languages', 'Delete'), array('action' => 'delete', $language['Language']['id'])) ?></li>
    		</ul>
  		</div>
	</td>
</tr>
