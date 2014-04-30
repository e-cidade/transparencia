<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb">
  <?php

    $aOptions = array('update' =>'main_content',
                      'url'    => array('action' => 'loadView','empenhos_movimentacoes'),
                      'data'   => $this->JavaScript->object($aParametros));
      
    $this->Crumb->addPage($aParametros['iNivel'],'Movimentações do Empenho',null,$aOptions) ;
    
    echo $this->Crumb->getHtml();
  ?>
</div>
<br>
<h2 align="center">Movimentações do Empenho<?php echo " / ".$aParametros['iExercicio'];?></h2>
<br>
<? echo $this->Transparencia->showHistoricoDespesa($aParametros['aHistorico']); ?>
<br>
<div>
<table id="gridDotacaoOrcamentaria" ></table>
<br>
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
		    url: '<?php echo $this->base?>/despesas/getMovimentacoesEmpenhos',
			  datatype: "json",
			  mtype: "POST",
			  postData:{aParametros:$.JSON.encode(oParametros)}, 
			    colNames:['Data','Tipo','Valor','Codigo Tipo'],
			    colModel:[
			      {name:'data'    ,index:'data'    ,width:'20', align:'center', formatter:'date'},
			      {name:'tipo'    ,index:'tipo'    ,width:'50', align:'left'},
			      {name:'valor'   ,index:'valor'   ,width:'20', align:'right' , formatter:'currency'},
			      {name:'codtipo' ,index:'codtipo' ,hidden: true },
			    ],
			    rowNum:10,
			    rowList:[10,20,30],
			    pager: '#pager',
	        autowidth: true,
			    sortname: 'data',
			    viewrecords: true,
			    sortorder: "asc",
			    altRows: true,
			    height: '220px',
			    altRows:true,
			    onCellSelect: function (id) {
			     
            var oDados = jQuery("#list").jqGrid('getRowData',id);
			       
  	        oParametros.iMovimentacaoEmpenho = id;    
            oParametros.iNivel++;
			    
		        if ( oDados.codtipo == 1 ) {
			        var sLink = 'empenhos_dados'; 
		        } else {
		          var sLink = 'empenhos_movimentacoes_dados';
		        }
			    
		        $.ajax({ async:true, 
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
		                 url:'<?php echo $this->base ?>/despesas/'+sLink
		               }) 
			    }
		  });
        
      jQuery("#gridDotacaoOrcamentaria").jqGrid({
        url: '<?php echo $this->base?>/despesas/getDotacaoEmpenho',
        datatype: "json",
        mtype: "POST",
        postData:{aParametros:$.JSON.encode(oParametros)}, 
          colNames:['','Código','Descrição'],
          colModel:[
            {name:'label'     ,index:'label'    ,width:'45px',   align:'left'  ,search:false, sortable:false},
            {name:'codigo'    ,index:'codigo'   ,width:'35px' ,  align:'center',search:false, sortable:false},
            {name:'descricao' ,index:'descricao',width:'170px' , align:'left'  ,search:false, sortable:false}            
          ],
          rowNum:8,
          autowidth: true,
          height: '184px',
          altRows:true,
          gridview:true,
          hoverrows:false,
          hiddengrid:true,
          caption: 'Dotação Orçamentária'
          
      });      
      
    }
  );

</script>