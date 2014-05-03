<!-- Libs -->
<?php echo $this->Html->css(array('ui/checkbox/jquery.ibutton', 'forms'), null, array('inline' => false)); ?>
<?php echo $this->Html->script(array('ui/checkbox/jquery.ibutton.min', 'tiny_mce/jquery.tinymce'), array('inline' => false)); ?>
<script type="text/javascript">
$(function() {
	$('#tabs').tabs();
	$(':checkbox').iButton();
	$('textarea').tinymce({
        script_url : base_url+'js/tiny_mce/tiny_mce.js',
        height: "100",
		width: "100%",
		theme: "simple",
		force_br_newlines: true,
		force_p_newlines: false,
		forced_root_block: ''
	});
});
</script>
<!-- END:Libs -->
<?php echo $this->Form->create('Setting', array('action' => 'index'));?>
<!-- Cabecera -->
<div class="inner-header">
	<div class="title"><?php echo $this->Html->image('/platform/img/icons/big/settings.png', array('alt' => __d('platform', 'Settings', true), 'align' => 'top')); ?> <?php echo __d('platform', 'Settings', true) ?></div>
	<div class="buttons"><?php echo $this->Form->submit(__d('platform', 'Save', true)) ?></div>
</div>
<!-- FIN: Cabecera -->
<?php echo $this->element('topbar'); ?>
<div id="tabs" class=".ui-helper-reset">
	<ul>
		<li><a href="#tabs-1"><?php echo __d('platform', 'General') ?></a></li>
		<li><a href="#tabs-2"><?php echo __d('platform', 'Communications') ?></a></li>
	</ul>
	<div id="tabs-1">
		<?php echo $this->Form->input('language_id'); ?>
	</div>
	<div id="tabs-2">
		<?php echo $this->Form->input('chat', array('type' => 'checkbox', 'label' => '', 'before' => 'Chat')); ?>
	</div>
</div>
<?php echo $this->element('bottombar') ?>
<?php echo $this->Form->end();?>