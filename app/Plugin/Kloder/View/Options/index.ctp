<!-- Libs -->
<?php echo $this->Html->css(array('ui/checkbox/jquery.ibutton', 'forms'), null, array('inline' => false)); ?>
<?php echo $this->Html->script(array('ui/checkbox/jquery.ibutton.min'), array('inline' => false)); ?>
<script type="text/javascript">
$(function() {
	$('#tabs').tabs();
	$(':checkbox').iButton();
});
</script>
<!-- END:Libs -->

<?php echo $this->Form->create('Setting', array('url' => array('plugin' => 'platform','controller' => 'options', 'action' => 'index')));?>

<!-- Cabecera -->
<div class="inner-header">
	<div class="title"><?php echo $this->Html->image('/platform/img/icons/big/config.png', array('alt' => __d('platform', 'Options', true), 'align' => 'top')); ?> <?php echo __d('platform', 'Options', true) ?></div>
	<div class="buttons"><?php echo $this->Form->submit(__d('platform', 'Save', true)) ?></div>
</div>
<!-- FIN: Cabecera -->

<?php echo $this->element('topbar'); ?>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><?php echo __d('platform', 'General') ?></a></li>
		<li><a href="#tabs-2"><?php echo __d('platform', 'Communications') ?></a></li>
	</ul>
	<div id="tabs-1">
		<?php echo $this->Form->input('language_id', array('label' => __d('platform', 'Languages'))); ?>
		<?php echo $this->Form->input('name', array('type' => 'text', 'label' => __d('platform', 'Name of the platform'))); ?>
		<?php echo $this->Form->input('logo_header', array('type' => 'text', 'label' => __d('platform', 'Header logo'))); ?>
		<?php echo $this->Form->input('favicon', array('type' => 'text', 'label' => __d('platform', 'Favicon'))); ?>
		<?php echo $this->Form->input('theme_id', array('empty' => __d('platform', '(by default)'), 'label' => __d('platform', 'Theme'))); ?>
		<?php echo $this->Form->input('rowaccess_superusers_groups', array('type' => 'text', 'label' => __d('platform', 'Permissions superusers groups (coma separated list)'))); ?>
	</div>
	<div id="tabs-2">
		<?php echo $this->Form->input('chat', array('type' => 'checkbox', 'label' => '', 'before' => __d('platform', 'Chat'))); ?>
		<?php echo $this->Form->input('chat_help_users', array('type' => 'checkbox', 'label' => '', 'before' => __d('platform', 'Chat help users'))); ?>
	</div>
</div>

<?php echo $this->element('bottombar') ?>

<?php echo $this->Form->end() ?>