
  <div id="navbar">
	  <?php echo $this->HelperMenu->generateMenu($menus); ?>
 
    <?php
      // validacao para mostrar ou nao o contador de visitas 
      if($this->viewVars['lNumeroVisitantes']){ ?>
    
			<span>
			  <strong>Visitantes:</strong> 
			  <?php 
			    echo $this->viewVars['iNumeroVisitantes'];
			  ?>
		  </span>
	  <?php }?>
	  
  </div>
	<div id="content">
	</div>

	<?php //echo $this->Html->script('../cms/js/menus/principal'); ?>

<script type="text/javascript">

	$(function() {
		$('#navbar ul:first li:first a').click();
	})

</script>