<div class="row">
    <div class="col-lg-12">
        <h1><?php echo __d('languages', 'Delete') ?> <small><?php echo __d('languages', 'Languages') ?></small></h1>
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link('<i class="fa fa-flag"></i> '.__d('languages', 'Languages'), array('action' => 'index'), array('escape' => false)) ?></li>
            <li class="active"><?php echo __d('languages', 'Delete') ?></li>
        </ol>
    </div>
</div>

<h2><?php echo $this->request->data['Language']['name'] ?></h2>

<?php echo $this->Form->create('Language') ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')) ?>

<blockquote>
	<?php echo __d('languages', 'Are you sure to delete <b>%s</b>?', $this->data['Language']['name']) ?>
</blockquote>

<div class="form-actions">
	<?php echo $this->Html->link(__d('languages', 'Cancel'), array('action' => 'index'), array('class' => 'btn')) ?>
	<?php echo $this->Form->submit(__d('languages', 'Delete'), array('class' => 'btn btn-danger', 'div' => false)) ?>
</div>

<?php echo $this->Form->end() ?>
