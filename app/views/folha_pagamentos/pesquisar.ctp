<?php echo $this->Html->script('pesquisar'); ?>

<div class="main">
  
  <h3>Lei de Acesso à Informação</h3>
  <p>Resultados da busca de Servidores</p>
  <p>Competência: <?php echo str_pad($aParametros['mes'],2,'0', STR_PAD_LEFT) . '/' . $aParametros['ano']; ?></p>

  
  <?php echo $this->Form->input('parametro', array('value' => $this->Javascript->object($aParametros), 'type' => 'hidden')); ?>
  
  <table id="servidores"></table>
  <div id="pager"></div> 
  
  <?php 

    $aNewParametros = array();

    foreach ($aParametros as $key => $value) {
      $aNewParametros[] = $key .'='.$value;
    }

    echo $this->Html->tag('div', $this->Html->link('Voltar', 'index?'.implode('&',$aNewParametros), array()), array('class' => 'buttons')); 

  ?>

</div>