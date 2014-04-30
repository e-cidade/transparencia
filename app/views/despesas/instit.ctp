<div id="breadcrumb">
  <?php
  
    $this->Html->addCrumb('Consulta Dados'           , '/main/consulta_dados') ;
    $this->Html->addCrumb('Despesas'                 , '/despesas') ;
    $this->Html->addCrumb('Despesas por Instituição' , '/despesas/instit') ;
 
    echo $html->getCrumbs('  > ', 'Principal');
  ?>
</div>
<br>
<h2 align="center">Gastos por Instituição / Orgão</h2>
<br>
<div>
<table id="list"></table>
</div>
<script type="text/javascript">

  $(document).ready(
  
    function(){  

	    jQuery("#list").jqGrid({
		    url: '<?php echo $this->base."/despesas/getInstit/{$iExercicio}"?>',
			  datatype: "json",
			  mtype: "POST",
			    colNames:['Instituição','Empenhado','Anulado','Liquidado','Pago'],
			    colModel:[
			      {name:'nome'          ,index:'nome'        , width:'50', align:'left'},
			      {name:'vlr_empenhado' ,index:'vlr_empenhado',width:'15', align:'right', formatter:'currency'},
			      {name:'vlr_anulado'   ,index:'vlr_anulado'  ,width:'15', align:'right', formatter:'currency'},
			      {name:'vlr_liquidado' ,index:'vlr_liquidado',width:'15', align:'right', formatter:'currency'},
			      {name:'vlr_pago'      ,index:'vlr_pago'     ,width:'15', align:'right', formatter:'currency'}
			    ],
			    rowNum:10,
	        autowidth: true,
			    sortname: 'nome',
			    viewrecords: true,
			    sortorder: "desc",
			    altRows: true,
			    height: '150px',
			    altRows:true,
			    parameters:'teste:10',
			    ondblClickRow: function (id) { 
			      document.location.href = '<?php echo $this->base ?>/despesas/instituicao/'+id;
			    }
		  });

		
    }
  );

</script>
