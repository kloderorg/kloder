<?php echo $this->Html->script(array('ajaxq')) ?>
<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('resources', 'Trash') ?> <small><?php echo __d('resources', 'Resources') ?></small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-folder"></i> '.__d('resources', 'Resources'), array('controller' => 'resources', 'action' => 'index'), array('escape' => false)) ?></li>
			<li class="active"><?php echo __d('resources', 'Trash') ?></li>
		</ol>
	</div>
	<div class="col-lg-12">
		<div class="btn-group">
			<a href="javascript:void(0);" onclick="reload()" class="btn btn-default" id="btn-reload"><i class="fa fa-refresh"></i></a>
		</div>

		<div class="btn-group">
			<a href="javascript:void(0);" onclick="action_restore()" class="btn btn-primary" id="btn-restore"><i class="fa fa-trash-o"></i> <?php echo __d('resources', 'Restore') ?></a>
		</div>

		<div class="btn-group">
			<a href="javascript:void(0);" onclick="action_delete()" class="btn btn-danger" id="btn-delete"><i class="fa fa-trash-o"></i> <?php echo __d('resources', 'Trash') ?></a>
		</div>

		<div class="btn-group pull-right">
			<?php echo $this->Html->link('<i class="fa fa-mail-reply-all fa-fw"></i> '.__d('resources', 'Back'), array('action' => 'index'), array('class' => 'btn btn-default', 'escape' => false)) ?>
		</div>
	</div>
</div>

<div style="clear:both; height: 16px;"></div>

<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th><input type="checkbox" class="resources-select-all" /></th>
			<th class="col-sm-9"><?php echo __d('resources', 'Title') ?></th>
			<th class="col-sm-1"><?php echo __d('resources', 'Owner') ?></th>
			<th class="col-sm-2"><?php echo __d('resources', 'Last Update') ?></th>
        </tr>
    </thead>
    <tbody id="icarus"></tbody>
</table>

<script type="text/javascript">
$(function () {
	reload();
	$('.resources-select').live('change', function () {
		actions_visibility();
	});
	$('.resources-select-all').change(function () {
		if ($('.resources-select-all').is(":checked")) $('.resources-select:checkbox:not(:checked)').attr('checked', 'checked');
		else $('.resources-select:checkbox:checked').removeAttr('checked');
		actions_visibility();
	});
});

function action_restore() {
	$('.resources-select:checkbox:checked').each(function (index, value) {
		var id = $(this).parent().parent().attr('data-id');
		var uri = $(this).parent().parent().attr('data-action-uri')+'/restore/'+id+'.json';
		$.getJSON(uri, function (data) {
			if (data.status == 'success') {
				$('tr[data-id="'+id+'"]').fadeOut('fast', function () {
					$(this).remove();
				});
			}
		});
	});
}

function action_delete() {
	$('.resources-select:checkbox:checked').each(function (index, value) {
		var id = $(this).parent().parent().attr('data-id');
		var uri = $(this).parent().parent().attr('data-action-uri')+'/delete/'+id+'/true.json';
		$.getJSON(uri, function (data) {
			if (data.status == 'success') {
				$('tr[data-id="'+id+'"]').fadeOut('fast', function () {
					$(this).remove();
				});
			}
		});
	});
}

function actions_visibility() {
	if ($('.resources-select:checkbox:checked').length > 0) $('#resources-actions').show();
	else $('#resources-actions').hide();
}

function reload() {
	$('#icarus').html('');
	$.ajaxq.clear("icarus");
	$('#btn-reload').html('<i class="fa fa-refresh fa-spin"></i>');

	// List of folders
	$.ajaxq("icarus", {
        url: '<?php echo $this->Html->url(array('controller' => 'resources_folders', 'action' => 'trash', 'ext' => 'json')) ?>',
        dataType: 'json',
        type: "GET",
        success: function(data) {
            $.each(data, function (key, folder) {
				$('#icarus').append(_.template($("script.template-folder").html(), { folder: folder }));
			});
        }
    });

    // List of files
	$.ajaxq("icarus", {
        url: '<?php echo $this->Html->url(array('controller' => 'resources_files', 'action' => 'trash', 'ext' => 'json')) ?>',
        dataType: 'json',
        type: "GET",
        success: function(data) {
            $.each(data, function (key, file) {
            	if (file.ResourcesFile.class == 'link')
					$('#icarus').append(_.template($("script.template-link-generic").html(), { file: file }));
				else if (file.ResourcesFile.class == 'file') {
					if ($("script.template-file-"+file.ResourcesFile.type.replace("/","-")).length > 0)
						$('#icarus').append(_.template($("script.template-file-"+file.ResourcesFile.type.replace("/","-")).html(), { file: file }));
					else
						$('#icarus').append(_.template($("script.template-file-generic").html(), { file: file }));
				} else {
					if (file.ResourcesFile.class != null) {
						$('#icarus').append(_.template($("script.template-"+file.ResourcesFile.class).html(), { file: file, folder_id: '<?php echo $folder_id ?>' }));
					}
				}
			});
			$('#btn-reload').html('<i class="fa fa-refresh"></i>');
        }
    });
}
</script>

<?php echo $this->element('Resources.templates') ?>
<?php foreach (Configure::read('Resources.plugins') as $plugin) echo $this->element($plugin.'.templates'); ?>
