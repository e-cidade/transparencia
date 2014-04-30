<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb">
  <?php

    $aOptions = array('update' =>'main_content',
                      'url'    => array('action' => 'loadView','elementos'),
                      'data'   => $this->JavaScript->object($aParametros));
      
    $this->Crumb->addPage($aParametros['iNivel'],'Elementos',null,$aOptions) ;
    
    echo $this->Crumb->getHtml();
  ?>
</div>
<br>
<h2 align="center">Despesas por Elemento<?php echo " / ".$aParametros['iExercicio'];?></h2>
<br>
<? echo $this->Transparencia->showHistoricoDespesa($aParametros['aHistorico']); ?>
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
		    url: '<?php echo $this->base."/despesas/getElementos"?>',
			  datatype: "json",
			  mtype: "POST",
        postData:{aParametros:$.JSON.encode(oParametros)},			  
			    colNames:['Grupo da Despesa','Elemento da Despesa','Empenhado','Anulado','Liquidado','Pago','Código Elemento'],
			    colModel:[
			      {name:'grupo'           ,index:'grupo'          ,width:'200px', align:'left'},
			      {name:'descricao'       ,index:'descricao'      ,width:'250px', align:'left'},
			      {name:'valor_empenhado' ,index:'valor_empenhado',width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'valor_anulado'   ,index:'valor_anulado'  ,width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'valor_liquidado' ,index:'valor_liquidado',width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'valor_pago'      ,index:'valor_pago'     ,width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'codcon'          ,index:'codcon'         ,hidden:true }
			    ],
			    rowNum:10,
			    rowList:[10,20,30],
			    pager: '#pager',
          gridview: true,
	        autowidth: true,
          shrinkToFit:false,
			    sortname: 'descricao',
			    viewrecords: true,
			    sortorder: "desc",
			    height: '240px',
			    altRows:true,
			    onCellSelect: function (id) {
			    
              var oDados = jQuery("#list").jqGrid('getRowData',id);
              oParametros.aHistorico.push(oDados) 			    
			        oParametros.iElemento      = oDados.codcon;
              oParametros.iNivel++;
              
			       
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
			                  url:'<?php echo $this->base ?>/despesas/loadLink/'+oParametros.iIdLink
			                 }) 
			    }
		  });
		  
		  jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		  
    }
  );

</script>
