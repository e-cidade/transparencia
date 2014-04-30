<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb">
  <?php

    $aOptions = array('update' =>'main_content',
                      'url'    => array('action' => 'loadView','empenhos'),
                      'data'   => $this->JavaScript->object($aParametros));
      
    $this->Crumb->addPage($aParametros['iNivel'],'Empenhos',null,$aOptions) ;
    
    echo $this->Crumb->getHtml();
  ?>
</div>
<br>
<h2 align="center">Despesas por Empenho<?php echo " / ".$aParametros['iExercicio'];?></h2>
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
		    url: '<?php echo $this->base?>/despesas/getEmpenhos',
			  datatype: "json",
			  mtype: "POST",
			  postData:{aParametros:$.JSON.encode(oParametros)}, 
			    colNames:['Empenho','Função','Sub-Função','Programa','Ação','Rubrica','Recurso','Data','Empenhado','Anulado','Liquidado','Pago'],
			    colModel:[
			      {name:'codempenho'           ,index:'codempenho'           ,width:'100px', align:'center'},
			      {name:'funcao_descricao'     ,index:'funcao_descricao'     ,width:'125px', align:'left'},
			      {name:'subfuncao_descricao'  ,index:'subfuncao_descricao'  ,width:'150px', align:'left'},
			      {name:'programa_descricao'   ,index:'programa_descricao'   ,width:'250px', align:'left'},
			      {name:'projeto_descricao'    ,index:'projeto_descricao'    ,width:'220px', align:'left'},
			      {name:'planoconta_descricao' ,index:'planoconta_descricao' ,width:'150px', align:'left'},
			      {name:'recurso_descricao'    ,index:'recurso_descricao'    ,width:'100px', align:'left'},
			      {name:'dataemissao'          ,index:'dataemissao'          ,width:'80px',  align:'center',formatter:'date', search:false},
			      {name:'valor_empenhado'      ,index:'valor_empenhado'      ,width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'valor_anulado'        ,index:'valor_anulado'        ,width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'valor_liquidado'      ,index:'valor_liquidado'      ,width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'valor_pago'           ,index:'valor_pago'           ,width:'100px', align:'right', search:false, formatter:'currency'}
			    ],
			    rowNum:10,
			    rowList:[10,20,30],
			    pager: '#pager',
	        autowidth: true,
          gridview: true,
          shrinkToFit:false,
			    sortname: 'codempenho',
			    viewrecords: true,
			    sortorder: "desc",
			    height: '240px',
			    altRows:true,
			    onCellSelect: function (id) {
			     
			        var oDados = jQuery("#list").jqGrid('getRowData',id);
			            oDados.descricao = 'EMPENHO : '+oDados.codempenho;
              oParametros.aHistorico.push(oDados) 
			        oParametros.iEmpenho = id;
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
			                 }); 
			    }
		  });
		  
      jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
      
    }
  );

</script>
