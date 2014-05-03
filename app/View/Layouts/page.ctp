<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title_for_layout; ?></title>

    <?php echo $this->Html->css(array('bootstrap/bootstrap', 'bootstrap/jasny-bootstrap', 'page', 'fontawesome/font-awesome.min')) ?>
    <?php echo $this->Html->script(array('jquery-2.0.3.min', 'bootstrap/bootstrap.min', 'bootstrap/jasny-bootstrap.min')); ?>
</head>
<?php echo $this->fetch('content'); ?>
</body>

</html>
