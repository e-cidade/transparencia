<h2>Preferências</h2>
<?php
echo $this->Form-> create();
echo $this->Form->input('id');

echo $this->Form->input('contador_visitas', array('type'  => 'checkbox',
		                                              'label' => 'Exibir Contador de Visitas') );

echo $this->Form->button('Salvar', array('type' => 'submit', 'class' => 'btn btn-primary')) . "&nbsp;";
echo $this->Html->link('Voltar', array('controller' => 'dashboard', 'action' => 'index') ,array('class' => 'btn'));
//echo $this->Form->end('Salvar');
?>