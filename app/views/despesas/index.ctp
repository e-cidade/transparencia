<div id="breadcrumb">
  <?php 
  
    $this->Crumb->addPage(0,'Principal'     ,'main');
    $this->Crumb->addPage(1,'Consulta Dados','main/consulta_dados') ;
    $this->Crumb->addPage(2,'Despesas'      ,'despesas') ;
  
    echo $this->Crumb->getHtml();
  ?>
</div>
<br>
<div>
  <label>Exercício a ser consultado :</label> 
  <select id="exercicioConsulta" style="width:80px;">
	<?php
		foreach ($aListaExercicios as $aExercicio ) {
		  echo "<option>".$aExercicio['Empenho']['exercicio']."</option>";
		}
	?>
  </select> 
	<br>
	<br>
	<div id="consulta_dados">
	<fieldset>
	  <legend>
	    Filtrar por:
	  </legend>
	  <div class="navbar_detalhe">
			<ul>
				<li><a href="#" onClick="js_loadMenu('1');">Despesas por Instituição / Orgão </a></li>
        <li><a href="#" onClick="js_loadMenu('3');">Despesas por Credor / Instituição</a></li>
				<li><a href="#" onClick="js_loadMenu('2');">Tipos de Despesas ( Elementos )  </a></li>
			</ul>
		</div>
	</fieldset>
	</div>
</div>
<br>
<script type="text/javascript">

  function js_loadMenu(sMenu) {
 
    var oParametros = new Object();
        oParametros.iExercicio       = $('#exercicioConsulta').val();
        oParametros.dtDataImportacao = '<?php echo $dtDataImportacao;?>';
        oParametros.sViewAtual       = '';
        oParametros.iNivel           = 3;
        
    var aHistorico  = new Object();
        aHistorico.descricao        = 'Descrição';
        aHistorico.valor_empenhado  = 'Valor Empenhado';
        aHistorico.valor_anulado    = 'Valor Anulado';
        aHistorico.valor_liquidado  = 'Valor Liquidado';
        aHistorico.valor_pago       = 'Valor Pago';   
                                
        oParametros.aHistorico = new Array();
        oParametros.aHistorico.push(aHistorico);
  
	  $.ajax( { async:true, 
	            type:'post', 
              dataType: 'json',
              data: oParametros, 	            
	            beforeSend:function(request) { 
	                           $('#ajax-loader').show();
	                       }, 
	            complete:  function(request, json) { 
	                           $('#main_content').html(request.responseText); 
	                           $('#ajax-loader').hide();
	                       }, 
	            url:'<?php echo $this->base ?>/despesas/loadLink/'+sMenu
	          } ); 
  }
</script>