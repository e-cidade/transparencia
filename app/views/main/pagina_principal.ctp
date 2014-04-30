<?php header('Content-Type: text/html; charset=utf-8'); ?>
<br>
<div id="consulta_dados">
  <?php 
    $aStyleFieldSet = array('style' => 'background: #FFF;');
    foreach ($aItensPagina as $oItem) {
    
      if ($oItem->habilitado) {
        
        echo $this->Html->tag('fieldset',  null, $aStyleFieldSet);
        echo $this->Html->tag('legend',    $this->Html->link(utf8_encode($oItem->descricao), $oItem->acao));
        echo $this->Html->tag('div',       null);
        echo $this->Html->tag('p',         utf8_encode($oItem->resumo));
        echo $this->Html->tag('/div',      null);
        echo $this->Html->tag('/fieldset', null);
        echo $this->Html->tag('br /', null);
      }
    }
  ?>
</div>