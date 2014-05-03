<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	<title>Resources</title>
	<link href="/img/favicon.png" type="image/x-icon" rel="icon" /><link href="/img/favicon.png" type="image/x-icon" rel="shortcut icon" />	<script type="text/javascript">var base_url = "http://localhost/gael/";</script>
	<?php
		echo $this->Html->css(array('default', 'ui/smoothness/jquery-ui-1.8.17.custom',
			'ui/jquery-ui-timepicker', 'tips/jquery.qtip', 'gritter/jquery.gritter', 'forms')
		);
		echo $this->Html->script(array('class/base', 'jquery-1.7.1.min', 'jquery-ui-1.8.17.custom.min',
			'jquery.cookie', 'jquery-ui-timepicker-addon', 'tips/jquery.qtip.min', 'gritter/jquery.gritter.min', 'default')
		);
		echo $scripts_for_layout;
	?>
</head>
<body style="padding: 10px;">
	<?php echo $content_for_layout; ?>
</body>
</html>