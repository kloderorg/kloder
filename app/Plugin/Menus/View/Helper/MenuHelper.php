<?php
class MenuHelper extends AppHelper {

	var $helpers = array('Html');

    function generate($menus) {
    	$out = $this->Html->css(array('/platform/css/menu'));
    	$out .= '<ul>';
		foreach($menus as $item) $out .= $this->get_menu_item_code($item);
		$out .= '</ul>';

		$script = "
			$(function () {
				if (window.location.hash != '') {
					$('.hash-link').removeClass('active');
					$.each('.hash-link', function () {
						var href = $(this).attr('href');
						if (href = window.location.hash) {
							$(this).addClass('active');
						}
					});
				}
			});
		";

		$out .= $this->Html->scriptBlock($script, array('inline' => true));
		return $out;
    }

	function get_menu_item_code($item, $level = 0) {
		if ($item['MenuItem']['disabled']) return '';
		$class = ''; if ($this->is_menu_item_selected($item)) $class = 'active';

		if ($item['MenuItem']['title'] == '---') {
			$out = '<li><hr/></li><li style="height:30px;"></li>';
			return $out;
		}

		$out = '<li>';

		if (!empty($item['children'])) {
			$out .= '<div onclick="$(\'#menu-item'.$item['MenuItem']['id'].'\').slideToggle(\'fast\')" style="height:8px;padding:8px;float:right;">';
			$out .= $this->Html->image('/platform/img/menu/triangle.png', array('alt' => __d('platform', 'Open/Close submenu'), 'title' => __d('platform', 'Open/Close submenu')));
			$out .= '</div>';
		}

		$parse_url = parse_url($this->Html->url('/', true).$item['MenuItem']['ubication']);
		$script = '';
		if (!empty($parse_url['fragment']) && $level == 1) {
			$class .= ' hash-link';
			$script = "$('.hash-link').removeClass('active'); $(this).addClass('active');";
		} else if (!empty($parse_url['fragment']) && $level == 0) {
			$script = "$('.hash-link').removeClass('active');";
		}
		$out .= '<a href="'.$this->Html->url('/').$item['MenuItem']['ubication'].'" class="'.$class.'" onclick="'.$script.'">';
		if ($item['MenuItem']['icon']) {
			$out .= '<img src="'.$this->Html->url($item['MenuItem']['icon']).'" title="'.$item['MenuItem']['title'].'" alt="'.$item['MenuItem']['title'].'" align="top"/>';
		}

		$name = $this->FormExtended->readMultilanguage($item['MenuItem']['title']);
		if (trim($name) == '%PROJECT_SHORT_NAME%') $name = ucfirst(Configure::read('platform.current_project_short_name'));

		$out .= '&nbsp;'.$name;
		$out .= '</a>';

		$style = ''; if (!$this->is_menu_item_selected($item)) $style = ' style="display:none"';
		$out .= '<ul id="menu-item'.$item['MenuItem']['id'].'"'.$style.'>';
		foreach ($item['children'] as $subitem) $out .= $this->get_menu_item_code($subitem, $level+1);
		$out .= '</ul>';

		$out .= '</li>';
		return $out;
	}

	function is_menu_item_selected($item) {
		$rast = substr($_SERVER["REQUEST_URI"], strlen($this->request->base));
		$rast = substr($rast, 1);

		//debug(apache_request_headers());
		//if (isset($_COOKIE['actual-folder-slug'])) $rast .= $_COOKIE['actual-folder-slug'];*/
		//debug($item['MenuItem']['ubication'].' == '.$this->request->base);
		if ($item['MenuItem']['ubication'] == $rast)
			return true;

		foreach ($item['children'] as $subitem)
			if ($this->is_menu_item_selected($subitem))
				return true;

		if ($item['MenuItem']['extra_ubications'] == '') return false;;

		$ubis = explode(",", $item['MenuItem']['extra_ubications']);
		foreach ($ubis as $ubi)
			if ($ubi == $this->request->url)
				return true;

		return false;
	}

}
?>
