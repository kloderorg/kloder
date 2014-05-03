<?php $first = $this->Paginator->first(
	'&laquo; '.__('First'),
	array('class' => '', 'tag' => 'li', 'escape' => false),
	null,
	array('class' => 'disabled', 'tag' => 'li', 'escape' => false, 'disabledTag' => 'span')
) ?>
<?php $last = $this->Paginator->last(
	__('Last').' &raquo;',
	array('class' => '', 'tag' => 'li', 'escape' => false),
	null,
	array('class' => 'disabled', 'tag' => 'li', 'escape' => false, 'disabledTag' => 'span')
) ?>

<?php $prev = $this->Paginator->prev(
	'&laquo;',
	array('class' => '', 'tag' => 'li', 'escape' => false),
	null,
	array('class' => 'disabled', 'tag' => 'li', 'escape' => false, 'disabledTag' => 'span')
) ?>
<?php $next = $this->Paginator->next(
	'&raquo;',
	array('class' => '', 'tag' => 'li', 'escape' => false),
	null,
	array('class' => 'disabled', 'tag' => 'li', 'escape' => false, 'disabledTag' => 'span')
) ?>
<?php echo $this->Paginator->numbers(array(
	'before' => '<ul class="pagination pagination-sm" style="margin:0;">'.$first.$prev,
	'after' => $next.$last.'</ul>',
	'tag' => 'li',
	'currentClass' => 'active',
	'separator' => '',
	'currentTag' => 'span')) ?>
