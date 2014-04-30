<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb">
  <?php

    $aOptions = array('update' =>'main_content',
                      'url'    => array('action' => 'loadView','instituicoes'),
                      'data'   => $this->JavaScript->object($aParametros));
      
    $this->Crumb->addPage($aParametros['iNivel'],'Instituições',null,$aOptions) ;
    
    echo $this->Crumb->getHtml();
  ?>
</div>
<br>
  <?php echo $this->Transparencia->showHistoricoDespesa($aParametros['aHistorico']); ?>
<br>
<h2 align="center">Despesas por Instituição<?php echo " / ".$aParametros['iExercicio'];?></h2>
<br>
<table id="list">
</table>
<br>
<?php echo $this->Transparencia->showDataAtualizacao($aParametros['dtDataImportacao']);?>
<br>
<script type="text/javascript">

  $(document).ready(
  
    function(){
      
      var oParametros = $.JSON.decode('<?php echo $this->JavaScript->object($aParametros);?>');

	    jQuery("#list").jqGrid({
	      url: '<?php echo $this->base;?>/despesas/getInstit',
			  datatype: "json",
			  mtype: "POST",
        postData:{aParametros:$.JSON.encode(oParametros)},
 	      colNames:['Instituição','Empenhado','Anulado','Liquidado','Pago'],
			  colModel:[
			      {name:'descricao'       ,index:'descricao'      ,width:'50', align:'left'},
			      {name:'valor_empenhado' ,index:'valor_empenhado',width:'15', align:'right', formatter:'currency'},
			      {name:'valor_anulado'   ,index:'valor_anulado'  ,width:'15', align:'right', formatter:'currency'},
			      {name:'valor_liquidado' ,index:'valor_liquidado',width:'15', align:'right', formatter:'currency'},
			      {name:'valor_pago'      ,index:'valor_pago'     ,width:'15', align:'right', formatter:'currency'}
			  ],
			  altRows:   true,
	      autowidth: true,
			  sortname:  'descricao',
			  sortorder: 'desc',
			  height:    '150px',
			  onCellSelect: function (id) {
 			      
 			      var oDados = jQuery("#list").jqGrid('getRowData',id);
	          oParametros.aHistorico.push(oDados) 
	          oParametros.iInstituicao = id;
            oParametros.iNivel++;
            
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
                      url:'<?php echo $this->base?>/despesas/loadLink/'+oParametros.iIdLink
                    })
        }           			    
		  });
    }
  );

</script>
