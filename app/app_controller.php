<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {
	
	var $uses       = array('Cms.Visitante', 'Cms.Configuracao');
	
	var $helpers    = array('Session', 'Html', 'Crumb');

	public $components = array(
		'Auth' => array(
			'authorize' => 'actions',
            'loginAction' => array(
            	'plugin' => 'cms',
                'controller' => 'users',
                'action' => 'login'
            ),
            'loginRedirect' => array(
            	'plugin' => 'cms',
                'controller' => 'dashboard',
                'action' => 'index'
        	),
            'userModel' => 'Cms.User',
            'fields' => array('username' => 'login', 'password' => 'password')
         ),
		'Session'
	);

	protected $allowedMethods = array();
	
	function beforeFilter() {
		
		if (!$this->Auth->user() && !in_array($this->action, $this->allowedMethods) && $this->plugin) {
			$this->redirect($this->Auth->loginAction);
		} else {
			$this->Auth->allow('*');
		}
		
		/*
		 * Contador de visitantes
		 * Soma 1 quando a sessão não estiver inicializada
		 * */
		
		if ($this->Session->read('lVisitante') == '') {
			
			$this->Visitante->addVisitante();
			
		}
		
		$this->Session->write('lVisitante', 'true');
 	  $this->set('iNumeroVisitantes', $this->Visitante->getVisitantes());
 	  $this->set('lNumeroVisitantes', $this->Configuracao->getConfiguracoes());
	}
	
	/**
	* Retorna um array no formato JSON ou em array
	*
	* @param array $lists Array passado
	* @param boolean $encode True para retornar no formato JSON
	* @access protected
	* @return mixed
	*/
	protected function json($lists, $encode = true) {
    if (is_array($lists)) {
    	$aToReturn = array();

      foreach ($lists as $index => $value) {
        $aToReturn[utf8_encode($index)] = is_array($value) ? $this->json($value, false) : utf8_encode($value);
      }

      return $encode ? json_encode($aToReturn) : $aToReturn;
    }

    return utf8_encode($lists);
  }

}
