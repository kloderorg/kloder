<div class="row">
    <div class="col-lg-12">
        <h1><?php echo __d('notices', 'View') ?> <small><?php echo __d('notices', 'Notices') ?></small></h1>
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link('<i class="fa fa-exclamation"></i> '.__d('notices', 'Notices'), array('action' => 'index'), array('escape' => false)) ?></li>
            <li class="active"><?php echo __d('notices', 'View') ?></li>
        </ol>
    </div>
</div>

<?php echo $this->element('Notices.notices/item', array('notice' => $notice)) ?>

<div class="btn-group">
    <?php echo $this->Html->link(__d('bank', 'Edit'), array('action' => 'edit', $notice['Notice']['id']), array('class' => 'btn btn-primary')) ?>
    <?php echo $this->Html->link(__d('bank', 'Delete'), array('action' => 'delete', $notice['Notice']['id']), array('class' => 'btn btn-danger')) ?>
</div>
