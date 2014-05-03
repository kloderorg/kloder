<?php echo $this->Html->script(array('ajaxq')) ?>
<div class="row">
	<div class="col-lg-12">
		<h1><?php echo __d('resources', 'Administration') ?> <small><?php echo __d('resources', 'Resources') ?></small></h1>
		<ol class="breadcrumb">
			<li class="active"><?php echo '<i class="fa fa-folder"></i> '.__d('resources', 'Resources') ?></li>
		</ol>
	</div>
	<div class="col-lg-12">
		<div class="btn-group">
			<a href="javascript:void(0);" onclick="reload()" class="btn btn-default" id="btn-reload"><i class="fa fa-refresh"></i></a>
		</div>

		<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
				<?php echo __d('resources', 'Add') ?> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<?php echo $this->element('Resources.add_button', array('folder_id', $folder_id)) ?>
				<?php foreach (Configure::read('Resources.plugins') as $plugin) echo $this->element($plugin.'.add_button', array('folder_id', $folder_id)); ?>
			</ul>
		</div>

		<div class="btn-group">
			<a href="#grid" onclick="grid()" class="btn btn-default" id="btn-grid" title="<?php echo __d('resources', 'Grid') ?>"><i class="fa fa-th"></i></a>
		</div><div class="btn-group">
			<a href="#list" onclick="list()" class="btn btn-default" id="btn-list" title="<?php echo __d('resources', 'List') ?>" style="display:none"><i class="fa fa-th-list"></i></a>
		</div>

		<div class="btn-group" id="resources-actions" style="display:none;">
			<a href="javascript:void(0);" onclick="move_to()" class="btn btn-default" id="btn-move-to" title="<?php echo __d('resources', 'Move to') ?>"><i class="fa fa-level-up"></i></a>
			<a href="javascript:void(0);" onclick="action_edit()" class="btn btn-default" id="btn-edit" title="<?php echo __d('resources', 'Edit') ?>"><i class="fa fa-pencil"></i></a>
			<a href="javascript:void(0);" onclick="action_delete()" class="btn btn-default" id="btn-delete" title="<?php echo __d('resources', 'Delete') ?>"><i class="fa fa-trash-o"></i></a>
		</div>
		<div class="btn-group pull-right">
			<?php echo $this->Html->link('<i class="fa fa-trash-o fa-fw"></i> '.__d('resources', 'Trash'), array('action' => 'trash'), array('class' => 'btn btn-default', 'escape' => false)) ?>
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

<div class="row" id="grid-icarus" style="display:none;"></div>

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

	var hash = window.location.hash;
	if (hash == '#grid') grid();
});

function grid() {
	$('#icarus').parent().hide();
	$('#btn-grid').hide();
	$('#grid-icarus').show();
	$('#btn-list').show();
}

function list() {
	$('#grid-icarus').hide();
	$('#btn-list').hide();
	$('#icarus').parent().show();
	$('#btn-grid').show();
}

function action_edit() {
	$('.resources-select:checkbox:checked').each(function (index, value) {
		window.location = $(this).parent().parent().attr('data-action-edit');
		return false;
	});
}

function action_delete() {
	$('.resources-select:checkbox:checked').each(function (index, value) {
		var id = $(this).parent().parent().attr('data-id');
		$.getJSON($(this).parent().parent().attr('data-action-delete'), function (data) {
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

	// Current Folder
	$.ajaxq("icarus", {
        url: '<?php echo $this->Html->url(array('controller' => 'resources_folders', 'action' => 'view', $folder_id, 'ext' => 'json')) ?>',
        dataType: 'json',
        type: 'GET',
        success: function(data) {
        	if (data == undefined || data == null || data.length == 0) return;
        	$('#icarus').append(_.template($("script.template-folder-back").html(), { folder: data[0] }));
        	$('#grid-icarus').append(_.template($("script.grid-folder-back").html(), { folder: data[0] }));
        }
    });

    // List of folders
	$.ajaxq("icarus", {
        url: '<?php echo $this->Html->url(array('controller' => 'resources_folders', 'action' => 'index', $folder_id, 'ext' => 'json')) ?>',
        dataType: 'json',
        type: "GET",
        success: function(data) {
            $.each(data, function (key, folder) {
				$('#icarus').append(_.template($("script.template-folder").html(), { folder: folder }));
				$('#grid-icarus').append(_.template($("script.grid-folder").html(), { folder: folder }));
			});
        }
    });

    // List of files
	$.ajaxq("icarus", {
        url: '<?php echo $this->Html->url(array('controller' => 'resources_files', 'action' => 'index', $folder_id, 'ext' => 'json')) ?>',
        dataType: 'json',
        type: "GET",
        success: function(data) {
            $.each(data, function (key, file) {
            	if (file.ResourcesFile.class == 'link')
					$('#icarus').append(_.template($("script.template-link-generic").html(), { file: file }));
				else if (file.ResourcesFile.class == 'file') {
					if ($("script.template-file-"+file.ResourcesFile.type.replace("/","-")).length > 0) {
						$('#icarus').append(_.template($("script.template-file-"+file.ResourcesFile.type.replace("/","-")).html(), { file: file }));
						$('#grid-icarus').append(_.template($("script.grid-file-"+file.ResourcesFile.type.replace("/","-")).html(), { file: file }));
					} else {
						$('#icarus').append(_.template($("script.template-file-generic").html(), { file: file }));
						$('#grid-icarus').append(_.template($("script.grid-file-generic").html(), { file: file }));
					}
				} else {
					if (file.ResourcesFile.class != null) {
						$('#icarus').append(_.template($("script.template-"+file.ResourcesFile.class).html(), { file: file, folder_id: '<?php echo $folder_id ?>' }));
						$('#grid-icarus').append(_.template($("script.grid-"+file.ResourcesFile.class).html(), { file: file, folder_id: '<?php echo $folder_id ?>' }));
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

