<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb">
  <?php
    
    $aOptions = array('update' =>'main_content',
                      'url'    => array('action' => 'loadLink','receitas'),
                      'data'   => $this->JavaScript->object($aParametros));
    
    $iCountHist = ( count($aParametros['aHistorico']) - 1);
      
    $this->Crumb->addPage($aParametros['iNivel'],$aParametros['aHistorico'][$iCountHist]['descricao'],null,$aOptions);
    
    echo $this->Crumb->getHtml();
  ?>
</div>
<br>
<h2 align="center">Detalhe Receitas<?php echo " <span id='mesConsulta'>{$aParametros['iMes']}</span> / ".$aParametros['iExercicio'];?></h2>
<br>
<? echo $this->Transparencia->showHistoricoReceita($aParametros['aHistorico']); ?>
<br>
<div>
<table id="list"></table>
<div id="pager"></div>
<br>
<?php echo $this->Transparencia->showDataAtualizacao($aParametros['dtDataImportacao']);?>
</div>
<script type="text/javascript">

  $(document).ready(
  
    function(){  

      var oParametros = $.JSON.decode('<?php echo $this->JavaScript->object($aParametros);?>');

	    jQuery("#list").jqGrid({
		      url: '<?php echo $this->base;?>/receitas/getReceitas',
			  datatype: "json",
			  mtype: "POST",
		      postData:{aParametros:$.JSON.encode(oParametros)},
			    colNames:['Estrutural',
			              'Analitica',
			              'Descrição',
			              'Previsto',
			              'Adicional',
			              'Arrecadado',
			              'Acumulado' ,
			              'Diferença'
			              
			         ],
			    colModel:[
			    
			      {name:'estrutural'             ,index:'estrutural'         ,width:'20', align:'center', hidden:true},
			      {name:'analitica'              ,index:'analitica'          ,width:'20', align:'center', hidden:true},
			      {name:'descricao'              ,index:'descricao'          ,width:'50', align:'left'  },
			      {name:'previsao_inicial'       ,index:'previsao_inicial'           ,width:'15', align:'right' , search:false, formatter:'currency'},
			      {name:'previsao_adicional'     ,index:'previsao_adicional' ,width:'15', align:'right' , search:false, formatter:'currency'},
			      {name:'valor_arrecadado'       ,index:'valor_arrecadado'         ,width:'15', align:'right' , search:false, formatter:'currency'},
			      {name:'arrecadado_acumulado'   ,index:'arrecadado_acumulado'     ,width:'15', align:'right' , search:false, formatter:'currency'},
			      {name:'valor_diferenca'        ,index:'valor_diferenca'    ,width:'15', align:'right' , search:false, formatter:'currency'}],
			    
			    rowNum:10,
			    rowList:[10,20,30],
			    altRows: true,
	            autowidth: true,
			    viewrecords: true,
			    sortname:  'descricao',
			    sortorder: 'asc',
			    height: '220px',
			    pager: '#pager',
			    onCellSelect: function (id) {
			    
			    var oDados = jQuery("#list").jqGrid('getRowData',id);
			    
			    if ( oDados.analitica != 'true' ) {
			            
	              oParametros.aHistorico.push(oDados)
	              oParametros.sEstrutural = oDados.estrutural;
                  oParametros.iNivel++;
                  oParametros.iMes =   $('#mesConsulta').html();
                  

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
				                  url:'<?php echo $this->base ?>/receitas/loadLink/receitas'
				                 }) 
			        }
			    }
		  });
		  
		  jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
    }
  );

</script>
