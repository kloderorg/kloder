<!-- Libs -->
<?php echo $this->Html->css(array('forms'), null, array('inline' => false)); ?>
<!-- FIN: Libs -->

<?php echo $this->FormExtended->create('MenuItem'); ?>

<!-- Cabecera -->
<div class="inner-header">
	<div class="title"><?php echo $this->Html->image('/platform/img/icons/big/menu.png', array('alt' => __d('platform', 'Add Item', true), 'align' => 'top')); ?> <?php echo __d('platform', 'Add Item') ?></div>
	<div class="buttons"><?php echo $this->Form->submit(__d('platform', 'Save', true)); ?></div>
	<div class="info"><?php echo $this->Html->link(__d('platform','Back', true), array('plugin' => 'platform', 'controller' => 'menu', 'action' => 'index'), array('class' => 'button')); ?></div>
</div>
<!-- FIN: Cabecera -->

<?php echo $this->element('topbar'); ?>

<?php echo $this->Sidebars->openHelpSidebar(450) ?>
	<?php echo $this->CollapsibleBlocks->open() ?>
	<?php echo $this->CollapsibleBlocks->block(__d('platform', 'Short Codes', true), $this->element('help/menu-index', array('plugin' => 'menu'))) ?>
	<?php echo $this->CollapsibleBlocks->close() ?>
<?php echo $this->Sidebars->closeHelpSidebar() ?>

<div style="overflow:hidden; padding-right: 10px;">
	<table width="100%" cellspacing="10">
		<tr>
			<td width="50%" valign="top"><?php echo $this->FormExtended->inputMultilanguage('title', array('between' => '<br />', 'languages' => array('en_US', 'es_ES', 'gl_ES'))); ?></td>
			<td valign="top"><?php echo $this->FormExtended->input('icon', array('after' => '<small>e.g.: /img/icons/small/dashboard.png or /projects/img/icons/small/overview.png</small>')); ?></td>
		</tr>
		<tr>
			<td colspan="2"><?php echo $this->FormExtended->input('ubication', array('after' => '<small>e.g.: projects/projects_overview/index or projects/projects_overview/ </small>')); ?></td>
		</tr>
		<tr>
			<td colspan="2"><?php echo $this->FormExtended->input('disabled'); ?></td>
		</tr>
		<tr>
			<td colspan="2"><?php echo $this->FormExtended->input('extra_ubications', array('style' => 'width:100%')); ?></td>
		</tr>
	</table>
</div>

<?php echo $this->element('bottombar'); ?>

<?php echo $this->FormExtended->end(); ?>
