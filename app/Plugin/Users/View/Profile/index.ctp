<div class="row">
	<div class="col-lg-12">
		<h1>Aministration <small>Profile</small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-user"></i> '.__d('users', 'Profile'), array('action' => 'index'), array('escape' => false)) ?></li>
		</ol>
	</div>
</div>

<h1><?php echo $user['User']['name'] ?> <?php echo $user['User']['last_name'] ?> <small><?php echo $user['User']['username'] ?></small></h1>

<?php if (!empty($user['User']['thumb'])) : ?>
	<img src="<?php echo $this->Html->url('/files/user/thumb/'.$user['User']['thumb_dir'].'/'.$user['User']['thumb']) ?>" alt="<?php echo __d('users', 'Avatar') ?>" class="img-circle" style="height:150px;" />
	<div style="clear:both; height: 16px;"></div>
<?php endif; ?>

<strong><?php echo __d('users', 'Email') ?></strong> <a href="mailto:<?php echo $user['User']['email'] ?>"><?php echo $user['User']['email'] ?></a><br />
<strong><?php echo __d('users', 'Language') ?></strong> <?php if (!empty($user['Language']['name'])) echo $user['Language']['name']; else echo __d('users', 'English') ?><br />
<strong><?php echo __d('users', 'Date Format') ?></strong> <?php if (!empty($user['User']['date_format'])) echo date($user['User']['date_format']); else echo date('d/m/Y'); ?><br />
<strong><?php echo __d('users', 'Time Format') ?></strong> <?php if (!empty($user['User']['time_format'])) echo date($user['User']['time_format']); else echo date('H:i'); ?><br />
<?php $days = array('0' => __d('users', 'Sunday'), '1' => __d('users', 'Monday'), '2' => __d('users', 'Tuesday'), '3' => __d('users', 'Wednesday'), '4' => __d('users', 'Thursday'), '5' => __d('users', 'Friday'), '6' => __d('users', 'Saturday')); ?>
<strong><?php echo __d('users', 'First Day') ?></strong> <?php echo $days[$user['User']['first_day']] ?><br />

<hr />

<div class="btn-group">
	<?php echo $this->Html->link(__d('users', 'Edit'), array('action' => 'edit'), array('class' => 'btn btn-primary')) ?>
</div>
