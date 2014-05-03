<h2><?php echo __d('comments', 'Leave a comment') ?></h2>

<div style="position: relative;padding-left: 62px;">
	<?php $thumb = AuthComponent::user('thumb'); if (!empty($thumb)) : ?>
	    <img src="<?php echo $this->Html->url('/files/user/thumb/'.AuthComponent::user('thumb_dir').'/'.$thumb) ?>" alt="<?php echo __d('comments', 'Avatar') ?>" class="img-circle" style="height:48px;position:absolute;top:0px;left:0;border:1px solid #e7e7e7;" />
	<?php else: ?>
	    <i class="fa fa-user fa-fw" style="font-size:48px;height:48px;position:absolute;top:0px;left:0;"></i>
	<?php endif; ?>
</div>

<div style="margin-left: 62px;">
	<?php echo $this->Form->create('', array('action' => 'comment')) ?>
	<?php if ($redirect_field != 'foreign_key') echo $this->Form->input($redirect_field, array('type' => 'hidden', 'value' => $redirect_value)) ?>
	<?php echo $this->Form->input('foreign_key', array('type' => 'hidden', 'value' => $foreign_key)) ?>
	<?php echo $this->Form->input('content', array('type' => 'comment', 'label' => false, 'placeholder' => __d('comments', 'Message'))) ?>
	<?php echo $this->Form->submit(__d('comments', 'Send'), array('class' => 'btn btn-primary')) ?>
	<?php echo $this->Form->end() ?>
</div>
