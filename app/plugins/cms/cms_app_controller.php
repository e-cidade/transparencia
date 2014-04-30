<?php

class CmsAppController extends AppController {

	public $helpers = array(
		'Html', 'Form', 'Ajax'
	);

	public $layout = 'cms';

	public $components = array('RequestHandler');

	# Método para poupar escrita de setFlash.
	protected function setFlash($msg = '', $class = '') {
		$this->Session->setFlash($msg, 'default', array('class' => $class));
	}

	protected function isAdmin($user = null) {
		$compar = $user!==null ? $user : $this->Session->read('Auth.User.login');
		return !!$this->Auth->user() && ($compar == Configure::read('Admin.login'));
	}

}

?>