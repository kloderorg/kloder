<div class="row">
    <div class="col-lg-12">
        <h1><?php echo __d('notices', 'Add') ?> <small><?php echo __d('notices', 'Notices') ?></small></h1>
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link('<i class="fa fa-exclamation"></i> '.__d('notices', 'Notices'), array('action' => 'index'), array('escape' => false)) ?></li>
            <li class="active"><?php echo __d('notices', 'Add') ?></li>
        </ol>
    </div>
</div>

<?php echo $this->Form->create('Notice') ?>

    <?php echo $this->Form->input('title', array('placeholder' => __d('notices', 'Title'), 'required', 'autofocus')) ?>
    <?php echo $this->Form->input('content', array('placeholder' => __d('notices', 'Content'))) ?>
    <?php echo $this->Form->input('class', array('placeholder' => __d('notices',  'Class'), 'options' => array(
        'success' => __d('notices', 'Success'),
        'info' => __d('notices', 'Info'),
        'warning' => __d('notices', 'Warning'),
        'danger' => __d('notices', 'Danger')
    ), 'default' => 'success')) ?>

    <?php echo $this->Form->submit(__d('notices', 'Save'), array('class' => 'btn btn-large btn-primary')) ?>

<?php echo $this->Form->end() ?>
