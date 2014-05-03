<?php
App::uses('AppController', 'Controller');
class DashboardController extends AppController {

	public $uses = array();

	public function index() {
		$this->loadModel('Finances.FinancesCompany');
		$company = $this->FinancesCompany->find('first');
		if (empty($company)) $this->set('finances', false);
		else $this->set('finances', true);

		$this->loadModel('Projects.Project');
		$project = $this->Project->find('first');
		if (empty($project)) $this->set('projects', false);
		else $this->set('projects', true);
	}

}
