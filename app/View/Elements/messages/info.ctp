<?php $uuid = String::uuid() ?>

<div style="padding-top:20px;" id="<?php echo $uuid ?>">
	<div class="alert alert-info">
		<button type="button" class="close">&times;</button>
		<?php echo $message; ?>
	</div>
</div>

<script>
$(function () {
	$('#<?php echo $uuid ?> .close').click(function () {
		$('#<?php echo $uuid ?>').remove();
	});

	window.setTimeout(function () {
		$('#<?php echo $uuid ?>').slideUp('fast', function () {
			$('#<?php echo $uuid ?>').remove();
		});
	}, 15 * 1000);
});
</script>
