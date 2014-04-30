<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb"><?php

$aOptions = array('update' =>'main_content',
                  'url'    => array('action' => 'loadLink','recursos'),
                  'data'   => $this->JavaScript->object($aParametros));      

$iCountHist = ( count($aParametros['aHistorico']) - 1);

echo $this->Crumb->getHtml($aParametros['aHistorico'][$iCountHist]['descricao'],'4',null,true,$aOptions) ;
?></div>
<br>
<?  echo $this->Transparencia->showHistorico($aParametros['aHistorico']);?>
<br>
<h2 align="center">Receitas por Recurso<?php echo " <span id='mesConsulta'>{$aParametros['iMes']}</span> / ".$aParametros['iExercicio'];?></h2>
<br>
<table id="list"></table>
<div id="pager"></div>
<br>
<?php echo $this->Transparencia->showDataAtualizacao($aParametros['dtDataImportacao']);?>
<br>
<script type="text/javascript">

  $(document).ready(
  
    function(){
      
      var oParametros = $.JSON.decode('<?php echo $this->JavaScript->object($aParametros);?>');

	    jQuery("#list").jqGrid({
	          url: '<?php echo $this->base;?>/receitas/getRecursos',
			  datatype: "json",
			  mtype: "POST",
              postData:{aParametros:$.JSON.encode(oParametros)},
                colNames:['Descrição',
                          'Previsto',
                          'Adicional',
                          'Arrecadado',
                          'Acumulado' ,
                          'Diferença'],
			  colModel:[
                {name:'descricao' ,index:'descricao' ,width:'50', align:'left'},
                {name:'previsto'           ,index:'previsto'           ,width:'15', align:'right' , search:false, formatter:'currency'},
                {name:'previsao_adicional' ,index:'previsao_adicional' ,width:'15', align:'right' , search:false, formatter:'currency'},
                {name:'arrecadado'         ,index:'arrecadado'         ,width:'15', align:'right' , search:false, formatter:'currency'},
                {name:'arrecadado_ano'     ,index:'arrecadado_ano'     ,width:'15', align:'right' , search:false, formatter:'currency'},
                {name:'valor_diferenca'    ,index:'valor_diferenca'    ,width:'15', align:'right' , search:false, formatter:'currency'}
              ],
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
	          oParametros.aHistorico.push(oDados) 
	          oParametros.iRecurso = id;
	          oParametros.iMes     = $('#mesConsulta').html();
	          
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
                      url:'<?php echo $this->base?>/receitas/loadLink/receitas'
                    })
        }           			    
		  });
		  
      jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
      
    }
  );

</script>
