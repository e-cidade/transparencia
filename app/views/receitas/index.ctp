<div id="breadcrumb">
  <?php 
    
    $this->Crumb->addPage(0,'Principal'     ,'main');
    $this->Crumb->addPage(1,'Consulta Dados','main/consulta_dados') ;
    $this->Crumb->addPage(2,'Receitas'      ,'receitas') ;
  
    echo $this->Crumb->getHtml();    
  ?>
</div>
<br>
<div>
  <label>Exercício a ser consultado :</label> 
  <select id="mesConsulta" style="width:100px;">
    <option value='01'>Janeiro</option>
    <option value='02'>Fevereiro</option>
    <option value='03'>Março</option>
    <option value='04'>Abril</option>
    <option value='05'>Maio</option>
    <option value='06'>Junho</option>
    <option value='07'>Julho</option>
    <option value='08'>Agosto</option>
    <option value='09'>Setembro</option>
    <option value='10'>Outubro</option>
    <option value='11'>Novembro</option>
    <option value='12'>Dezembro</option>
  </select> 
  
  &nbsp; Ano: 
  
  
  <select id="exercicioConsulta"  style="width:80px;">
	<?php
		foreach ($aListaExercicios as $aExercicio ) {
		 echo "<option>".$aExercicio[0]['exercicio']."</option>";
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
					<li><a href="#" onClick="js_loadMenu('receitas');">Tipos de Receitas ( Classificação Econômica )</a></li>
          <li><a href="#" onClick="js_loadMenu('receitas', 1);">Tipos de Receitas ( Liquidas das Deduções )</a></li>
          <li><a href="#" onClick="js_loadMenu('recursos');">Finalidade de Receita ( Recurso )            </a></li>
				</ul>
			</div>
		</fieldset>	
  </div>
</div>
<script type="text/javascript">
  
  /**
   * Carrega as informações a serem exibidas de acordo com o tipo de consulta
   *
   * @param String sMenu - Nome da view a ser renderizada
   * @param Integer iTipoExibicao - Altera o modo de exibição dos dados de acordo com o tipo passado
   *                1 : Exibe apenas as receitas já subtraídas as deduções
   */
  function js_loadMenu(sMenu, iTipoExibicao) {
    var oParametros                  = new Object();
        oParametros.iExercicio       = $('#exercicioConsulta').val();
        oParametros.iMes		         = $('#mesConsulta').val();
        oParametros.dtDataImportacao = '<?php echo $dtDataImportacao;?>';
        oParametros.sTipoConsulta    = sMenu;
        oParametros.sEstrutural      = '';
        oParametros.iNivel           = 3;

    if (iTipoExibicao) {
      oParametros.iTipoExibicao = iTipoExibicao;
    }
        
    var aHistorico  = new Object();
    
        aHistorico.descricao   = 'Descrição';
        aHistorico.valor       = 'Valor';
                                
        oParametros.aHistorico = new Array();
        oParametros.aHistorico.push(aHistorico);
  
	  $.ajax({ 
	    url        : '<?php echo $this->base ?>/receitas/loadLink/instituicoes',
      async      : true, 
      type       : 'post', 
      dataType   : 'json',
      data       : oParametros,               
      beforeSend : function(request) { 
        $('#ajax-loader').show();
      }, 
      complete   : function(request, json) { 
        $('#main_content').html(request.responseText); 
        $('#ajax-loader').hide();
      }
	  }); 
  }
</script>
