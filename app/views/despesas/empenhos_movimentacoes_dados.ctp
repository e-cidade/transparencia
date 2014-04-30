<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb">
  <?php
      
    $this->Crumb->addPage($aParametros['iNivel'],'Dados da Movimentação do Empenho') ;
    
    echo $this->Crumb->getHtml();
  ?>
</div>
<br>
<h2 align="center">Dados Movimentação Empenho<?php echo " / ".$aParametros['iExercicio'];?></h2>
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
			<td align="left">Data</td>
			<td align="left"><?php echo $this->Formatacao->data($aDadosMovimentacao['data']);?></td>
		</tr>
		<tr>
			<td align="left">Valor</td>
			<td align="left"><?php echo $this->Formatacao->moeda($aDadosMovimentacao['valor']);?></td>
		</tr>
		<tr>
			<td align="left">Historico</td>
			<td align="left"><?php echo $aDadosMovimentacao['historico'];?></td>
		</tr>
	</table>
</div>
<br>
<?php echo $this->Transparencia->showDataAtualizacao($aParametros['dtDataImportacao']);?>