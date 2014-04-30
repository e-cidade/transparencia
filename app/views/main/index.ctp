<?php
	$aOptions1 = array('update'    => 'content',
	                   'url'       => array('action' => 'loadMenu','pagina_principal'),
	                   'form'      => 'get', 
	                   'indicator' => 'ajax-loader');
	
	$aOptions2 = array('update'    => 'content',
	                   'url'       => array('action' => 'loadMenu','o_que_e_portal'),
	                   'form'      => 'get', 
	                   'indicator' => 'ajax-loader');
	 
	$aOptions3 = array('update'    => 'content',
	                   'url'       => array('action' => 'loadMenu','como_consultar'),
                     'form'      => 'get',	 
	                   'indicator' => 'ajax-loader');
	
	$aOptions4 = array('update'    => 'content',
	                   'url'       => array('action' => 'loadMenu','origem_dados'),
                     'form'      => 'get',	 
	                   'indicator' => 'ajax-loader');
	
	$aOptions5 = array('update'    => 'content',
	                   'url'       => array('action' => 'loadMenu','glossario'),
                     'form'      => 'get',	 
	                   'indicator' => 'ajax-loader');

?>
  <div id="navbar">
	  <ul>
	 	  <li><?php echo $this->Ajax->link('Página Principal'  ,array(),$aOptions1);?></li>
		  <li><?php echo $this->Ajax->link('O que é o Portal'  ,array(),$aOptions2);?></li>
		  <li><?php echo $this->Ajax->link('Como Consultar'    ,array(),$aOptions3);?></li>
		  <li><?php echo $this->Ajax->link('Origem dos Dados'  ,array(),$aOptions4);?></li>
		  <li><?php echo $this->Html->link('Consulta Dados'    ,'consulta_dados');  ?></li>
		  <li><?php echo $this->Ajax->link('Glossário'         ,array(),$aOptions5);?></li>
		</ul>
		
		<span><strong>Visitantes:</strong> <?php echo $this->viewVars['iNumeroVisitantes']?></span>
  </div>
	<div id="content">
	</div>
<script type="text/javascript">

  <?php echo $this->Ajax->remoteFunction(array('update'=>'content',
                                               'url'   => array('action' => 'loadMenu','pagina_principal'),
                                               'form'  => 'get',  
                                               'indicator' => 'ajax-loader'));?>

</script>