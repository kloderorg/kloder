<?php
class FeedController extends KloderAppController {

	var $name = 'Feed';
	var $uses = '';

	function articles($tags = '') {
		$this->loadModel('ResourcesArticle');
		$this->ResourcesArticle->Behaviors->attach('Tags.Taggable');

		if ($tags != '') $articles = $this->ResourcesArticle->find('all', array('conditions' => array('tags' => $tags)));
		else  $articles = $this->ResourcesArticle->find('all');

		$this->set(compact('articles'));
	}

}
