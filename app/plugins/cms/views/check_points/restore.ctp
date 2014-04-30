<h2>Restaurar Estrutura</h2>
<?php
echo $this->Form-> create('CheckPoint');

echo $this->Form->input('snapshot', array(
    'type' => 'select',
    'label' => 'Arquivo de Estrutura:',
    'options' => $aSnapshot
  ));

echo $this->Html->tag('div',
      $this->Form->button('Restaurar', array('type' => 'submit', 'class' => 'btn btn-primary')).
      '&nbsp;' .
      $this->Html->link('Voltar', array('controller' => 'dashboard', 'action' => 'index') ,array('class' => 'btn')) ,
      array('class' => 'input submit')
    );

echo $this->Form->end();
?>