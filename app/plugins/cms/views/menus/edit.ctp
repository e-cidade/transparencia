<?php echo $this->Html->css(array('../cms/vendor/redactor/css/redactor'));?>
<?php 
	echo $this->Html->script(array(
		'../cms/js/menus/edit', 
		'../cms/vendor/redactor/js/redactor.min',
		'../cms/js/redactor.pt-br'
	)); 
	?>
<h1>Menus</h1>

<div class="menu">

	<?php 
		echo $this->Form->create('Menu', array('type' => 'file'));

		echo $this->Form->input('id');

		echo $this->Form->input('parent_id', array('label' => 'Menu Pai', 'options' => $menuTree, 'empty' => ''));

		echo $this->Form->input('name', array('label' => 'Nome'));

		echo $this->Form->input('visible', array(
				'label' => 'Visível Usuário',
				'type' => 'checkbox' , 
				'checked' => (isset($this->data['Menu']['visible']) ? $this->data['Menu']['visible'] : true)
			));

		echo $this->Form->input('static', array('label' => 'Conteúdo Estático', 'class' => 'content-type', 'type' => 'checkbox'));

		echo '<div class="non-static">';

			echo $this->Form->input('path', array('empty' => '', 'options' => $methods, 'class' => ''));

			echo $this->Form->input('params', array('label' => 'Argumentos', 'class' => ''));

		echo '</div>';


		echo '<div class="static">';

			echo $this->Form->input('upload', array('label' => 'Enviar arquivo codificado', 'class' => ' method', 'type' => 'checkbox'));

			$fileLabel = '';

			if (!empty($this->data['Menu']['file'])) {
				$fileLabel = '<span style="color: red;"> - ' . $this->data['Menu']['file'] . '</span>';
			}

			echo $this->Form->input('_file', array('label' => 'Arquivo' . $fileLabel, 'class' => 'upload ', 'type' => 'file', 'div' => 'input file well'));

			echo $this->Form->input('file', array('type' => 'hidden'));

			echo $this->Form->input('content', array('label' => 'Conteúdo', 'type' => 'textarea', 'class' => 'redactor  non-upload'));

		echo '</div>';

		echo $this->Form->input('ajax', array('label' => 'Carregar por Ajax', 'type' => 'checkbox'));

		echo $this->Html->tag('div', 
			$this->Form->button('Salvar', array('type' => 'submit', 'class' => 'btn btn-primary')).
			'&nbsp;' . 
			$this->Html->link('Cancelar', array('action' => 'index') ,array('class' => 'btn')) ,
			array('class' => 'input submit')
		);


		echo $this->Form->end(); 
	?>

</div>