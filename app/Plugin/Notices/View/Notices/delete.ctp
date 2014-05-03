<div class="row">
    <div class="col-lg-12">
        <h1><?php echo __d('notices', 'Delete') ?> <small><?php echo __d('notices', 'Notices') ?></small></h1>
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link('<i class="fa fa-exclamation"></i> '.__d('notices', 'Notices'), array('action' => 'index'), array('escape' => false)) ?></li>
            <li class="active"><?php echo __d('notices', 'Delete') ?></li>
        </ol>
    </div>
</div>

<h2><?php echo $notice['Notice']['title'] ?></h2>

<?php echo $this->Form->create('Notice') ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')) ?>

<blockquote>
	<?php echo __d('notices', 'Are you sure to delete <b>%s</b>?', $notice['Notice']['title']) ?>
</blockquote>

<div class="form-actions">
	<?php echo $this->Html->link(__d('notices', 'Cancel'), array('action' => 'view', $notice['Notice']['id']), array('class' => 'btn')) ?>
	<?php echo $this->Form->submit(__d('notices', 'Delete'), array('class' => 'btn btn-danger', 'div' => false)) ?>
</div>

<?php echo $this->Form->end() ?>
