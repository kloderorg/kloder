<?php
$this->Html->script(array('select/select2'), array('block' => 'script'));
$this->Html->css(array('select/select2', 'select/select2-bootstrap'), null, array('block' => 'css'));

if (!array_key_exists('plugin', $options)) $options['plugin'] = null;
if (!array_key_exists('controller', $options)) $options['controller'] = null;
if (!array_key_exists('model', $options)) $options['model'] = null;
if (!array_key_exists('rowaccess_model', $options)) $options['rowaccess_model'] = null;
?>

<script type="text/javascript">
$(function () {
	$("#rowaccess-who").select2({
	    placeholder: '<?php echo __d('rowaccess', 'Search for users') ?>',
	    minimumInputLength: 3,
	    ajax: {
	        url: "<?php echo $this->Html->url(array('plugin' => 'users', 'controller' => 'users', 'action' => 'index', 'ext' => 'json')) ?>",
	        dataType: 'json',
	        quietMillis: 100,
	        multiple: true,
	        data: function (term, page) {
	            return {
	                q: term,
	                page: page,
	                page_limit: 10,
	            };
	        },
	        results: function (data, page) {
	            var more = (page * 10) < data.length;
	            return { results: data, more: more };
	        }
	    },
	    id: function (item) {
	    	return item.User.id;
	    },
	    formatResult: userFormatResult, // omitted for brevity, see the source of this page
	    formatSelection: userFormatSelection,  // omitted for brevity, see the source of this page
	    initSelection : function (element, callback) {
	    	console.log(element);
            var elementText = $(element).attr('data-init-text');

            callback({"term":elementText});

        },
	    escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
	});

	function userFormatResult(item) {
		var markup = '<div class="pull-left" style="width: 36px;text-align: center;">';
		if (item.User.thumb != undefined && item.User.thumb != null && item.User.thumb != '') {
			markup += '<img src="<?php echo $this->Html->url('/files/user/thumb/') ?>'+item.User.thumb_dir+'/'+item.User.thumb+'" alt="<?php echo __('Avatar') ?>" class="img-rounded" style="height:28px;" />';
		} else {
			markup += '<i class="fa fa-user fa-fw" style="font-size: 28px; line-height: 28px;"></i>';
		}
		markup += '</div><div style="margin-left: 42px; line-height: 16px;">';
		markup += item.User.name+' '+item.User.last_name+'<br />';
		markup += '<span class="text-muted">'+item.User.email+'</span>';
		markup += '</div>';
		markup += '<div style="clear:both;"></div>';
        return markup;
	}

	function userFormatSelection(item) {
        return item.User.name+' '+item.User.last_name;
    }
});
function rowaccessModal(id) {
	var uri = '<?php echo $this->Html->url(array('plugin' => 'rowaccess', 'controller' => 'rowaccess', 'action' => 'index', $options['model'], $options['plugin'])) ?>/'+id+'.json';
	$.getJSON(uri, function (data) {
		$dialog = $('#rowaccess-<?php echo $options['plugin'] ?>-<?php echo $options['controller'] ?>');
		$dialog.find('#rowaccess-uri').val('<?php echo $this->Html->url(array('plugin' => $options['plugin'], 'controller' => $options['controller'], 'action' => 'view'), true) ?>/'+data.<?php echo $options['model'] ?>.id);
		$dialog.find('#rowaccess-owner').html(_.template($("script.template-rowaccess-user").html(), { item: data }));
		$dialog.find('#model_id').val(id);
		$dialog.find('#rowaccess-lines').html('');
		for(var i=0;i<data.<?php echo $options['rowaccess_model'] ?>.length;i++) {
			$dialog.find('#rowaccess-lines').append(_.template($("script.template-rowaccess-user").html(), { item: data.<?php echo $options['rowaccess_model'] ?>[i] }));
		}

		$dialog.modal();
	});
}
function rowaccessChange(id) {
	var uri = '<?php echo $this->Html->url(array('plugin' => 'rowaccess', 'controller' => 'rowaccess', 'action' => 'change', $options['rowaccess_model'], $options['plugin'], 'ext' => 'json')) ?>';
	var val = $('div[data-id="'+id+'"] select').val();

	$.post(uri, { id: id, can: val }, function (data) {
		console.log(data);
	}, 'json');
}
function rowaccessDelete(id) {
	var uri = '<?php echo $this->Html->url(array('plugin' => 'rowaccess', 'controller' => 'rowaccess', 'action' => 'delete', $options['rowaccess_model'], $options['plugin'], 'ext' => 'json')) ?>';

	$.post(uri, { id: id }, function (data) {
	}, 'json');
}
function rowaccessAdd() {
	var uri = '<?php echo $this->Html->url(array('plugin' => 'rowaccess', 'controller' => 'rowaccess', 'action' => 'add', $options['rowaccess_model'], $options['model'], $options['plugin'], 'ext' => 'json')) ?>';
	var can = $('#rowaccess-can').val();
	var who = $('#rowaccess-who').val();
	$dialog = $('#rowaccess-<?php echo $options['plugin'] ?>-<?php echo $options['controller'] ?>');
	var id = $dialog.find('#model_id').val();

	$.post(uri, { user_id: who, model_id: id,  can: can }, function (data) {
		$dialog.find('#rowaccess-lines').append(_.template($("script.template-rowaccess-user").html(), { item: data }, 'json'));
	});
}
</script>

