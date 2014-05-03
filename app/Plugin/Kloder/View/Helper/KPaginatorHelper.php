<?php
App::uses('PaginatorHelper', 'View/Helper');
class KPaginatorHelper extends PaginatorHelper {

	public $helpers = array('Html');

    public function sort($key, $title = NULL, $options = array()) {
        if (empty($title)) {
            $title = $key;

            if (strpos($title, '.') !== false) {
                $title = str_replace('.', ' ', $title);
            }

            $title = __(Inflector::humanize(preg_replace('/_id$/', '', $title)));
        }

        $options['escape'] = false;

        if (parent::sortKey() == $key) {
            $dir = parent::sortDir();
            if ($dir == 'asc') $dir = 'desc'; else $dir = 'asc';
            $title .= '&nbsp;<i class="fa fa-sort-'.$dir.'"></i>';
        } else {
            $title .= '&nbsp;<i class="fa fa-sort"></i>';
        }

        $out = parent::sort($key, $title, $options);
        return $out;
    }

}
