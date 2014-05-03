<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('languages', 'Add') ?> <small><?php echo __d('languages', 'Languages') ?></small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-flag"></i> '.__d('languages', 'Languages'), array('action' => 'index'), array('escape' => false)) ?></li>
			<li class="active"><?php echo __d('languages', 'Add') ?></li>
		</ol>
	</div>
</div>

<?php echo $this->Form->create('Language'); ?>

<?php echo $this->Form->input('id', array('type' => 'text', 'placeholder' => __d('languages', 'en_US or es_ES'))) ?>
<?php echo $this->Form->input('name', array('placeholder' => __d('languages', 'Name'))) ?>
<?php echo $this->Form->input('flag', array('placeholder' => __d('languages', 'gb.png, us.png, es.png, ...'))) ?>

<div class="form-actions">
  	<?php echo $this->Form->submit(__d('languages', 'Save'), array('class' => 'btn btn-primary', 'div' => false)) ?>
  	<?php echo $this->Html->link(__d('languages','Cancel', true), 'javascript:window.history.back();', array('class' => 'btn')); ?>
</div>

<?php echo $this->Form->end() ?>