<div class="modal fade" id="rowaccess-<?php echo $options['plugin'] ?>-<?php echo $options['controller'] ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo __d('rowaccess', 'Settings for share') ?></h4>
			</div>
			<div class="modal-body">

				<input type="hidden" id="model_id" value="" />

				<?php echo __d('rowaccess', 'Link to share (only accessible for collaborators)') ?><br />
				<input type="text" value="" class="form-control input-sm" id="rowaccess-uri">

				<div style="height:32px;"></div>

				<?php echo __d('rowaccess', 'who has access') ?>
				<hr style="margin: 10px 0;" />

				<div id="rowaccess-owner"></div>
				<div id="rowaccess-lines"></div>

				<div style="height:32px;"></div>
				<?php echo __d('rowaccess', 'Invitar') ?>

				<div class="row">
					<div class="col-lg-9">
						<input type="text" class="form-control input-sm" id="rowaccess-who"></select>
					</div>
					<div class="col-lg-3">
						<select class="form-control input-sm" id="rowaccess-can">
							<option value="0"><?php echo __d('rowaccess', 'Can see') ?></option>
							<option value="1"><?php echo __d('rowaccess', 'Can comment') ?></option>
							<option value="2"><?php echo __d('rowaccess', 'Can edit') ?></option>
						</select>
					</div>
				</div>
				<div style="height:16px;"></div>
				<button type="button" class="btn btn-primary btn-xs" onclick="rowaccessAdd()"><?php echo __d('rowaccess', 'Add') ?></button>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo __d('rowaccess', 'End') ?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/template" class="template-rowaccess-user">
	<div class="row" data-id="<%- item.id %>">
		<div class="col-lg-1">
			<% if (item.User.thumb != undefined && item.User.thumb != null && item.User.thumb != '') { %>
			    <img src="<?php echo $this->Html->url('/files/user/thumb/') ?><%- item.User.thumb_dir %>/<%- item.User.thumb %>" alt="<?php echo __('Avatar') ?>" class="img-rounded" style="height:32px;" />
			<% } else { %>
			    <i class="fa fa-user" style="font-size: 32px; line-height: 32px;"></i>
			<% } %>
		</div>
		<div class="col-lg-7" style="line-height: 18px;">
			<%- item.User.name %> <%- item.User.last_name %>
			<% if (item.User.id == '<?php echo AuthComponent::user('id') ?>') { %>(<?php echo __d('rowaccess', 'you') ?>)<% } %>
			<span class="text-muted"><%- item.User.email %></span>
		</div>
		<div class="col-lg-3" style="line-height: 32px;">
			<% if (item.User.id == '<?php echo AuthComponent::user('id') ?>') { %>
				<?php echo __d('rowaccess', 'Is owner') ?>
			<% } else { %>
				<select class="form-control input-sm" onchange="rowaccessChange('<%- item.id %>')">
					<option value="0" <% if (item.can == 0) { %>selected="selected"<% } %>><?php echo __d('rowaccess', 'Can see') ?></option>
					<option value="1" <% if (item.can == 1) { %>selected="selected"<% } %>><?php echo __d('rowaccess', 'Can comment') ?></option>
					<option value="2" <% if (item.can == 2) { %>selected="selected"<% } %>><?php echo __d('rowaccess', 'Can edit') ?></option>
				</select>
			<% } %>
		</div>
		<div class="col-lg-1" style="line-height: 32px;">
			<% if (item.User.id != '<?php echo AuthComponent::user('id') ?>') { %>
				<a href="javascript:void(0);" onclick="">&times;</a>
			<% } %>
		</div>
	</div>
	<hr style="margin: 10px 0;" />
</script>
