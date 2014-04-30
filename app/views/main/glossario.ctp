<?php header('Content-Type: text/html; charset=utf-8'); ?>
<h2>Glossário</h2>
<br>
<p>Esta seção traz conceitos básicos de diversos termos utilizados  na administração pública e apresentados no Portal da Transparência. O objetivo é facilitar a compreensão dos assuntos abordados no Portal para que o cidadão e o agente público tenham condições reais de exercer o controle social e fiscalizar o correto uso dos recursos públicos</p>
<br>
<br>
<div class="glossario">
	<ul>
	<?php 
	
	  foreach ($aListaTipos as $aTipo ) {
	  	
	    $aOptions = array('update'    => 'itensGloassario',
	                      'url'       => array('action' => 'loadMenu','itens_glossario'),
	                      'data'      => $this->Javascript->object($aTipo[0]),
	                      'indicator' => 'ajax-loader');  	
	  	
	  	echo "<li>".$this->Ajax->link($aTipo[0]['descricao'],array(),$aOptions)."</li>";
	      
	  }
	  
	?>
	</ul>
</div>
<br>
<br>
<br>
<div id="itensGloassario">
</div>
