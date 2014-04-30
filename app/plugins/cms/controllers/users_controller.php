<?php

class UsersController extends CmsAppController {
	
	public $name = 'Users';

	public $allowedMethods = array(
		'login', 'logout'
	);

	public function beforeFilter() {

		parent::beforeFilter();

		if (!$this->isAdmin() && !in_array($this->action, $this->allowedMethods)) {
			$this->setFlash('Você não possui acesso aos usuários.', 'alert alert-info');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}

	public function index() {
		$this->set('users', $this->paginate());
	}

	public function login() {

		if ($this->Auth->user()) {
			$this->setFlash('Você já está logado.', 'alert alert-info');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}

		if (!empty($this->data)) {

			if ($this->Auth->login()) {
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			} else {
				$this->setFlash('Erro ao tentar acessar. Verifique seu usuário e senha.', 'alert alert-error');
			}
		}
	}

	public function logout() {
		$this->Auth->logout();

		$this->redirect('index');
	}

	public function edit($id = null) {
		$data =& $this->data;

		if (!empty($data)) {

			$userId = $this->Session->read('Auth.User.id');
			if ($userId) {
				$data['User']['user_id'] = $userId;
			}
			
			$this->User->set($data);

			if ($this->User->validates()) {
					try {
					$this->User->save();
					$this->setFlash('Usuário salvo com sucesso', 'alert alert-success');
					$this->redirect('index');
				} catch (PDOException $e) {
					$this->setFlash('Falha ao salvar o usuário. Tente novamente.', 'alert alert-error');
				}
			} else {
				$this->setFlash('Alguns campos estão inválidos.', 'alert alert-error');
			}

		} else {
			if ($id) {
				$data = $this->User->read(null, $id);
			}

		}
		
		unset($data['User']['password']);
	}

	public function delete($id) {
		$this->User->id = $id;

		if ($this->isAdmin($this->User->field('login'))) {
			$this->setFlash('Você não pode apagar o usuário administrador.', 'alert alert-info');
		} else {
			try {
				$this->User->delete();
				$this->setFlash('Usuário apagado com sucesso.', 'alert alert-success');
			} catch (PDOException $e) {
				$this->setFlash('Não foi possível apagar o usuário. Tente novamente.', 'alert alert-error');
			}
		}

		$this->redirect('index');
	}
}

?>