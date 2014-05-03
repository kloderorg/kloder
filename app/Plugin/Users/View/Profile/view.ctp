<div class="row">
	<div class="col-lg-12">
		<h1>View <small>Profile</small></h1>
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

<hr />

<div class="btn-group">
	<?php echo $this->Html->link(__d('users', 'Edit'), array('action' => 'edit'), array('class' => 'btn btn-primary')) ?>
</div>
