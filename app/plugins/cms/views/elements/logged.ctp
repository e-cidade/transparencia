
<div class="to-right">

	<div>
		Você está logado como: <?php echo $this->Session->read('Auth.User.name'); ?>
	</div>

	<div>
	
		<?php echo $this->Html->link('Área Administrativa', array('plugin' => 'cms', 'controller' => 'dashboard', 'action' => 'index'), array('class' => 'label')); ?>

		<?php echo $this->Html->link('Sair', array('plugin' => 'cms', 'controller' => 'users', 'action' => 'logout'), array('class' => 'label')); ?>
		
	</div>
</div>