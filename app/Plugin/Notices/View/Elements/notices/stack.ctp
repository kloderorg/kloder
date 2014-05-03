<?php foreach (Configure::read('Notices') as $notice) : ?>
	<?php echo $this->element('Notices.notices/item', array('notice' => $notice)) ?>
<?php endforeach; ?>
