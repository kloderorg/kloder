<?php
App::uses('FormHelper', 'View/Helper');
class KFormHelper extends FormHelper {

	public $helpers = array('Html');

	public function create($model = null, $options = array()) {
		$options['inputDefaults'] = array(
			'div' => array('class' => 'form-group'),
			'class' => 'form-control',
			'error' => array('attributes' => array('wrap' => 'div', 'class' => 'alert alert-error'))
		);
		$options['role'] = 'form';

		$out = parent::create($model, $options);
		return $out;
	}

	public function dateTime($fieldName, $dateFormat = 'DMY', $timeFormat = '12', $attributes = array()) {
		$this->setEntity($fieldName);
		$options = $this->_parseOptions($attributes);
		$options = array_merge($options, $this->_initInputField($fieldName, $options));
		$type = $options['type'];

		if ($type == 'datetime') {
			$js_format = 'YYYY-MM-DD hh:mm';
			$php_format = 'Y-m-d H:i';
		} else if ($type == 'date') {
			$js_format = 'YYYY-MM-DD';
			$php_format = 'Y-m-d';
		} else if ($type == 'time') {
			$js_format = 'hh:mm';
			$php_format = 'H:i';
		}

		$current = date($php_format);

		$options['div'] = false;
		$options['type'] = 'text';
		$options['label'] = false;
		$options['data-format'] = $js_format;
		$options['class'] = 'form-control';

		if (empty($options['value'])) $options['value'] = $current;
		else $options['value'] = date($php_format, strtotime($options['value']));

		$this->Html->script(array('datetimepicker/moment.min', 'datetimepicker/bootstrap-datetimepicker.min'), array('block' => 'script'));
		$this->Html->css('datetimepicker/bootstrap-datetimepicker.min', null, array('block' => 'css'));

		$optionsParsed = $options;
		unset($optionsParsed['error']);
		$out = '<div class="form-group">';
		//$out .= parent::label($fieldName);
		$out .= '<div class="form-group"><div id="datetimepicker-'.$options['id'].'" class="input-group date">';
		$out .= parent::text($fieldName, $optionsParsed);
		$out .= '<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div></div>';

		$script = "$(function() {
			$('#datetimepicker-".$options['id']."').datetimepicker({
				language: 'es',
				".(($type == 'date') ? "pickTime: false,":"")."
				".(($type == 'time') ? "pickDate: false,":"")."
				icons: {
                    time: 'fa fa-clock-o',
                    date: 'fa fa-calendar',
                    up: 'fa fa-arrow-up',
                    down: 'fa fa-arrow-down'
                }
			});
		});";
		$out .= $this->Html->scriptBlock($script);

		return $out;
	}

	public function color($fieldName, $options = array()) {
		$options = $this->_initInputField($fieldName, $options);

		$options['type'] = 'select';
		$options['no_select'] = true;
		$options['between'] = '<br />';
		if (empty($options['value'])) $options['value'] = '#3a87ad';
		else $options['value'] = $options['value'];
		$options['options'] = array(
			'#3a87ad' => '#3a87ad',
			'#7bd148' => '#7bd148',
			'#a4bdfc' => '#a4bdfc',
			'#46d6db' => '#46d6db',
			'#7ae7bf' => '#7ae7bf',
			'#51b749' => '#51b749',
			'#fbd75b' => '#fbd75b',
			'#ffb878' => '#ffb878',
			'#ff887c' => '#ff887c',
			'#dc2127' => '#dc2127',
			'#dbadff' => '#dbadff',
			'#e1e1e1' => '#e1e1e1'
		);

		$this->Html->script('color/jquery.simplecolorpicker', array('block' => 'script'));
		$this->Html->css(array(
			'color/jquery.simplecolorpicker',
			'color/jquery.simplecolorpicker-fontawesome'
		), null, array('block' => 'css'));

		$out = parent::input($fieldName, $options);

		$script = "$(function() {
			$('#".$options['id']."').simplecolorpicker({
				theme: 'fontawesome'
			});
		});";

		$out .= $this->Html->scriptBlock($script);

		return $out;
	}

	function thumb($fieldName, $options = array()) {
		$options = $this->_initInputField($fieldName, $options);

		$options['div'] = false;
		$options['type'] = 'file';
		$options['label'] = false;
		$options['class'] = 'form-control';

		$out = '<div class="fileinput fileinput-new" data-provides="fileinput">';
		if (!empty($options['value'])) {
			$out .= '<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">';
    		$out .= '<img src="'.$this->Html->url('/files/'.$options['read_dir'].'/'.$options['value']).'" alt="'.__('Thumbnail').'">';
  			$out .= '</div>';
  		}
		$out .= '<div class="fileinput-preview '.((!empty($options['value'])) ? 'fileinput-exists' : '').' thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>';
		$out .= '<div><span class="btn btn-default btn-file"><span class="fileinput-new">'.__('Select image').'</span><span class="fileinput-exists">'.__('Change').'</span>';
		$out .= parent::file($fieldName, $options);
		$out .= '</span><a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">'.__('Remove').'</a></div></div>';

		return $out;
	}

	function file($fieldName, $options = array()) {
		$options = $this->_initInputField($fieldName, $options);

		$out = '<div class="form-group">';
		$out .= parent::label($fieldName);
		$out .= '<div class="fileinput fileinput-new" data-provides="fileinput">
			<div class="input-group">
				<div class="form-control uneditable-input span3" data-trigger="fileinput">
					<i class="fa fa-file fa-fw"></i>
					<span class="fileinput-filename"></span>
				</div>
				<span class="input-group-addon btn btn-default btn-file">
					<span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span>';
		$out .= '<input name="'.$options['name'].'" value="'.$options['value'].'" id="'.$options['id'].'" class="form-control" type="file">';
		$out .= '</span>
	    		<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
	  		</div>
		</div>';
		$out .= '</div>';

		return $out;
	}

	function select($fieldName, $options = array(), $attributes = array()) {
		$attributes = $this->_initInputField($fieldName, $attributes);

		$out = '';

		if (isset($attributes['no_select'])) {
			$out = parent::select($fieldName, $options, $attributes);
		} else {
			if (isset($attributes['class'])) $attributes['class'] .= ' select'; else $attributes['class'] = 'select';
			$attributes['between'] = '<br />';

			$this->Html->script(array('select/select2'), array('block' => 'script'));
			$this->Html->css(array('select/select2', 'select/select2-bootstrap'), null, array('block' => 'css'));

			$out = parent::select($fieldName, $options, $attributes);

			$script = "$(function() {
				$('#".$attributes['id']."').select2();
			});";

			$out .= $this->Html->scriptBlock($script);
		}

		return $out;
	}

	function textarea($fieldName, $options = array()) {
		$options = $this->_initInputField($fieldName, $options);

		if (array_key_exists('editor', $options) && $options['editor'] == false) {
			return parent::textarea($fieldName, $options);
		}

		$this->Html->css(array('codemirror/codemirror', 'codemirror/theme/monokai', 'summernote/summernote'), null, array('block' => 'css'));
		$this->Html->script(array('codemirror/codemirror', 'codemirror/mode/xml/xml', 'summernote/summernote.min', 'summernote/lang/summernote-es-ES'), array('block' => 'script'));

		$out = parent::textarea($fieldName, $options);

		$script = "$(function() {
			$('#".$options['id']."').summernote({
				tabsize: 4,
				lang: 'es-ES',
				codemirror: { // codemirror options
					theme: 'monokai',
					htmlMode: true
				}
			});
		});";

		$out .= $this->Html->scriptBlock($script);

		return $out;
	}

	function comment($fieldName, $options = array()) {
		$options = $this->_initInputField($fieldName, $options);

		$this->Html->css(array('summernote/summernote'), null, array('block' => 'css'));
		$this->Html->script(array('summernote/summernote.min', 'summernote/lang/summernote-es-ES'), array('block' => 'script'));

		$out = parent::textarea($fieldName, $options);

		$script = "$(function() {
			$('#".$options['id']."').summernote({
				tabsize: 4,
				lang: 'es-ES',
				toolbar: [
				    //['style', ['style']], // no style button
				    ['style', ['bold', 'italic', 'underline', 'clear']],
				    ['fontsize', ['fontsize']],
				    ['color', ['color']],
				    ['para', ['ul', 'ol', 'paragraph']],
				    ['height', ['height']],
				    ['insert', ['picture', 'link']], // no insert buttons
				    //['table', ['table']], // no table button
				    //['help', ['help']] //no help button
				]
			});
		});";

		$out .= $this->Html->scriptBlock($script);

		return $out;
	}

	function checkbox($fieldName, $options = array()) {
		$options['class'] = '';
		if (array_key_exists('style', $options)) $options['style'] .= 'margin-right: 5px;';
		else $options['style'] = 'margin-right: 5px;';
		return parent::checkbox($fieldName, $options);
	}

	function code($fieldName, $options = array()) {
		$options = $this->_initInputField($fieldName, $options);

		$this->Html->css(array('codemirror/codemirror', 'codemirror/theme/monokai'), null, array('block' => 'css'));
		$this->Html->script(array('codemirror/codemirror'), array('block' => 'script'));

		$lang = 'javascript';
		if (array_key_exists('lang', $options)) $lang = $options['lang'];

		$this->Html->script(array('codemirror/mode/'.$lang.'/'.$lang), array('block' => 'script'));

		$out = '<div class="form-group">';
		$out .= parent::label($fieldName);
		$out .= parent::textarea($fieldName, $options);
		$out .= '</div>';

		$script = "$(function() {
			var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('".$options['id']."'), {
				theme: 'monokai',
				tabsize: 4,
				indentUnit: 4,
				lineNumbers: true,
				styleActiveLine: true,
    			matchBrackets: true,
				mode: '".$lang."'
			});
		});";

		$out .= $this->Html->scriptBlock($script);

		return $out;
	}

	function radio($fieldName, $options = array(), $settings = array()) {
		$settings = $this->_initInputField($fieldName, $settings);
		$settings['class'] = false;
		//$settings['legend'] = false;
		$settings['before'] = '<div class="radio">';
		$settings['between'] = '<div class="radio">';
		$settings['separator'] = '</div><div class="radio">';
		$settings['after'] = '</div>';
		$out = parent::radio($fieldName, $options, $settings);

		return $out;

	}

}
