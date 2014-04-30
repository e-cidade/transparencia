<?php echo $this->Html->css('../cms/vendor/bootstrap/css/bootstrap.min'); ?>

<?php echo $this->Session->flash(); ?>

<fieldset>
	<legend>Acesso ao Sistema</legend>

<?php echo $this->Form->create('User', array(
	'class' => 'form-horizontal',
	'url' => array('controller' => 'users')
)); ?>

	<div class="control-group">

		<?php echo $this->Form->input('login', array('div' => 'controls', 'label' => 'Email', 'autofocus' => true, 'autocomplete' => 'off')); ?>

	</div>
	
	<div class="control-group">
		
		<?php echo $this->Form->input('password', array('div' => 'controls', 'label' => 'Senha')); ?>

	</div>
	
	<div class="control-group">
	
		<div class="controls">
	
			<?php echo $this->Form->button('Acessar', array('type' => 'submit', 'class' => 'btn btn-inverse'));?>

		</div>

	</div>

<?php echo $this->Form->end(); ?>

</fieldset>