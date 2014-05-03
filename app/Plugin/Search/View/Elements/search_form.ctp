<?php /*
Need:
$model - Model for search
*/ ?>
<div style="clear:both; height: 16px;"></div>
<?php echo $this->Form->create($model, array('action' => 'search', 'id' => 'context-general-search', 'role' => 'search')) ?>
<div class="input-group custom-search-form">
    <?php if (!isset($search)) $search = ''; ?>
    <?php echo $this->Form->input('search', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => __d('search', 'Search...'), 'value' => $search)) ?>
    <span class="input-group-btn">
        <a href="javascript:void(0);" class="btn btn-default" onclick="$('#context-general-search').submit();"><i class='fa fa-search'></i></a>
    </span>
</div>
<?php $this->Form->end() ?>
<div style="clear:both; height: 16px;"></div>
