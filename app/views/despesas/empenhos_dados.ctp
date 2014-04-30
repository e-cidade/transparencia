<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb">
  <?php 
      
    $this->Crumb->addPage($aParametros['iNivel'],'Dados Empenhos') ;
    
    echo $this->Crumb->getHtml();
      
  ?>
</div>
<br>
  <h2 align="center">Dados Empenho<?php echo " / ".$aParametros['iExercicio'];?></h2>
<br>
  <? echo $this->Transparencia->showHistoricoDespesa($aParametros['aHistorico']); ?>
<br>
<div class="dados_empenhos">
	<table width="100%;" cellspacing="0">
		<tr>
			<th width="20%" align="center">Campo</th>
			<th width="80%" align="center">Conteúdo</th>
		</tr>
    <tr>
      <td align="left">Número</td>
      <td align="left"><?php echo $aDadosEmpenho['codigo'];?></td>
    </tr>		
    <tr>
      <td align="left">Tipo Compra</td>
      <td align="left"><?php echo utf8_encode($aDadosEmpenho['tipo_compra']);?></td>
    </tr>    
    <tr>
      <td align="left">Processo de Compra</td>
      <td align="left"><?php echo $sProcessos;?></td>
    </tr>    
    <tr>
      <td align="left">Recurso</td>
      <td align="left"><?php echo utf8_encode($aDadosEmpenho['descrrecurso']);?></td>      
    </tr>    
		<tr>
			<td align="left">Data</td>
			<td align="left"><?php echo $this->Formatacao->data($aDadosMovimentacao['data']);?></td>
		</tr>
		<tr>
			<td align="left">Valor</td>
			<td align="left"><?php echo $this->Formatacao->moeda($aDadosMovimentacao['valor']);?></td>
		</tr>
		<tr>
			<td align="left">Historico</td>
      <td align="left"><?php echo utf8_encode($aDadosEmpenho['resumo']);?></td>
		</tr>
	</table>
</div>
<br>
<div>
  <table id="list_itens" ></table>
  <div   id="pager_itens"></div>
  <br>
  <?php echo $this->Transparencia->showDataAtualizacao($aParametros['dtDataImportacao']);?>
</div>
<script type="text/javascript">

  $(document).ready(
  
    function(){
          
      var oParametros = $.JSON.decode('<?php echo $this->JavaScript->object($aParametros);?>');  
      
      jQuery("#list_itens").jqGrid({
        url: '<?php echo $this->base?>/despesas/getItensEmpenho',
        datatype: "json",
        mtype: "POST",
        postData:{aParametros:$.JSON.encode(oParametros)}, 
          colNames:['Descrição','Quantidade','Valor Unitário','Valor Total'],
          colModel:[
            {name:'descricao'      ,index:'descricao'      ,width:'250px', align:'left'  },
            {name:'quantidade'     ,index:'quantidade'     ,width:'50px' , align:'center', search:false },
            {name:'valor_unitario' ,index:'valor_unitario' ,width:'80px', align:'right'  , search:false, formatter:'currency'},
            {name:'valor_total'    ,index:'valor_total'    ,width:'80px', align:'right'  , search:false, formatter:'currency'},            
          ],
          rowNum:4,
          rowList:[10,20,30],
          pager: '#pager_itens',
          autowidth: true,
          sortname: 'descricao',
          viewrecords: true,
          sortorder: "asc",
          height: '88px',
          altRows:true,
          hoverrows:false,
          caption: 'Itens do Empenho'
      });
      
      jQuery("#list_itens").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
    }
  );

</script>