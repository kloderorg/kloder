<?php App::uses('CakeTime', 'Utility') ?>
<?php $comment_model = Inflector::pluralize($model).'Comment' ?>

<div style="position: relative;padding-left: 62px;">
	<a href="<?php echo $this->Html->url(array('plugin' => 'users', 'controller' => 'profile', 'action' => 'view', $comment['User']['id'])) ?>">
		<?php $thumb = $comment['User']['thumb']; if (!empty($thumb)) : ?>
            <img src="<?php echo $this->Html->url('/files/user/thumb/'.$comment['User']['thumb_dir'].'/'.$thumb) ?>" alt="<?php echo __d('projects', 'Avatar') ?>" class="img-circle" style="height:48px;position:absolute;top:0px;left:0;border:1px solid #e7e7e7;" />
        <?php else: ?>
            <i class="fa fa-user fa-fw" style="font-size:48px;height:48px;position:absolute;top:0px;left:0;"></i>
        <?php endif; ?>

        <?php echo $comment['User']['name'].' '.$comment['User']['last_name'] ?>
    </a>
    <small><?php echo CakeTime::timeAgoInWords($comment[$comment_model]['modified']) ?></small><br />
</div>

<div style="margin-left: 62px;">
	<?php echo $comment[$comment_model]['content'] ?>
</div>

<hr />
