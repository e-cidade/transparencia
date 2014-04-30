<?php

class User extends CmsAppModel {
	
	public $name = 'User';

	public $useTable = 'users';

	public $order = 'name ASC';

	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'message' => 'Nome não pode ficar em branco.'
		),
		'login' => array(
			'unique' => array(
		        'rule' => 'isUnique',
		        'message' => 'O e-mail informado já está cadastrado.'
		    ),
		    'email' => array(
				'rule' => 'email',
				'message' => 'O e-mail deve ser válido.'
			)
		),
		'password' => array(
			'rule' => array('minLength', 6),
			'message' => 'A senha deve ter ao menos 6 caracteres.'
		)
	);

	public function hashPasswords($data) {
		if (!isset($data['User']['name'])) {
			$data[$this->alias]['password'] = Security::hash($data[$this->alias]['password']);
		}
		return $data;
	}

}

?>