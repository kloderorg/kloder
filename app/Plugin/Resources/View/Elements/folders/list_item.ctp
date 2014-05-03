<?php App::uses('CakeTime', 'Utility') ?>
<tr>
	<td><input type="checkbox" /></td>
	<td><?php echo $this->Html->link('<i class="fa fa-folder"></i> '.$folder['ResourcesFolder']['name'], array('action' => 'index', $folder['ResourcesFolder']['id']), array('escape' => false)) ?></td>
	<td><?php if ($folder['User']['id'] == AuthComponent::user('id')) echo __d('resources', 'me'); else echo $folder['User']['name']; ?>
	<td><?php echo CakeTime::timeAgoInWords($folder['ResourcesFolder']['modified']) ?></td>
</tr>
