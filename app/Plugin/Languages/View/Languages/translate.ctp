<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('languages', 'Translate') ?> <small><?php echo __d('languages', 'Languages') ?></small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-flag"></i> '.__d('languages', 'Languages'), array('action' => 'index'), array('escape' => false)) ?></li>
			<li class="active"><?php echo __d('languages', 'Translate') ?></li>
		</ol>
	</div>
</div>

<div class="btn-group">
	<?php echo $this->Html->link(__d('languages', 'Reload Strings'), array('action' => 'translate', $language['Language']['id']), array('class' => 'btn btn-primary')) ?>
</div>

<br /><br />

<div class="panel-group" id="accordion">
	<?php foreach($translations as $translation) : ?>
		<?php if (!array_key_exists('file', $translation)) continue; ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $translation['id'] ?>">
						<div class="row">
							<div class="col-lg-6">
								<?php if (array_key_exists('plugin', $translation) && $translation['plugin'] != '') : ?><?php echo $translation['plugin'] ?> /<?php endif; ?>
								<?php echo $translation['file'] ?>
							</div>
							<div class="col-lg-3">
								<?php echo $translation['percentage'] ?>% <?php echo __d('languages', 'Complete') ?>
								(<?php echo $translation['completed'] ?> / <?php echo $translation['total'] ?>)
							</div>
							<div class="col-lg-3">
								<div class="progress progress-striped active" style="margin-bottom: 0;">
  									<div class="progress-bar"  role="progressbar" aria-valuenow="<?php echo $translation['percentage'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $translation['percentage'] ?>%">
    									<span class="sr-only"><?php echo $translation['percentage'] ?>% <?php echo __d('languages', 'Complete') ?></span>
  									</div>
								</div>
							</div>
						</div>
					</a>
				</h4>
			</div>
			<div id="collapse-<?php echo $translation['id'] ?>" class="panel-collapse collapse">
				<div class="panel-body">
	        		<table class="table table-striped table-condensed">
						<tbody>

							<ul class="nav nav-pills">
								<li class="active"><a href="#empty-<?php echo $translation['id'] ?>" data-toggle="tab"><?php echo __d('languages', 'Empty') ?></a></li>
								<li><a href="#filled-<?php echo $translation['id'] ?>" data-toggle="tab"><?php echo __d('languages', 'Completed') ?></a></li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane active" id="empty-<?php echo $translation['id'] ?>">
									<br />
									<ul class="list-group">
									<?php foreach($translation['strings']['empty'] as $string) : ?>
										<li class="list-group-item">
											<pre><?php echo $string['msgid'] ?></pre>
											<div class="input-group">
  												<input type="text" value="<?php echo $string['msgstr'] ?>" class="form-control" data-path="<?php echo $translation['path'] ?>" data-id="<?php echo $string['msgid'] ?>" />
  												<span class="input-group-addon"></span>
											</div>
										</li>
									<?php endforeach; ?>
									</ul>

								</div>
								<div class="tab-pane" id="filled-<?php echo $translation['id'] ?>">
									<br />
									<ul class="list-group">
									<?php foreach($translation['strings']['filled'] as $string) : ?>
										<li class="list-group-item">
											<pre><?php echo $string['msgid'] ?></pre>
											<div class="input-group">
  												<input type="text" value="<?php echo $string['msgstr'] ?>" class="form-control" data-path="<?php echo $translation['path'] ?>" data-id="<?php echo $string['msgid'] ?>" />
  												<span class="input-group-addon"></span>
											</div>
										</li>
									<?php endforeach; ?>
									</ul>

								</div>
							</div>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<script type="text/javascript">
$(function () {
	$('input[type="text"]').change(function () {
		console.log($(this).val());
		if ($.trim($(this).val()) == '') return false;
		$input = $(this);
		$input.parent().find('.input-group-addon:first').html('<i class="fa fa-refresh"></i>');
		var uri = '<?php echo $this->Html->url(array('action' => 'change_string', 'ext' => 'json')) ?>';
		$.getJSON(uri, { path: $(this).attr('data-path'), id: $(this).attr('data-id'), value: $(this).val() }, function (data) {
			if (data.status == 'success') {
				$input.parent().find('.input-group-addon:first').html('<i class="fa fa-check"></i>');
			} else {
				$input.parent().find('.input-group-addon:first').html('<i class="fa fa-times"></i>');
			}
			console.log(data);
		});
	});
});
</script>
