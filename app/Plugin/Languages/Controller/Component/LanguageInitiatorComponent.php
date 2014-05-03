<?php
App::uses('Component', 'Controller');
class LanguageInitiatorComponent extends Component {

	public function initialize(Controller $controller) {
        if (AuthComponent::user('id') == null) {
            $this->setFromBrowser();
        } else {
            if (AuthComponent::user('language_id') != '') {
                Configure::write('Config.language', AuthComponent::user('language_id'));
                Configure::write('Language.alternative', $this->getAlternative(AuthComponent::user('language_id')));
            } else {
                $this->setFromBrowser();
            }
        }
    }

    public function setFromBrowser() {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        switch ($lang) {
            case "es":
                Configure::write('Config.language', 'es_ES');
                Configure::write('Language.alternative', $this->getAlternative('es_ES'));
                break;
            default:
                Configure::write('Config.language', 'en_US');
                Configure::write('Language.alternative', $this->getAlternative('en_US'));
                break;
        }
    }

    public function getAlternative($lang) {
    	if ($lang == 'es_ES') return 'es';
    	if ($lang == 'en_US') return 'en';
    	return 'en';
    }

}
