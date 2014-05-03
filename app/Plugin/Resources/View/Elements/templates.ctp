<!-- Folder -->

<script type="text/template" class="template-folder">
	<tr data-id="<%- folder.ResourcesFolder.id %>" data-action-uri="<?php echo $this->Html->url(array('controller' => 'resources_folders')) ?>">
		<td><input type="checkbox" class="resources-select" /></td>
		<td><a href="<?php echo $this->Html->url('/resources/resources/index') ?>/<%- folder.ResourcesFolder.id %>"><i class="fa fa-folder fa-fw"></i> <%- folder.ResourcesFolder.name %></a></td>
		<td>
			<% if (folder.User.id == '<?php echo AuthComponent::user('id') ?>') { %>
				<?php echo __d('resources', 'me') ?>
			<% } else { %>
				<%- folder.User.name %>
			<% } %>
		</td>
		<td><%- folder.ResourcesFolder.modified %></td>
	</tr>
</script>

<script type="text/template" class="grid-folder">
	<div data-id="<%- folder.ResourcesFolder.id %>" data-action-uri="<?php echo $this->Html->url(array('controller' => 'resources_folders')) ?>" class="col-lg-2">
		<a href="<?php echo $this->Html->url('/resources/resources/index') ?>/<%- folder.ResourcesFolder.id %>">
			<i class="fa fa-folder fa-fw" style="font-size: 154px;"></i><br />
			<center><%- folder.ResourcesFolder.name %></center>
		</a>
	</div>
</script>

<!-- Back Folder -->

<script type="text/template" class="template-folder-back">
	<tr>
		<td></td>
		<td><a href="<?php echo $this->Html->url('/resources/resources/index') ?>/<%- folder.ResourcesFolder.parent_id %>"><i class="fa fa-mail-reply-all fa-fw"></i> ...</a></td>
		<td></td>
		<td></td>
	</tr>
</script>

<script type="text/template" class="grid-folder-back">
	<div class="col-lg-2">
		<a href="<?php echo $this->Html->url('/resources/resources/index') ?>/<%- folder.ResourcesFolder.parent_id %>">
			<i class="fa fa-mail-reply-all fa-fw" style="font-size: 154px;"></i>
		</a>
	</div>
</script>

<!-- Link File -->

<script type="text/template" class="template-link-generic">
	<tr data-id="<%- file.ResourcesFile.id %>"
		data-action-delete="<?php echo $this->Html->url(array('controller' => 'resources_links', 'action' => 'delete')) ?>/<%- file.ResourcesFile.id %>"
		data-action-edit="<?php echo $this->Html->url(array('controller' => 'resources_links', 'action' => 'edit')) ?>/<%- file.ResourcesFile.id %>">

		<td><input type="checkbox" class="resources-select" /></td>
		<td><a href="<%- file.ResourcesFile.link %>" target="_blank"><i class="fa fa-link fa-fw"></i> <%- file.ResourcesFile.name %> (<%- file.ResourcesFile.link %>)</a></td>
		<td>
			<% if (file.User.id == '<?php echo AuthComponent::user('id') ?>') { %>
				<?php echo __d('resources', 'me') ?>
			<% } else { %>
				<%- file.User.name %>
			<% } %>
		</td>
		<td><%- file.ResourcesFile.modified %></td>
	</tr>
</script>

<script type="text/template" class="grid-link-generic">
	<div data-id="<%- file.ResourcesFile.id %>"
		data-action-delete="<?php echo $this->Html->url(array('controller' => 'resources_links', 'action' => 'delete')) ?>/<%- file.ResourcesFile.id %>"
		data-action-edit="<?php echo $this->Html->url(array('controller' => 'resources_links', 'action' => 'edit')) ?>/<%- file.ResourcesFile.id %>" class="col-lg-2">

		<a href="<%- file.ResourcesFile.link %>" target="_blank">
			<i class="fa fa-link fa-fw" style="font-size: 154px;"></i><br />
			<center><%- file.ResourcesFile.name %> (<%- file.ResourcesFile.link %>)</center>
		</a>
	</div>
</script>

<!-- Generic File -->

<script type="text/template" class="template-file-generic">
	<tr data-id="<%- file.ResourcesFile.id %>"
		data-action-delete="<?php echo $this->Html->url(array('controller' => 'resources_files', 'action' => 'delete')) ?>/<%- file.ResourcesFile.id %>"
		data-action-edit="<?php echo $this->Html->url(array('controller' => 'resources_files', 'action' => 'edit')) ?>/<%- file.ResourcesFile.id %>">

		<td><input type="checkbox" class="resources-select" /></td>
		<td><a href="<?php echo $this->Html->url('/files/resources_file/file/') ?><%- file.ResourcesFile.path %>/<%- file.ResourcesFile.file %>" target="_blank"><i class="fa fa-file fa-fw"></i> <%- file.ResourcesFile.name %></a></td>
		<td>
			<% if (file.User.id == '<?php echo AuthComponent::user('id') ?>') { %>
				<?php echo __d('resources', 'me') ?>
			<% } else { %>
				<%- file.User.name %>
			<% } %>
		</td>
		<td><%- file.ResourcesFile.modified %></td>
	</tr>
</script>

<script type="text/template" class="grid-file-generic">
	<div data-id="<%- file.ResourcesFile.id %>"
		data-action-delete="<?php echo $this->Html->url(array('controller' => 'resources_files', 'action' => 'delete')) ?>/<%- file.ResourcesFile.id %>"
		data-action-edit="<?php echo $this->Html->url(array('controller' => 'resources_files', 'action' => 'edit')) ?>/<%- file.ResourcesFile.id %>" class="col-lg-2">

		<a href="<?php echo $this->Html->url('/files/resources_file/file/') ?><%- file.ResourcesFile.path %>/<%- file.ResourcesFile.file %>" target="_blank">
			<i class="fa fa-file fa-fw" style="font-size: 154px;"></i><br />
			<center><%- file.ResourcesFile.name %></center>
		</a>
	</div>
</script>
