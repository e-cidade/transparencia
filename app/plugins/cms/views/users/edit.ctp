<h1>Usuários</h1>

<div class="">

	<?php 
		echo $this->Form->create('User', array('url' => array('controller' => 'users')));

		echo $this->Form->input('id');

		echo $this->Form->input('name', array('label' => 'Nome'));

		echo $this->Form->input('login', array('label' => 'Email / Login'));

		echo $this->Form->input('password', array('type' => 'password', 'label' => 'Senha'));

		echo $this->Html->tag('div', 
			$this->Form->button('Salvar', array('type' => 'submit', 'class' => 'btn btn-primary')).
			'&nbsp;' . 
			$this->Html->link('Cancelar', array('action' => 'index') ,array('class' => 'btn')) ,
			array('class' => 'input submit')
		);


		echo $this->Form->end(); 
	?>

</div>