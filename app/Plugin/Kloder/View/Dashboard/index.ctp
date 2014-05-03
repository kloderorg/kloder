<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __('Dashboard') ?> <small><?php echo __('Welcome') ?></small></h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard') ?></li>
		</ol>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo __('Welcome to <b>Kloder</b>! Feel free to use all the power in this platform! You can make big things with it and we are at your service to provide you with the best tools ever you have seen, only give a try and you fall in love with Kloder. Don\'t forget, you are amazing!') ?>
		</div>
	</div>
</div>

<!-- Finances -->
<div class="row">
<?php if ($finances) : ?>
	<div class="col-lg-6">
		<iframe src="<?php echo $this->Html->url(array('plugin' => 'finances', 'controller' => 'finances_invoices', 'action' => 'widget_year')) ?>" width="100%" height="350" style="border: 0px;"></iframe>
	</div>
<?php endif; ?>
<?php if ($projects) : ?>
	<div class="col-lg-6">
		<iframe src="<?php echo $this->Html->url(array('plugin' => 'projects', 'controller' => 'projects_issues', 'action' => 'widget_latest')) ?>" width="100%" height="350" style="border: 0px;"></iframe>
	</div>
<?php endif; ?>
</div>
