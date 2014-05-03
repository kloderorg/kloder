<?php
$comment_model = Inflector::pluralize($model).'Comment';
$items = $item[$comment_model];
$foreign_key = $item[$model]['id'];
if (!isset($redirect_field)) $redirect_field = 'foreign_key';
if ($redirect_field != 'foreign_key') $redirect_value = $item[$model][$redirect_field]; else $redirect_value = '';
?>

<h2><?php echo __d('comments', 'Comments') ?> (<?php echo count($items) ?>)</h2>

<?php foreach ($items as $comment) : ?>
	<?php echo $this->element('Comments.list_item', array('comment' => $comment, 'model' => $model)) ?>
<?php endforeach; ?>
<?php echo $this->element('Comments.form', array('foreign_key' => $foreign_key, 'redirect_field' => $redirect_field, 'redirect_value' => $redirect_value)) ?>
