<?php echo $this->Html->script(array('/platform/js/i18n/es_ES'), array('inline' => false)); ?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
	function deleteItemMenu(id) {
		$.getJSON(base_url + 'platform/menu_items/delete/'+id, function (data) {
			$("#item-"+id).fadeOut();
		});
	}

	function confirmDeleteItemMenu(id) {
		$("#dialog-confirm-delete-item-menu").dialog({
			resizable: false,
			height:200,
			modal: true,
			buttons: [{
				text: ("Delete Item Menu").localized("Users"),
				click: function() {
					$( this ).dialog( "close" );
					deleteItemMenu(id);}
				},{
					text: ("Cancel").localized("Users"),
					click: function() { $(this).dialog("close"); }
				}
			]
		});
	}
<?php $this->Html->scriptEnd(); ?>
<noscript><?php echo __d('platform', 'Your browser does not support JavaScript!'); ?></noscript>
<!-- Libs -->
<?php echo $this->Html->css(array('/platform/css/menu-edit'), null, array('inline' => false)); ?>
<?php echo $this->Html->script(array('/platform/js/jquery.ui.nestedSortable', '/platform/js/menu'), array('inline' => false)); ?>
<!-- END: Libs -->

<!-- Header -->
<div class="inner-header">
	<div class="title"><?php echo $this->Html->image('/platform/img/icons/big/menu.png', array('title' => __d('platform', 'Menu', true), 'alt' => __d('platform', 'Menu', true), 'align' => 'top')); ?> <?php echo __d('platform', 'Menu') ?></div>
	<div class="buttons">
		<?php echo $this->Html->link(__d('platform','Add Item'), array('plugin' => 'platform', 'controller' => 'menu_items', 'action' => 'add'), array('class' => 'button')); ?>
		<?php echo $this->Html->link(__d('platform','Add Separation'), array('plugin' => 'platform', 'controller' => 'menu_items', 'action' => 'add_separation'), array('class' => 'button')); ?>
	</div>
</div>
<!-- END: Header -->

<?php echo $this->element('topbar'); ?>

<div style="overflow:hidden; padding-right: 10px;">
	<ol class="menu-list sortable" style="overflow:hidden;">
	<?php foreach($menus as $item) echo $this->element('menu-item', array('plugin' => 'platform', 'item' => $item, 'level' => 0)); ?>
	</ol>
</div>
<div id="dialog-confirm-delete-item-menu" style="display:none;" title="<?php echo __d('platform', 'Are you sure?')?>">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo __d('platform', 'Item menu will be deleted.', true)?></p>
</div>
<?php echo $this->element('bottombar'); ?>
