<!-- Libs -->
<?php
function getPermissions($path) {
	$perms = fileperms($path);
	if (($perms & 0xC000) == 0xC000) {
	    // Socket
	    $info = 's';
	} elseif (($perms & 0xA000) == 0xA000) {
	    // Symbolic Link
	    $info = 'l';
	} elseif (($perms & 0x8000) == 0x8000) {
	    // Regular
	    $info = '-';
	} elseif (($perms & 0x6000) == 0x6000) {
	    // Block special
	    $info = 'b';
	} elseif (($perms & 0x4000) == 0x4000) {
	    // Directory
	    $info = 'd';
	} elseif (($perms & 0x2000) == 0x2000) {
	    // Character special
	    $info = 'c';
	} elseif (($perms & 0x1000) == 0x1000) {
	    // FIFO pipe
	    $info = 'p';
	} else {
	    // Unknown
	    $info = 'u';
	}
	
	// Owner
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
	            (($perms & 0x0800) ? 's' : 'x' ) :
	            (($perms & 0x0800) ? 'S' : '-'));
	
	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
	            (($perms & 0x0400) ? 's' : 'x' ) :
	            (($perms & 0x0400) ? 'S' : '-'));
	
	// World
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
	            (($perms & 0x0200) ? 't' : 'x' ) :
	            (($perms & 0x0200) ? 'T' : '-'));
	
	return $info;
}

function getOwnerAndGroup($path) {
	if (function_exists('posix_getpwuid')) $owner_data = posix_getpwuid(fileowner($path)); else return'';
	if (function_exists('posix_getgrgid')) $group_data = posix_getgrgid(filegroup($path)); else return'';
	return $owner_data['name'].':'.$group_data['name'];
}
?>
<style>
.correct { color: #009900; }
.wrong { color: #990000; }
.bold { font-weight: bold; }
</style>
<!-- END:Libs -->

<!-- Cabecera -->
<div class="inner-header">
	<div class="title"><?php echo $this->Html->image('/platform/img/icons/big/system_check.png', array('alt' => __('Settings', true), 'align' => 'top')); ?> <?php echo __('System Check') ?></div>
</div>
<!-- FIN: Cabecera -->

<table class="top-bar"><tr><td class="left"></td><td class="middle"></td><td class="right"></td></tr></table>

<table class="list">
	<tr><th>Propertie</th><th>Value</th><th>Description</th></tr>
	<tr>
		<td class="bold">Safe mode</td>
		<td class="bold"><?php echo ini_get('safe_mode') ? '<span class="wrong">Yes</span>' : '<span class="correct">No</span>'; ?></td>
		<td>Must be 'No' for the storage system can rise to more than one directory of deep.</td>
	</tr>
	<tr>
		<td class="bold">Register globals</td>
		<td class="bold"><?php echo ini_get('register_globals') ? '<span class="wrong">Yes</span>' : '<span class="correct">No</span>'; ?></td>
		<td>Needed to be 'NO' for a security reason (<a href="http://php.net/manual/es/security.globals.php" target="_blank">more</a>).</td>
	</tr>
	<tr>
		<td class="bold">Upload max filesize</td>
		<td class="bold"><?php echo ini_get('upload_max_filesize') ? '<span class="correct">'.ini_get('upload_max_filesize').'</span>' : '<span class="wrong">No</span>'; ?></td>
		<td>The amount of data that can be transfer to the system by the user.</td>
	</tr>
	<tr>
		<td class="bold">Display errors</td>
		<td class="bold"><?php echo ini_get('display_errors') ? 'Yes' : 'No'; ?></td>
		<td>Show errors or not.</td>
	</tr>
</table>
<div style="height: 10px"></div>
<table class="list">
	<tr><th>Directory</th><th>Permissions</th><th>Description</th></tr>
	<?php $dir = APP.'tmp'.DS.'cache'.DS; ?>
	<tr>
		<td class="bold"><?php echo $dir ?></td>
		<td class="bold"><?php echo '<span class="'.(is_writable($dir) ? 'correct' : 'wrong').'">'.getPermissions($dir).' '.getOwnerAndGroup($dir).'</span>'; ?></td>
		<td>For temporaly data</td>
	</tr>
	<?php $dir = APP.'tmp'.DS.'logs'.DS; ?>
	<tr>
		<td class="bold"><?php echo $dir ?></td>
		<td class="bold"><?php echo '<span class="'.(is_writable($dir) ? 'correct' : 'wrong').'">'.getPermissions($dir).' '.getOwnerAndGroup($dir).'</span>'; ?></td>
		<td>For store logs.</td>
	</tr>
	<?php $dir = APP.'tmp'.DS.'sessions'.DS; ?>
	<tr>
		<td class="bold"><?php echo $dir ?></td>
		<td class="bold"><?php echo '<span class="'.(is_writable($dir) ? 'correct' : 'wrong').'">'.getPermissions($dir).' '.getOwnerAndGroup($dir).'</span>'; ?></td>
		<td>For store sessions (only if is configure, by default use PHP sessions store system).</td>
	</tr>
	<?php $dir = WWW_ROOT.'files'.DS; ?>
	<tr>
		<td class="bold"><?php echo $dir ?></td>
		<td class="bold"><?php echo '<span class="'.(is_writable($dir) ? 'correct' : 'wrong').'">'.getPermissions($dir).' '.getOwnerAndGroup($dir).'</span>'; ?></td>
		<td>File system storage.</td>
	</tr>
</table>

<table class="bottom-bar"><tr><td class="left"></td><td class="middle"></td><td class="right"></td></tr></table>