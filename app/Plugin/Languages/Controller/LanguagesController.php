<?php
class LanguagesController extends LanguagesAppController {

	var $name = 'Languages';

	function index() {
		$languages = $this->paginate('Language');
		$this->set(compact('languages'));
	}

	function add() {
		if (!empty($this->request->data)) {
			if ($this->Language->save($this->request->data)) {
				$this->Session->setFlash(__d('languages', 'Your language has been saved.'), 'messages/success');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	function edit($id = null) {
		if (!empty($this->request->data)) {
			if ($this->Language->save($this->request->data)) {
				$this->Session->setFlash(__d('languages', 'Your language has been saved.'), 'messages/success');
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->request->data = $this->Language->findById($id);
	}

	public function delete($id) {
		$language = $this->Language->findById($id);
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Language->delete($this->request->data['Language']['id']);
			$this->Session->setFlash(__d('languages', 'Language deleted'), 'messages/success');
			$this->redirect(array('action'=>'index'));
		}
		if (!$this->request->data) $this->request->data = $language;
	}

	function change_string() {
		$path = $this->request->query['path'];
		$id = $this->request->query['id'];
		$value = $this->request->query['value'];

		$content = file_get_contents($path);
		$lines = explode(PHP_EOL, $content);

		$out = '';
		for($i=0;$i<count($lines);$i++) {
			if ($lines[$i] == 'msgid "'.$id.'"') {
				$out .= $lines[$i++].PHP_EOL;
				$lines[$i] = 'msgstr "'.$value.'"';
				$out .= $lines[$i].PHP_EOL;
			} else {
				$out .= $lines[$i].PHP_EOL;
			}
		}

		if (file_put_contents($path, $out) === FALSE) {
			$this->set('result', array(
				'status' => 'error',
				'message' => __d('languages', 'Error writing to .po file')
			));
		} else {
			$this->set('result', array(
				'status' => 'success',
				'data' => null
			));
		}

		App::import('Vendor','Languages.php-mo');
		phpmo_convert($path);
	}

	function translate($id = null) {
		$language = $this->Language->findById($id);
		$translations = array();

		$path = APP . 'Locale' . DS . $language['Language']['code'] . DS . 'LC_MESSAGES';
		$translations = array_merge($translations, $this->_read_path($path, $language, ''));

		$plugins = App::objects('plugin');
		foreach ($plugins as $plugin) {
			$path = APP . 'Plugin' . DS . $plugin . DS . 'Locale' . DS . $language['Language']['code'] . DS . 'LC_MESSAGES';
			$translations = array_merge($translations, $this->_read_path($path, $language, $plugin));
		}

		$this->set(compact('translations', 'language'));
	}

	private function _read_path($path, $language, $plugin) {
		$translations = array();
		$files = scandir($path);
		foreach ($files as $file) {
			if ($file == '.' || $file == '..' || $file == 'empty') continue;
			$parts = pathinfo($path . DS . $file);
			if ($parts['extension'] == 'po') {
				$translation = array('file' => $file);
				$translation = array_merge($translation, $this->_read_po($path . DS . $file, $language));
				$translation = array_merge($translation, array(
					'percentage' => round($translation['completed'] * 100 / $translation['total']),
					'plugin' => $plugin,
					'id' => md5($path . DS . $file),
					'path' => $path . DS . $file
				));
				array_push($translations, $translation);
			}
		}
		return $translations;
	}

	private function _read_po($path, $language) {
		$content = file_get_contents($path);
		preg_match_all('/msgid "(.*)"/', $content, $msgid);
		preg_match_all('/msgstr "(.*)"/', $content, $msgstr);
		$out = array('empty' => array(), 'filled' => array());
		$counter = 0;
		$total = 0;
		for ($i=0;$i<count($msgid[1]);$i++) {
			if (trim($msgid[1][$i]) == '') continue;
			$tmp = array('msgid' => $msgid[1][$i], 'msgstr' => $msgstr[1][$i]);
			if ($msgstr[1][$i] == '') array_push($out['empty'], $tmp);
			else { array_push($out['filled'], $tmp); $counter++; }
			$total++;
		}
		return array(
			'strings' => $out,
			'completed' => $counter,
			'total' => $total
		);
	}

	function save_language_file($id, $file) {

	}
}
?>
