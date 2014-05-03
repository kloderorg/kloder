<?php
App::uses('HtmlHelper', 'View/Helper');
class KHtmlHelper extends HtmlHelper {

	public $helpers = array('Html');

    public $accordion_id = null;

    public function dialog($id, $options = array()) {
    	$default = array(
    		'name' => __('Open Dialog'),
    		'title' => __('Title'),
    		'close_text' => __('Close'),
    		'content' => __('Content')
    	);
    	$options = array_merge($default, $options);

    	$out = '
    	<!-- Modal -->
		<div class="modal fade" id="'.$id.'" style="text-align: left;">
	    	<div class="modal-dialog">
	      		<div class="modal-content">
	        		<div class="modal-header">
	          			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          			<h4 class="modal-title">'.$options['title'].'</h4>
	        		</div>
	        		<div class="modal-body">'.$options['content'].'</div>
	        		<div class="modal-footer">
	          			<button type="button" class="btn btn-default" data-dismiss="modal">'.$options['close_text'].'</button>
	        		</div>
	      		</div><!-- /.modal-content -->
	    	</div><!-- /.modal-dialog -->
	  	</div><!-- /.modal -->
	  	<a href="#'.$id.'" class="btn btn-primary btn-xs" data-toggle="modal">'.$options['name'].'</a>
		';

		return $out;
    }

    public function thumb($img = null, $options = array()) {
    	$options = array_merge(array(
    		'width' => null,
    		'height' => null,
    		'quality' => null,
    		'zoomcrop' => 1
    	), $options);

    	$out = '';

    	$out .= $this->Html->url('/timthumb.php', true);
    	$out .= '?src=' . $img;
    	if ($options['width'] != null) $out .= '&w=' . $options['width'];
    	if ($options['height'] != null) $out .= '&h=' . $options['height'];
    	if ($options['quality'] != null) $out .= '&q=' . $options['quality'];
    	if ($options['zoomcrop'] != null) $out .= '&zc=' . $options['zoomcrop'];

    	$out = $this->Html->image($out, $options);

    	return $out;
    }

    public function accordionStart() {
        App::uses('String', 'Utility');
        $this->accordion_id = String::uuid();
        $out = '<div class="panel-group" id="accordion-'.$this->accordion_id.'">';
        return $out;
    }

    public function accordionBlock($title = "", $content = "", $options = array()) {
        $options = array_merge(array(
            'style' => 'default',
            'collapsed' => true
        ), $options);
        App::uses('String', 'Utility');
        $uuid = String::uuid();
        $out = '<div class="panel panel-'.$options['style'].'">';
        $out .= '<div class="panel-heading">';
        $out .= '<h4 class="panel-title">';
        $out .= '<a data-toggle="collapse" data-parent="#accordion-'.$this->accordion_id.'" href="#collapse-'.$uuid.'">';
        $out .= $title;
        $out .= '</a>';
        $out .= '</h4>';
        $out .= '</div>';

        $out .= '<div id="collapse-'.$uuid.'" class="panel-collapse collapse';
        if (!$options['collapsed']) $out .= ' in';
        $out .= '">';

        $out .= '<div class="panel-body">';
        $out .= $content;
        $out .= '</div>';
        $out .= '</div>';
        $out .= '</div>';
        return $out;
    }

    public function accordionEnd() {
        $out = '</div>';
        return $out;
    }

    public function swipeBox($path = "", $options = array()) {
        $options = array_merge(array(
            'thumb' => array(
                'width' => 350,
                'height' => 200,
                'class' => 'img-responsive'
            ),
            'class' => 'swipebox'
        ), $options);

        $this->Html->script(array('jquery.swipebox'), array('block' => 'script'));
        $this->Html->css('swipebox/swipebox', null, array('block' => 'css'));

        $out = '<a href="'.$this->Html->url($path).'" class="'.$options['class'].'">';
        $out .= $this->thumb($path, $options['thumb']);
        $out .= '</a>';

        $script = "$(function () {
            $('.".$options['class']."').swipebox();
        });";

        $out .= $this->Html->scriptBlock($script);

        return $out;
    }


    /* TODO */
    /*public function hashtag($text, $options = array()) {
        $options = array_merge(array(
            'hash' => 'issue',
            'url' => array('plugin' => 'projects', 'controller' => 'projects_issues', 'hashtag'),
            'options' => array()
        ), $options);

        $url = $options['url'].'/'.

        $out = '<a href="'.$this->Html->url($path).'" class="'.$options['class'].'">';
        $out .= $this->thumb($path, $options['thumb']);
        $out .= '</a>';
    }*/

}
