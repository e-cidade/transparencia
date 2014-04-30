<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb">
  <?php
  
    $aOptions = array('update' =>'main_content',
                      'url'    => array('action' => 'loadLink','instituicoes'),
                      'data'   => $this->JavaScript->object($aParametros));
      
    $this->Crumb->addPage($aParametros['iNivel'],'Instituições',null,$aOptions) ;
    
    echo $this->Crumb->getHtml();
  ?>
</div>
<br>
<? echo $this->Transparencia->showHistoricoReceita($aParametros['aHistorico']); ?>
<br>
<h2 align="center">Receitas por Instituição<?php echo  " &nbsp; {$aParametros['iMes']}  / ".$aParametros['iExercicio'];?></h2>
<br>
<table id="list"></table>
<br>
<?php echo $this->Transparencia->showDataAtualizacao($aParametros['dtDataImportacao']);?>
<br>
<script type="text/javascript">

  $(document).ready(
  
    function(){
      
      var oParametros = $.JSON.decode('<?php echo $this->JavaScript->object($aParametros);?>');

	    jQuery("#list").jqGrid({
	      url: '<?php echo $this->base;?>/receitas/getInstit',
			  datatype: "json",
			  mtype: "POST",
        postData:{aParametros:$.JSON.encode(oParametros)},

       
 	      colNames:[
 	      			'Instituição',
 	      			'Previsão Inicial',
 	      			'Previsão Adicional',
 	      			'Arrecadado',
 	      			'Acumulado',
 	      			'Diferença'
 	      			],
 	      			
			  colModel:[
		      {name:'descricao'            ,index:'descricao'            ,width:'100', align:'left'},
			  {name:'previsao_inicial'     ,index:'previsao_inicial'     ,width:'70', align:'right', formatter:'currency'},
			  {name:'previsao_adicional'   ,index:'previsao_adicional'   ,width:'70', align:'right', formatter:'currency'},
			  {name:'valor_arrecadado'     ,index:'valor_arrecadado'     ,width:'70', align:'right', formatter:'currency'},
			  {name:'valor_acumulado' 	   ,index:'valor_acumulado'      ,width:'70', align:'right', formatter:'currency'},
			  {name:'valor_diferenca' 	   ,index:'valor_diferenca'      ,width:'50', align:'right', formatter:'currency'}
			  ],
			  altRows:   true,
	          autowidth: true,
			  sortname:  'descricao',
			  sortorder: 'desc',
			  height:    '150px',
			  onCellSelect: function (id) {
 			      
 			  var oDados = jQuery("#list").jqGrid('getRowData',id);
	          oParametros.aHistorico.push(oDados);
	          oParametros.iInstituicao = id;
              oParametros.iNivel++;
            
            var sMenu = oParametros.sTipoConsulta; 			        
 			      
            $.ajax( 
                    { async:true, 
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
                      url:'<?php echo $this->base?>/receitas/loadLink/'+sMenu
                    })
        }           			    
		  });
    }
  );

</script>
