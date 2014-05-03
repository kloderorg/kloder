<li id="item-<?php echo $item['MenuItem']['id'] ?>">
	<div<?php if ($item['MenuItem']['disabled']) echo ' class="inactive"'; ?>>
		
		<?php if ($level == 0 && $item['MenuItem']['title'] != '---') : ?>
			<a href="javascript:void(0);" title="<?php echo __d('platform', 'Open/Close submenu') ?>" onclick="$('#menu-children-<?php echo $item['MenuItem']['id'] ?>').slideToggle()"><span class="ui-icon ui-icon-circle-triangle-s handler" style="padding-right: 10px;">&nbsp;</span></a>
		<?php endif; ?>
		
		<span title="<?php echo __d('platform', 'Move item'); ?>" class="ui-icon ui-icon-arrowthick-2-n-s handler"></span>
		
		<?php if ($item['MenuItem']['icon']) : ?>
			<img src="<?php echo $this->Html->url($item['MenuItem']['icon']) ?>" alt="<?php echo $item['MenuItem']['title'] ?>" title="<?php echo $item['MenuItem']['title'] ?>" align="top"/>
		<?php endif; ?>
		
		<?php if ($item['MenuItem']['title'] == '---') {
			echo __d('platform', '- Separator -');
		} else {
			echo $this->FormExtended->readMultilanguage($item['MenuItem']['title']).' &rarr; '.$item['MenuItem']['ubication'];
			echo $this->Html->link($this->Html->image('actions/shortcut.png', array('title' => __d('platform', 'Open link in new window'), 'alt' => __d('platform', 'Open link in new window'), 'align' => 'top')), '/'.$item['MenuItem']['ubication'], array('target' => '_blank', 'escape' => false));
		} ?>
		
		<span class="menu-actions">
			<?php if ($item['MenuItem']['disabled']) : ?>
				<?php echo $this->Html->link($this->Html->image('actions/disable.png', array('title' => __d('platform', 'Enable item'), 'alt' => __d('platform', 'Enable item'), 'align' => 'top')), array('plugin' => 'platform', 'controller' => 'menu_items', 'action' => 'enable', $item['MenuItem']['id']), array('escape' => false)); ?>
			<?php else: ?>
				<?php echo $this->Html->link($this->Html->image('actions/enable.png', array('title' => __d('platform', 'Disable item'), 'alt' => __d('platform', 'Disable item'), 'align' => 'top')), array('plugin' => 'platform', 'controller' => 'menu_items', 'action' => 'disable', $item['MenuItem']['id']), array('escape' => false)); ?>
			<?php endif; ?>
			
			<?php if ($item['MenuItem']['title'] != '---') : ?>
				<?php echo $this->Html->link($this->Html->image('actions/edit.png', array('title' => __d('platform', 'Edit item'), 'alt' => __d('platform', 'Edit item'), 'align' => 'top')), array('plugin' => 'platform', 'controller' => 'menu_items', 'action' => 'edit', $item['MenuItem']['id']), array('escape' => false)); ?>
			<?php endif; ?>
			
			<?php echo $this->Html->link($this->Html->image("actions/delete.png", array('title' => __d('platform', 'Delete item'), 'alt' => __d('platform', 'Delete item'), 'align' => 'top')),"javascript:confirmDeleteItemMenu(".$item['MenuItem']['id'].")",	array('escape' => false));?>
		</span>
	</div>
	<ol id="menu-children-<?php echo $item['MenuItem']['id'] ?>" style="display:none;">
		<?php foreach($item['children'] as $subitem) echo $this->element('menu-item', array('plugin' => 'platform', 'item' => $subitem, 'level' => $level + 1)); ?>
	</ol>
</li>