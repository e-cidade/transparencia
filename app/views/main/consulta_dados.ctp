<div id="breadcrumb">
<?php

  $this->Crumb->addPage(0,'Principal'     ,'main');
  $this->Crumb->addPage(1,'Consulta Dados','main/consulta_dados') ;
  
  echo $this->Crumb->getHtml();
?>
</div>
<br>
<div id="consulta_dados">
 <?php 

    foreach ($aItensPagina as $oItem) {
    
      if ($oItem->habilitado) {
        
        echo $this->Html->tag('fieldset',  null);
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
<br>
<br>