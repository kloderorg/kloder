<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
    <meta name="author" content="">

    <?php echo $this->Html->meta('icon') ?>

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" href="../assets/ico/favicon.png">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

    <?php echo $this->Html->css(array('bootstrap/bootstrap', 'bootstrap/jasny-bootstrap', 'main', 'fontawesome/font-awesome.min')) ?>
    <?php echo $this->Html->script(array('jquery-2.0.3.min', 'jquery-migrate-1.2.1.min', 'bootstrap/bootstrap.min', 'bootstrap/jasny-bootstrap.min', 'underscore-min', 'jquery.metisMenu')); ?>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<?php echo $this->fetch('meta') ?>
	<?php echo $this->fetch('css') ?>
	<?php echo $this->fetch('script') ?>
</head>
<body>

	<div id="wrapper">

		<?php echo $this->element('menu') ?>
		<?php echo $this->element('sidebar') ?>

		<div id="page-wrapper">
			<?php echo $this->element('Notices.notices/stack') ?>
			<?php echo $this->Session->flash() ?>
			<?php echo $this->fetch('content') ?>
		</div>

	</div>

</body>
</html>
