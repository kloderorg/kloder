<div class="row">
    <div class="col-lg-12">
        <h1><?php echo __d('plugins', 'Add') ?> <small><?php echo __d('plugins', 'Repositories') ?></small></h1>
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link('<i class="fa fa-puzzle-piece"></i> '.__d('plugins', 'Plugins'), array('controller' => 'plugins', 'action' => 'index'), array('escape' => false)) ?></li>
            <li><?php echo $this->Html->link(__d('plugins', 'Plugins'), array('action' => 'index')) ?></li>
            <li class="active"><?php echo __d('plugins', 'Add') ?></li>
        </ol>
    </div>
</div>

<?php echo $this->Form->create('PluginsRepository') ?>

    <?php echo $this->Form->input('name', array('placeholder' => __d('plugins', 'Name'), 'required', 'autofocus')) ?>
    <?php echo $this->Form->input('description', array('placeholder' => __d('plugins', 'Description'))) ?>
    <?php echo $this->Form->input('uri', array('placeholder' => __d('plugins', 'URI'))) ?>
    <?php echo $this->Form->input('public', array('placeholder' => __d('plugins', 'Public'))) ?>
    <?php echo $this->Form->input('user', array('placeholder' => __d('plugins', 'User'))) ?>
    <?php echo $this->Form->input('pass', array('placeholder' => __d('plugins', 'Pass'))) ?>

    <?php echo $this->Form->submit(__d('plugins', 'Save'), array('class' => 'btn btn-large btn-primary')) ?>

<?php echo $this->Form->end() ?>

<script type="text/javascript">
$(function () {
    $('#PluginsRepositoryPublic').change(function () {
        if ($(this).is(":checked")) {
            $('#PluginsRepositoryUser,#PluginsRepositoryPass').attr("disabled", "disabled");
        } else {
            $('#PluginsRepositoryUser,#PluginsRepositoryPass').removeAttr("disabled");
        }
    });
});
</script>
