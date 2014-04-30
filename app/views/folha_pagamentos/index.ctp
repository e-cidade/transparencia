<?php echo $this->Html->script('folha_pagamento'); ?>

<div id="breadcrumb">
  <?php 
    
    $this->Crumb->addPage(0,'Principal'     ,'main');
    $this->Crumb->addPage(1,'Folha de Pagamento / Pessoal','folha_pagamentos/index');
  
    echo $this->Crumb->getHtml();    
  ?>
</div>

<br>

<div class="main">
  <h3>Lei de Acesso à Informação</h3>

  <?php 
    echo $this->Form->create('Filtro', array(
      'url' => array(
        'plugin' => false,
        'controller' => 'folha_pagamentos',
        'action' => 'pesquisar'
      ),
      'type' => 'GET'
    )); 

    echo $this->Form->input('instituicao', array('options' => $aInstituicoes, 'empty' => 'Selecione...', 'label' => 'Instituição', 'class' => 'required x6'));

    echo $this->Form->input('ano', array('options' => $aAnos, 'empty' => 'Selecione...', 'default' => date('Y'), 'class' => 'required'));

    echo $this->Form->input('mes', array('options' => $aMeses, 'empty' => 'Selecione...', 'class' => 'required', 'label' => 'Mês'));

    echo '<div class="input checkbox">';

    echo $this->Html->tag('label', 'Exibir demitidos');
    echo $this->Form->input('demitidos', array(
      'type'  => 'checkbox', 
      'label' => false,
      'div'   => false
    ));

    echo '</div>';

    echo $this->Form->input('cargo', array('options' => $aCargos, 'empty' => 'Todos', 'label' => 'Cargo', 'class' => 'x6'));

    echo $this->Form->input('lotacao', array('options' => $aLotacoes, 'empty' => 'Todos', 'label' => 'Lotação', 'class' => 'x6'));

    echo $this->Form->input('vinculo', array('options' => $aVinculos, 'empty' => 'Todos', 'label' => 'Vínculo', 'class' => 'x6'));

    echo $this->Form->input('matricula', array('label' => 'Matrícula', "class" => "x1"));

    echo $this->Form->input('nome', array('label' => 'Nome', "class" => "x6"));

    echo $this->Html->tag('div', $this->Form->button('Pesquisar', array('type' => 'submit')), array('class' => 'buttons'));

    echo $this->Form->end(); 

  ?>
</div>